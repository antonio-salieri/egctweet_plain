<?php
use Egc\Mvc\Application;

define('ROOT_PATH', realpath(dirname(__FILE__)) . '/../');
define("APPLICATION_PATH", ROOT_PATH . 'Application/');
define("LAYOUT_PATH", ROOT_PATH . 'view/layout/layout.phtml');

// Setup autoloading
require ROOT_PATH . 'init_autoloader.php';

session_start();

$config = array();
$config_file = ROOT_PATH . 'config/config.php';
if (file_exists($config_file))
    $config = require $config_file;

Application::init($config);
Application::route();
Application::render();
