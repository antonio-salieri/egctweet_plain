<?php
use Egc\Mvc\Controller;
return array (
    'defaults' => array(
        Controller::CONFIG_KEY_DEFAULT_CONTROLLER_NAME => 'Application\Controller\IndexController',
        Controller::CONFIG_KEY_ERROR_CONTROLLER_NAME => 'Application\Controller\ErrorController',
        Controller::CONFIG_KEY_ERROR_ACTION_NAME => 'index'
    ),

    'db' => array(
        'driver' => 'pdo_pgsql',
        'dsn' => 'pgsql:host=ec2-50-19-236-178.compute-1.amazonaws.com;dbname=ddb1mohh5qdt7t',
        'username' => 'cxogvztesjubuy',
        'passwd' => 'W5-dTZ6CbmzpUvd-T3sLiIYYmg',
        'options' => array()
    ),

    'controller_map' => array(
        'index' => 'Application\Controller\IndexController',
        'user' => 'Application\Controller\UserController',
        'followings' => 'Application\Controller\FollowingsController',
        'twitter' => 'Application\Controller\TwitterController',
        'error' => 'Application\Controller\ErrorController',
    ),

    'auth_setup' => array(
        'table_name' => 'users',
        'identity_field' => 'username',
        'password_field' => 'password',
        'identity_id_field' => 'id',
    ),

    'egc_tweet' => array(
        'access_token' => array(
            'token' => '400943385-NidLvjFoY0SP4qtTUF09Z5fNB6jBVNdP8q4017no',
            'secret' => 'RqBc2FRAjTzlHJxYHwx3L4lv0Bo8vJohN0qSlOar9V7Sh'
        ),
        'oauth_options' => array(
            'consumerKey' => 'uXRBhOaxJCgRHaJ3Ivq3gcGHO',
            'consumerSecret' => 'RauT7uJiYYDVOKdjovbnd7hy722h38TnNodzqxDQB0sF5Wl1fD'
        ),
        'http_client_options' => array(
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        )
    )

);
