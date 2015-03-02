<?php
namespace Application\Controller;

use Egc\Auth\Identity;
use Egc\Mvc\View;
class IndexController extends AbstractController
{

    public function indexAction()
    {
        $profile_table = $this->getProfileTable();

        if (! Identity::isAuthenticated()) {
            $followings = $profile_table->getThreeRandomFollowings();
        } else {
            $followings = $profile_table->getUserFollowings($identity->getId());
        }

        $view = new View('profile/index.phtml', array(
            'followings' => $followings
        ));
        //         $view->set('followings', );
        return $view->render();

        return new ViewModel();
    }
}
