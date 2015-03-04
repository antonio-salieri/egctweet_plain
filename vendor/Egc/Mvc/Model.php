<?php
namespace Egc\Mvc;

class Model
{

    protected $dynamicProperities = array();

    public function __construct(array $data = array())
    {
        if (! empty($data))
            $this->exchangeArray($data);
    }

    public function __call($method, $args)
    {
        if (! preg_match('/(?P<accessor>set|get)(?P<property>[A-Z][a-zA-Z0-9]*)/', $method, $match)) {
            throw new \Exception(sprintf("'%s' does not exist in '%s'.", $method, __CLASS__));
        }

        $is_dynamic_prop = false;
        if (! property_exists(get_class($this), $match['property'] = lcfirst($match['property'])))
            $is_dynamic_prop = true;

        switch ($match['accessor']) {
            case 'get':
                $val = null;
                if (! $is_dynamic_prop) {
                    $val = $this->{$match['property']};
                } else
                    if (isset($this->dynamicProperities[$match['property']])) {
                        $val = $this->dynamicProperities[$match['property']];
                    }
                return $val;
            case 'set':
                if (! $args) {
                    throw new InvalidArgumentException(sprintf("'%s' requires an argument value.", $method));
                }
                if (! $is_dynamic_prop) {
                    $this->{$match['property']} = $args[0];
                } else {
                    $this->dynamicProperities[$match['property']] = $args[0];
                }
                return $this;
        }
    }

    public function exchangeArray(array $data)
    {
        foreach (array_keys(get_object_vars($this)) as $prop_name) {
            $this->$prop_name = null;
            if (! empty($data[$prop_name])) {
                $this->$prop_name = $data[$prop_name];
            }
        }
    }

    public function getData()
    {
        return get_object_vars($this);
    }
}
