<?php
namespace Egc\Db\Adapter;

class PdoMysql extends AdapterAbstract
{
    public function quoteColumnName($col_name)
    {
        return "`{$col_name}`";
    }
}
