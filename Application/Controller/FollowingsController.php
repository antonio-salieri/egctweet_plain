<?php
namespace Application\Controller;

use Egc\Auth\Identity;
use Egc\Mvc\View\ViewModel;

class FollowingsController extends AbstractController
{
    const FOLLOWINGS_FIELDSET_NAME = 'followings';
    const FOLLOWINGS_PER_USER = 3;

    public function indexAction()
    {
        $followingsTable = $this->getFollowingTable();
        if (! Identity::isAuthenticated()) {
            return $this->redirect(UserController::ROUTE_LOGIN);
        }

        $followings = $followingsTable->getUserFollowings(Identity::getId());

        return new ViewModel('followings/index.phtml', array(
            'followings' => $followings,
            'followings_max_count' => self::FOLLOWINGS_PER_USER
        ));
    }

    public function saveAction()
    {
        if (! Identity::isAuthenticated()) {
            return $this->redirect(UserController::ROUTE_LOGIN);
        }

        $collection = new FollowingCollection($_POST[self::FOLLOWINGS_FIELDSET_NAME]);
        $user_id = Identity::getId();
        if (!$user_id)
            throw new \Exception("Error occurred. Unable to fetch signed user id.");

        foreach ($collection as $item) {
            $following_name = $item->getFollowingName();
            if ($item->getFollowingId() && $following_name)
                $followingsTable->saveFollowing($item, $user_id);
            else if($item->getId() && empty($following_name))
                $followingsTable->deleteFollowing($item->getId(), $user_id);
        }

        return $this->redirect('/');
    }
}
