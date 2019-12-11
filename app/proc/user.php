<?php

require_once 'proc/lib/MySQL_Socket.php';

class User extends MySQL_Socket {

    //  Query string variables.
    static private $GetUsr_Q;
    static private $SetPW_Q;
    static private $SetNick_Q;

    //  User data.
    static private $user;
    static private $JWT;

    //  Keep a log for debug.
    static private $log;

    //  Construct instance.
    function __construct($token) {
        parent::__construct();
        //  Create log instance.
        self::$log = New Log('USER_Debug');
        //  Set query strings.
        self::$GetUsr_Q = 'SELECT * FROM USERS WHERE USER = ? LIMIT 1';
        self::$SetPW_Q = 'UPDATE USERS SET HASH = ? WHERE ID = ?';
        self::$SetNick_Q = 'UPDATE USERS SET NAME = ? WHERE ID = ?';
        //  Interperate passed token.
        if (is_string($token)) {
            self::$JWT = new JWT($token);
            if (self::$JWT->ToStr()) {
                self::$user = self::$JWT->ToObj();
            }
        } else if (is_object($token)) {
            self::GetUser($token);
        }
    }

    private function GetUser($token) {
        if (!property_exists($token, '') || !property_exists($token, '')) {
            return False;
        }

    }

}

?>
