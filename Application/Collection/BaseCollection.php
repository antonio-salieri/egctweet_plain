<?php
namespace EgcTweet\Collection;

abstract class BaseCollection extends \ArrayIterator
{
    public function __construct(array $items = array())
    {
        $this->_populateItems($items);
    }
    
    public function add($item)
    {
        $this->append($item);
    }
    
    protected function _populateItems(array $items)
    {
        foreach ($items as $item)
        {
            $this->add($item);
        }
    }
    
}
