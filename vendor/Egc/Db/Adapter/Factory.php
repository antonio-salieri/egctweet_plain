<?php
namespace Egc\Db\Adapter;

class Factory
{

    const DB_CONFIG_KEY = 'db';
    const DRIVER_KEY = 'driver';
    const DEFAULT_DB_ADAPTER = 'pdo_mysql';
    const DRIVER_NAMESPACE = "Egc\\Db\\Adapter\\";

    /**
     *
     * @param array $config
     * @throws \Exception
     * @return AdapterInterface
     */
    public static function getAdapterInstance(array $config = array())
    {
        if (!isset($config[self::DB_CONFIG_KEY]))
        {
            throw new \Exception('No database configuration');
        }

        $driver = self::DEFAULT_DB_ADAPTER;
        if (isset($config[self::DB_CONFIG_KEY][self::DRIVER_KEY]))
        {
            $driver = $config[self::DB_CONFIG_KEY][self::DRIVER_KEY];
        }

        $instance = null;
        $class_name = self::DRIVER_NAMESPACE;
        $class_name .= implode("", array_map(function($el){return ucfirst($el);}, explode("_", $driver)));
        if (class_exists($class_name) &&
            in_array('Egc\Db\Adapter\AdapterInterface', class_implements($class_name)))
        {
            $instance = new $class_name($config[self::DB_CONFIG_KEY]);
        } else {
            throw new \Exception("Unkznown driver type '{$driver}'");
        }

        return $instance;
    }
}
