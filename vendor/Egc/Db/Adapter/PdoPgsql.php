<?php
namespace Egc\Db\Adapter;

class PdoPgsql extends AdapterAbstract
{
    public function quoteColumnName($col_name)
    {
        return '"'.$col_name.'"';
    }
}
