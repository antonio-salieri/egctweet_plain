<?php
namespace Egc\Mvc;

abstract class AbstractView
{

    private $pageVars = array();

    public function __construct($vars = array())
    {
        foreach ($vars as $var => $val) {
            $this->set($var, $val);
        }
    }

    public function __get($var_name)
    {
        $result = null;
        if (isset($this->pageVars[$var_name]))
            $result = $this->pageVars[$var_name];

        return $result;
    }

    public function set($name, $value)
    {
        $this->pageVars[$name] = $value;
        return $this;
    }

    public function setVariable($name, $value)
    {
        $this->set($name, $value);
        return $this;
    }

    protected function _getVars()
    {
        return $this->pageVars;
    }

    abstract public function render();
}
