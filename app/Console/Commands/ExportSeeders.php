<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExportSeeders extends Command
{
    protected $signature = 'seed:export {--tables=* : Specific tables to export (can be repeated)} {--exclude=* : Tables to exclude (can be repeated)} {--chunk=500 : Rows per insert chunk}';

    protected $description = 'Export current database rows into seeder classes under database/seeders/dumps';

    public function handle(): int
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        // Try to list tables using Doctrine when available; otherwise fallback to driver-specific queries
        $allTables = collect();
        try {
            if (method_exists($connection, 'getDoctrineSchemaManager')) {
                $schemaManager = $connection->getDoctrineSchemaManager();
                $allTables = collect($schemaManager->listTableNames());
            }
        } catch (\Throwable $e) {
            // ignore and fallback below
        }

        if ($allTables->isEmpty()) {
            if ($driver === 'mysql') {
                $rows = DB::select("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
                // Result rows have property like 'Tables_in_{$database}' depending on DB name
                $dbName = $connection->getDatabaseName();
                $key = 'Tables_in_' . $dbName;
                $allTables = collect($rows)->map(function ($r) use ($key) {
                    $arr = (array) $r;
                    return $arr[$key] ?? reset($arr);
                });
            } elseif ($driver === 'sqlite') {
                $rows = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                $allTables = collect($rows)->map(fn ($r) => ((array) $r)['name'] ?? reset((array) $r));
            } elseif ($driver === 'pgsql') {
                $rows = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                $allTables = collect($rows)->map(fn ($r) => ((array) $r)['tablename'] ?? reset((array) $r));
            }
        }

        $include = collect($this->option('tables'))
            ->flatMap(fn ($t) => Str::of((string) $t)->explode(','))
            ->filter()
            ->map(fn ($t) => trim((string) $t))
            ->filter();

        $exclude = collect($this->option('exclude'))
            ->flatMap(fn ($t) => Str::of((string) $t)->explode(','))
            ->filter()
            ->map(fn ($t) => trim((string) $t))
            ->merge(['migrations']) // always exclude migrations
            ->unique();

        $tables = $allTables
            ->when($include->isNotEmpty(), fn ($c) => $c->intersect($include))
            ->diff($exclude)
            ->values();

        if ($tables->isEmpty()) {
            $this->warn('No tables to export. You can specify with --tables=table1,table2 or ensure your DB has tables.');
            return self::SUCCESS;
        }

        $outputDir = database_path('seeders/dumps');
        File::ensureDirectoryExists($outputDir);

    $chunk = max(1, (int) $this->option('chunk'));
        $exported = 0;

        foreach ($tables as $table) {
            $rows = DB::table($table)->get();
            $classBase = Str::studly($table) . 'TableSeeder';
            $namespace = 'Database\\Seeders\\Dumps';
            $filePath = $outputDir . DIRECTORY_SEPARATOR . $classBase . '.php';

            $insertChunks = [];
            if ($rows->isNotEmpty()) {
                $arrayRows = $rows->map(fn ($r) => (array) $r)->all();
                $insertChunks = array_chunk($arrayRows, $chunk);
            }

            // driver already resolved above

            $body = [];
            $body[] = '<?php';
            $body[] = '';
            $body[] = 'namespace ' . $namespace . ';';
            $body[] = '';
            $body[] = 'use Illuminate\\Database\\Seeder;';
            $body[] = 'use Illuminate\\Support\\Facades\\DB;';
            $body[] = '';
            $body[] = 'class ' . $classBase . ' extends Seeder';
            $body[] = '{';
            $body[] = '    public function run(): void';
            $body[] = '    {';
            // Disable FK checks
            if ($driver === 'mysql') {
                $body[] = "        DB::statement('SET FOREIGN_KEY_CHECKS=0;');";
            } elseif ($driver === 'sqlite') {
                $body[] = "        DB::statement('PRAGMA foreign_keys = OFF;');";
            } elseif ($driver === 'pgsql') {
                $body[] = "        DB::statement('SET CONSTRAINTS ALL DEFERRED;');";
            }
            if ($driver === 'sqlite') {
                $body[] = "        DB::table('" . addslashes($table) . "')->delete();";
                // reset autoincrement
                $body[] = "        try { DB::statement(\"DELETE FROM sqlite_sequence WHERE name='" . addslashes($table) . "'\"); } catch (\\Throwable $e) { /* ignore */ }";
            } else {
                $body[] = "        DB::table('" . addslashes($table) . "')->truncate();";
            }
            // Inserts
            foreach ($insertChunks as $chunkRows) {
                $export = var_export($chunkRows, true);
                $body[] = '        DB::table(\'' . addslashes($table) . '\')->insert(' . $export . ');';
            }
            // Re-enable FK checks
            if ($driver === 'mysql') {
                $body[] = "        DB::statement('SET FOREIGN_KEY_CHECKS=1;');";
            } elseif ($driver === 'sqlite') {
                $body[] = "        DB::statement('PRAGMA foreign_keys = ON;');";
            } elseif ($driver === 'pgsql') {
                $body[] = "        DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');";
            }
            $body[] = '    }';
            $body[] = '}';
            $body[] = '';

            File::put($filePath, implode(PHP_EOL, $body));
            $this->info("Exported {$table} -> {$filePath} (" . count($rows) . ' rows)');
            $exported++;
        }

        $this->info("Done. Exported seeders: {$exported}. Commit files in database/seeders/dumps to share data.");
        return self::SUCCESS;
    }
}
