<?php
namespace Application\Controller;

use Egc\Auth\Identity;
use Egc\Mvc\View\ViewModel;

class UserController extends AbstractController
{

    const ROUTE_LOGIN = '/user/login';

    public function indexAction()
    {
        if (Identity::isAuthenticated()) {
            $this->redirect('/followings');
        } else {
            $this->redirect(self::ROUTE_LOGIN);
        }
    }

    public function loginAction()
    {
        $flash = '';
        if (Identity::isAuthenticated()) {
            return $this->redirect('/followings');
        } else
            if (isset($_POST['username']) && isset($_POST['password'])) {
                try {
                    Identity::authenticate($_POST['username'], $_POST['password']);
                } catch (\Exception $e) {
                    $flash = $e->getMessage();
                }

                if (Identity::isAuthenticated())
                    return $this->redirect('/followings');
                else
                    $flash .= "Authentication failed. Please try again.";
            }

        return new ViewModel('user/login.phtml', array(
            'flash' => $flash
        ));
    }

    public function logoutAction()
    {
        Identity::clearAuth();
        return $this->redirect('/');
    }
}
