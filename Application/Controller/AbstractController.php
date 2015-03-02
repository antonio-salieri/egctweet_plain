<?php
namespace Application\Controller;

use Egc\Mvc\Controller;
use Application\Table\ProfileTable;

abstract class AbstractController extends Controller
{

    protected $profileTable = null;

    protected function getProfileTable()
    {
        if (! $this->profileTable) {
            $this->profileTable = new ProfileTable();
        }
        return $this->profileTable;
    }
}
