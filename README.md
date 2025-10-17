# VITS-SCM-MS

## Shared database seed data

This project can export the current database rows into seeder classes so that collaborators can seed their local DBs with the exact same data.

How it works:

1) Export seeders from your current database

- Exports one seeder per table into `database/seeders/dumps/`
- Truncates then inserts all rows in chunks when seeding

PowerShell (Windows):

```powershell
# Optional: choose specific tables
php artisan seed:export --tables=users,super_admins

# Or export everything (default excludes 'migrations')
php artisan seed:export
```

2) Commit the generated files

```powershell
git add database/seeders/dumps
git commit -m "chore(seeds): export current DB data"
git push
```

3) Teammates can migrate + seed

```powershell
php artisan migrate:fresh --seed
```

Notes

- Generated seeder files live under the namespace `Database\\Seeders\\Dumps` and are auto-discovered by `DatabaseSeeder`.
- Foreign key checks are handled per driver (MySQL/SQLite/PGSQL) during truncation/inserts.
- To exclude certain tables, use `--exclude=table1,table2`.
- If you need to re-export, simply run `php artisan seed:export` again and re-commit updated files.
