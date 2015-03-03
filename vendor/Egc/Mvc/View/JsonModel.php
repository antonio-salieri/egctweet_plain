<?php
namespace Egc\Mvc\View;

use Egc\Mvc\AbstractView;
use Egc\Mvc\Application;

class JsonModel extends AbstractView
{
    public function render()
    {
        Application::disableLayoutRender(true);
        header("Content-type: application/json");
        return json_encode($this->_getVars());
    }

}
