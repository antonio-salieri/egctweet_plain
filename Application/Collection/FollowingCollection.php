<?php
namespace Application\Collection;

use Application\Collection\BaseCollection;
use Application\Model\Following;

class FollowingCollection extends BaseCollection
{
    public function add($item)
    {
        if (!$item instanceof Following)
            throw new \Exception("Trying to add invalid item.");

        parent::add($item);
    }

    protected function _populateItems(array $items)
    {
    	foreach($items as $item_data)
    	{
    		if (is_array ( $item_data )) {
				$item = new Following();
				$item->exchangeArray ( $item_data );
  				$this->add($item);
    		}
    	}
    }
}
