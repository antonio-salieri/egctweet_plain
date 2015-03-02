<?php
namespace EgcTweet\Entity;

abstract class Base
{

    public function __call($method, $args)
    {
        if (! preg_match('/(?P<accessor>set|get)(?P<property>[A-Z][a-zA-Z0-9]*)/', $method, $match) || 
            ! property_exists(get_class($this), $match['property'] = lcfirst($match['property']))) {
            throw new \Exception(sprintf("'%s' does not exist in '%s'.", $method, __CLASS__));
        }
        
        switch ($match['accessor']) {
            case 'get':
                return $this->{$match['property']};
            case 'set':
                if (! $args) {
                    throw new InvalidArgumentException(sprintf("'%s' requires an argument value.", $method));
                }
                $this->{$match['property']} = $args[0];
                return $this;
        }
    }
    
    public function exchangeArray(array $data)
    {
        foreach (array_keys(get_object_vars($this)) as $prop_name)
        {
            $this->$prop_name = null;
            if (!empty($data[$prop_name]))
            {
                $this->$prop_name = $data[$prop_name];
            }
        }
    }
    
    public function getData()
    {
        return get_object_vars($this);
    }
}
