<?php
namespace Application\Controller;

use Egc\Mvc\View\ViewModel;
use Egc\Service\Twitter\Response;
class ErrorController extends AbstractController
{

    public function indexAction()
    {
        $this->setResponseCode(Response::STATUS_CODE_500);
        return new ViewModel('error/index.phtml');
    }

    public function notfoundAction()
    {
        $this->setResponseCode(Response::STATUS_CODE_404);
        return new ViewModel('error/notfound.phtml');
    }
}
