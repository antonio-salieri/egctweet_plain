<?php
namespace Egc\Auth;

class Identity
{
    const SETUP_KEY = 'auth_setup';

    const IS_LOGGEDID_KEY = 'logged_in';

    public static function isAuthenticated()
    {
        return (isset($_SESSION[self::IS_LOGGEDID_KEY]) && $_SESSION[self::IS_LOGGEDID_KEY]);
    }

    public static function setAuthenticated($is_auth = true)
    {
        $_SESSION[self::IS_LOGGEDID_KEY] = $is_auth;
    }

    public function authenticate($identity, $password)
    {

    }
}
