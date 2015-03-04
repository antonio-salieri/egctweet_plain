<?php
namespace Egc\Mvc;

use Application\Controller\ErrorController;
class Controller
{
    const ACTION_NAME_POSTFIX = 'Action';

    public function redirect($loc)
    {
        session_write_close();
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $loc);
    }

    public function dispatch($action, $params = array())
    {
        /* @var $result View */
        $result = null;

        $config = Application::getConfig();
        if (substr($action, -(strlen(self::ACTION_NAME_POSTFIX))) !== self::ACTION_NAME_POSTFIX) {
            $action .= self::ACTION_NAME_POSTFIX;
        }

        if (! method_exists($this, $action)) {
            $action = $config['defaults']['notfound_action'];

            $result = $this->_getErrorController()->$action();
        } else {
            try {
                $result = $this->$action($params);
            } catch (\Exception $e) {
                error_log($e->getMessage());
                error_log($e->getTraceAsString());
                $result = $this->_getErrorController()->indexAction();
            }
        }

        return $result;
    }

    /**
     *
     * @return Controller
     */
    protected function _getErrorController()
    {
        $config = Application::getConfig();
        return new $config['defaults']['error_controller']();
    }
}
