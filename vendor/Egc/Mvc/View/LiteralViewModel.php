<?php
namespace Egc\Mvc\View;

use Egc\Mvc\AbstractView;

class LiteralViewModel extends AbstractView
{
    protected $_contents = '';

    public function __construct($contents = '')
    {
        $this->_contents = $contents;
    }

    public function render()
    {
        return $this->_contents;
    }
}
