<?php
namespace Egc\Db\Adapter;

interface AdapterInterface
{
    public function exec($statement);

    public function prepareExecuteAndFetch($query, array $params = array());
}
