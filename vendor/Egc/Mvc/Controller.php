<?php
namespace Egc\Mvc;

use Application\Controller\ErrorController;
use Egc\Mvc\View\ViewModel;
use Egc\Mvc\View\LiteralViewModel;
class Controller
{
    const ACTION_NAME_POSTFIX = 'Action';
    const CONFIG_KEY_DEFAULT_ACTION_NAME = 'action';
    const CONFIG_KEY_DEFAULT_CONTROLLER_NAME = 'controller';

    const CONFIG_KEY_ERROR_CONTROLLER_NAME = 'error_controller';
    const CONFIG_KEY_ERROR_ACTION_NAME = 'error_not_found_action';
    const CONFIG_KEY_NOTFOUND_ACTION_NAME = 'error_not_found_action';

    const CONFIG_KEY_CONTROLLER_MAP = 'controller_map';

    protected static $_responseCode = 200;

    protected $_requestParams = array();


    public function redirect($loc)
    {
        session_write_close();
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $loc);
        die();
    }

    public function dispatch($action, $params = array())
    {
        /* @var $result View */
        $result = null;

        $config = Application::getConfig();
        $action = $this->_getNormalizedActionName($action);

        if (!empty($params) && is_array($params)) {
            $this->setRequestParams($params);
        }

        if (! method_exists($this, $action)) {

            $action = $this->_getNormalizedActionName(
                $config['defaults'][self::CONFIG_KEY_NOTFOUND_ACTION_NAME]
            );

            $result = $this->_getErrorController()->$action();
        } else {
            try {
                $result = $this->$action();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                error_log($e->getTraceAsString());
                $result = $this->errorAction();
            }
        }

        http_response_code(self::$_responseCode);

        return $result;
    }

    protected function _getNormalizedActionName($name)
    {
        if (substr($name, -(strlen(self::ACTION_NAME_POSTFIX))) !== self::ACTION_NAME_POSTFIX) {
            $name .= self::ACTION_NAME_POSTFIX;
        }
        return $name;
    }

    /**
     *
     * @return Controller
     */
    protected function _getErrorController()
    {
        $config = Application::getConfig();
        if(class_exists($config['defaults'][CONFIG_KEY_ERROR_CONTROLLER_NAME]))
            $controller = new $config['defaults'][CONFIG_KEY_ERROR_CONTROLLER_NAME]();
        else
            $controller = $this;
        return $controller;
    }

    protected function setResponseCode($responseCode)
    {
        if ($responseCode < 512)
        {
            self::$_responseCode = $responseCode;
        }
    }

    protected function setRequestParams(array $rawParams)
    {
        $params = array();
        for ($i = 0; $i < count($rawParams); $i++)
        {
            $name = $rawParams[$i];
            $i++;
            $value = null;
            if (isset($rawParams[$i]))
            {
                $value = $rawParams[$i];
            }
            $params[$name] = $value;
        }

        $this->_requestParams = $params;
        return $this;
    }

    protected function getParamFromRequest($name)
    {
        $value = null;
        if (isset($this->_requestParams[$name]))
        {
            $value = $this->_requestParams[$name];
        }
        return $value;
    }

    protected function getRequestParams()
    {
        return $this->_requestParams;
    }

    public function errorAction()
    {
        $this->setResponseCode(Response::STATUS_CODE_500);
        return new LiteralViewModel("<h1>Ooops! Serrver error occurred, sorry for inconvenience.</h1>");
    }

    public function notfoundAction()
    {
        $this->setResponseCode(Response::STATUS_CODE_404);
        return new LiteralViewModel("<h1>Ooops! Requested file not found.</h1>");
    }
}
