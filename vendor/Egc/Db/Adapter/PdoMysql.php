<?php
namespace Egc\Db\Adapter;

class PdoMysql extends \PDO implements AdapterInterface
{

    protected static $_config = array();

    public function __construct($config = array())
    {
        self::$_config = $config;
        parent::__construct(self::$_config['dsn'], self::$_config['username'], self::$_config['passwd'], self::$_config['options']);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function exec($statement)
    {
        try {
            parent::exec($statement);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function prepareExecuteAndFetch($query, array $params = array())
    {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        $rowset = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $rowset;
    }
}
