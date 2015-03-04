<?php
use Egc\Mvc\Controller;
return array (
    'defaults' => array(
        Controller::CONFIG_KEY_DEFAULT_CONTROLLER_NAME => 'Application\Controller\IndexController',
        Controller::CONFIG_KEY_ERROR_CONTROLLER_NAME => 'Application\Controller\ErrorController',
        Controller::CONFIG_KEY_ERROR_ACTION_NAME => 'index'
    ),

    'db' => array(
        'driver' => 'pdo_mysql',
        'dsn' => 'mysql:host=localhost;dbname=egctweet',
        'username' => 'egctweet',
        'passwd' => 'egctweet',
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
        'table_name' => 'user',
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
            'consumerKey' => 'F8r0UeWFSHsy6wfZE7xylb6Va',
            'consumerSecret' => 'HwnUmmxhCvVH4MsWyT8RNSwzBmjf4ntgpPO2p6MmmBjjdZNuwd'
        ),
        'http_client_options' => array(
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        )
    )

);
