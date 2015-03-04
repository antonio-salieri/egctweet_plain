<?php
namespace Egc\Mvc;

use Egc\Db\Adapter\MySql;
use Egc\Db\Adapter\Factory;
use Egc\Mvc\View\ViewModel;

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
            Controller::CONFIG_KEY_DEFAULT_CONTROLLER_NAME => 'Egc\Mvc\Controller',
            Controller::CONFIG_KEY_DEFAULT_ACTION_NAME => 'index',
            Controller::CONFIG_KEY_ERROR_CONTROLLER_NAME => 'Egc\Mvc\Controller',
            Controller::CONFIG_KEY_NOTFOUND_ACTION_NAME => 'notfound',
            Controller::CONFIG_KEY_ERROR_ACTION_NAME => 'error',
            'layout_path' => LAYOUT_PATH
        )
    );

    private static $_content = '';

    private function __construct()
    {}

    public static function init($config = array())
    {
        self::prepareConfig($config);
        self::$_db_adapter = Factory::getAdapterInstance(self::$_config);
    }

    private static function prepareConfig(array $config = array())
    {
        if (isset($config['defaults']))
        {
            foreach ($config['defaults'] as $name => $value)
            {
                self::$_config['defaults'][$name] = $value;
            }
            unset($config['defaults']);

            foreach ($config as $name => $value)
            {
                self::$_config[$name] = $value;
            }
        }
    }

    public static function route()
    {
        $controller_class = self::$_config['defaults'][Controller::CONFIG_KEY_DEFAULT_CONTROLLER_NAME];
        $action = self::$_config['defaults'][Controller::CONFIG_KEY_DEFAULT_ACTION_NAME];
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
            if (isset(self::$_config['controller_map']) &&
                isset(self::$_config[Controller::CONFIG_KEY_CONTROLLER_MAP][$segments[0]]))
            {
                $controller_class = self::$_config[Controller::CONFIG_KEY_CONTROLLER_MAP][$segments[0]];
                if (!class_exists($controller_class))
                {
                    $controller_class = self::$_config['defaults'][Controller::CONFIG_KEY_ERROR_CONTROLLER_NAME];
                    $action = self::$_config['defaults'][Controller::CONFIG_KEY_NOTFOUND_ACTION_NAME];
                }
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

        if (self::$_content instanceof AbstractView) {
            self::$_content = self::$_content->render();
        }
    }

    public static function render()
    {
        if (self::$_skipLayoutRender) {
            echo self::$_content;
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
