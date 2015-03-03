<?php
namespace Application\Controller;

use Egc\Mvc\View\ViewModel;
class ErrorController extends AbstractController
{

    public function indexAction()
    {
        http_response_code(500);
        return new ViewModel('error/index.phtml');
    }

    public function notfoundAction()
    {
        http_response_code(404);
        return new ViewModel('error/notfound.phtml');
    }
}
