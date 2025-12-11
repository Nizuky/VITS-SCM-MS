<?php

namespace App\Database;

use Illuminate\Database\Connectors\MySqlConnector as BaseMySqlConnector;
use PDO;

class MySqlConnector extends BaseMySqlConnector
{
    /**
     * Get the DSN string for a host / port configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getDsn(array $config)
    {
        // Build DSN WITHOUT database name
        // We'll select it later with USE statement
        return $this->hasSocket($config)
            ? $this->getSocketDsn($config)
            : $this->getHostDsn($config);
    }

    /**
     * Get the DSN string for a host / port configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getHostDsn(array $config)
    {
        extract($config, EXTR_SKIP);

        // Don't include dbname in DSN - we'll USE it later
        return isset($port)
            ? "mysql:host={$host};port={$port}"
            : "mysql:host={$host}";
    }

    /**
     * Get the DSN string for a socket configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getSocketDsn(array $config)
    {
        // Don't include dbname in DSN
        return "mysql:unix_socket={$config['unix_socket']}";
    }

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
        $connection = parent::createConnection($dsn, $config, $options);
        
        // Manually select database with backticks to handle special characters like hyphens
        if (isset($config['database']) && $config['database'] !== '') {
            $database = str_replace('`', '``', $config['database']); // Escape backticks
            $connection->exec("USE `{$database}`");
        }
        
        return $connection;
    }
}
}
