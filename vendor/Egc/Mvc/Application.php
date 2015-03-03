<?php
namespace Egc\Mvc;

use Egc\Db\Adapter\MySql;
use Egc\Db\Adapter\Factory;

final class Application
{

    /**
     *
     * @var \PDO
     */
    private static $_db_adapter = null;

    private static $_instance = null;

    private static $_skipLayoutRender = false;

    private static $_config = array(
        'defaults' => array(
            'controller' => 'Application\Controller\IndexController',
            'action' => 'index',
            'error_controller' => 'Application\Controller\ErrorController',
            'error_not_found_action' => 'notfound',
            'layout_path' => LAYOUT_PATH
        )
    );

    private static $_content = '';

    private function __construct()
    {}

    public static function init($config = array())
    {
        self::$_config = array_merge(self::$_config, $config);
        self::$_db_adapter = Factory::getAdapterInstance(self::$_config);
    }

    public static function route()
    {
        $controller_class = self::$_config['defaults']['controller'];
        $action = self::$_config['defaults']['action'];
        $url = '';

        $request_url = '';
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_url = explode('?', $_SERVER['REQUEST_URI']);
            $request_url = $request_url[0];
        }
        $script_url = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';

        if ($request_url != $script_url)
            $url = trim(preg_replace('/' . str_replace('/', '\/', str_replace('index.php', '', $script_url)) . '/', '', $request_url, 1), '/');

        $segments = explode('/', $url);

        if (isset($segments[0]) && $segments[0] != '') {
            if (isset(self::$_config['controller_map']) && isset(self::$_config['controller_map'][$segments[0]])) {
                $controller_class = self::$_config['controller_map'][$segments[0]];
            }
        }

        if (isset($segments[1]) && $segments[1] != '')
            $action = $segments[1];

        $controller = new $controller_class();
        self::$_content = call_user_func_array(array(
            $controller,
            'dispatch'
        ), array_merge(array(
            'action' => $action
        ), array(
            'params' => array_slice($segments, 2)
        )));

        if (self::$_content instanceof View) {
            self::$_content = self::$_content->render();
        }
    }

    public static function render()
    {
        if (self::$_skipLayoutRender) {
            return self::$_content;
        } else {
            require self::$_config['defaults']['layout_path'];
        }
    }

    public static function partial($partial_name)
    {
        require ROOT_PATH . 'view/partials/' . $partial_name;
    }

    public static function getConfig()
    {
        return self::$_config;
    }

    /**
     *
     * @return \PDO
     */
    public static function getDbAdapter()
    {
        return self::$_db_adapter;
    }

    public static function disableLayoutRender($disable)
    {
        self::$_skipLayoutRender = $disable;
    }
}
