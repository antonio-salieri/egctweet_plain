<?php
namespace Egc\Auth;

use Egc\Mvc\Application;
class Identity
{
    const SETUP_KEY = 'auth_setup';
    const PASSWORD_FIELD_KEY = 'password_field';
    const IDENTITY_FIELD_KEY = 'identity_field';
    const IDENTITY_ID_FIELD_KEY = 'identity_id_field';

    const IS_LOGGEDID_KEY = 'logged_in';
    const IDENTITY_ID_KEY = 'identity_id';

    public static function getId()
    {
        $id = null;
        if (isset($_SESSION[self::IDENTITY_ID_KEY]))
            $id = $_SESSION[self::IDENTITY_ID_KEY];

        return $id;
    }

    public static function isAuthenticated()
    {
        return (isset($_SESSION[self::IS_LOGGEDID_KEY]) && $_SESSION[self::IS_LOGGEDID_KEY]);
    }

    protected static function setAuthenticated($is_auth = true, $user_id = null)
    {
        $_SESSION[self::IS_LOGGED_KEY] = $is_auth;
        if ($is_auth) {
            $_SESSION[self::IDENTITY_ID_KEY] = $user_id;
        } else {
            $_SESSION[self::IDENTITY_ID_KEY] = null;
        }
    }

    public static function authenticate($username, $password)
    {
        $db = Application::getDbAdapter();
        $config = Application::getConfig();
        $table = $config[self::SETUP_KEY]['table_name'];
        $username_col = $config[self::SETUP_KEY][self::IDENTITY_FIELD_KEY];
        $password_col = $config[self::SETUP_KEY][self::PASSWORD_FIELD_KEY];

        $query = "
            SELECT {$config[self::SETUP_KEY][self::IDENTITY_ID_FIELD_KEY]} FROM {$table}
            WHERE {$username_col} = ? AND {$password_col} = MD5(?);";
        $rowset = $db->prepareExecuteAndFetch($query, array($username, $password));

        if (count($rowset) == 1)
        {
            self::setAuthenticated(true, $rowset[0][$config[self::SETUP_KEY]['identity_id_field']]);
        }
    }

    public static function clearAuth()
    {
        self::setAuthenticated(false);
    }
}
