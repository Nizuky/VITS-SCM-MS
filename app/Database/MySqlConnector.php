<?php

namespace App\Database;

use Illuminate\Database\Connectors\MySqlConnector as BaseMySqlConnector;
use PDO;

class MySqlConnector extends BaseMySqlConnector
{
    /**
     * Create a new PDO connection.
     *
     * @param  string  $dsn
     * @param  array  $config
     * @param  array  $options
     * @return \PDO
     */
    public function createConnection($dsn, array $config, array $options)
    {
        // Create connection without database selection
        $dsnWithoutDb = preg_replace('/;dbname=[^;]+/', '', $dsn);
        
        $connection = parent::createConnection($dsnWithoutDb, $config, $options);
        
        // Manually select database with backticks to handle special characters
        if (isset($config['database']) && $config['database'] !== '') {
            $database = $config['database'];
            $connection->exec("USE `{$database}`");
        }
        
        return $connection;
    }
}
