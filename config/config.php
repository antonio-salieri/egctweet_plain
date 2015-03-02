<?php
return array (
    'pdo_mysql' => array(
        'dsn' => 'mysql:localhost;dbname=egctweet',
        'username' => 'egctweet',
        'passwd' => 'egctweet',
        'options' => array()
    ),

    'controller_map' => array(
        'index' => 'Application\Controller\IndexController',
        'profile' => 'Application\Controller\ProfileController',
        'twitter' => 'Application\Controller\TwitterController',
        'error' => 'Application\Controller\ErrorController',
    ),

    'auth_setup' => array(
        'table_name' => 'user',
        'identity_field' => 'username',
        'password_field' => 'password'
    )
);
