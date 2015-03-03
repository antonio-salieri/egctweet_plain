<?php
namespace Egc\Mvc\View;

use Egc\Mvc\AbstractView;

class ViewModel extends AbstractView
{

    private $pageVars = array();

    private $template;

    public function __construct($template, $vars = array())
    {
        $this->template = ROOT_PATH . 'view/' . $template;

        parent::__construct($vars);
    }

    public function render()
    {
        extract($this->pageVars);

        ob_start();
        require ($this->template);
        return ob_get_clean();
    }
}
