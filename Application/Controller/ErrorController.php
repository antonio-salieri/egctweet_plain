<?php
namespace Application\Controller;

class ErrorController extends AbstractController
{

    public function indexAction()
    {
        http_response_code(500);
        $view = new View('error/index.phtml');
    }
}
