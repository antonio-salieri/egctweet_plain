<?php
namespace Egc\Db\Adapter;

use Egc\Mvc\Application;
class MySql implements AdapterInterface
{
    protected static $_instance = null;
    const CONFIGURATION_KEY = 'pdo_mysql';

    protected static $_config = array();

    protected function __construct($config)
    {
        if (!isset($config[self::CONFIGURATION_KEY]))
        {
            throw new \Exception("No pdo_mysql configuration set.");
        }

        self::$_config = $config[self::CONFIGURATION_KEY];
        self::$_instance = new \PDO(self::$_config['dsn'], self::$_config['username'], self::$_config['passwd'], self::$_config['options']);
    }

    public static function getInstance()
    {
        if (!self::$_instance)
        {
            self::$_instance = new self(Application::getConfig());
        }

        return self::$_instance;
    }
}
