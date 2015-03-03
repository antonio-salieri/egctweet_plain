<?php
namespace Application\Controller;

use Egc\Auth\Identity;
use Egc\Mvc\View\ViewModel;

class IndexController extends AbstractController
{

    public function indexAction()
    {
        $profile_table = $this->getFollowingTable();

        $followings = $profile_table->getAllFollowings();

        return new ViewModel('index/index.phtml', array(
            'followings' => $followings
        ));
    }
}
