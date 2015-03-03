<?php
namespace Application\Controller;

use Egc\Mvc\Controller;
use Application\Table\FollowingTable;
use Egc\Mvc\View;

abstract class AbstractController extends Controller
{

    protected $profileTable = null;

    protected function getFollowingTable()
    {
        if (! $this->profileTable) {
            $this->profileTable = new FollowingTable();
        }
        return $this->profileTable;
    }
}
