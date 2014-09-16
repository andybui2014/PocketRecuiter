<?php
class PR_Session {
    const SESSION_CLIENT = "SESSION_CLIENT";    
    //static $session_user;
    
    function __construct() {
        //Zend_Session::start();
    }

    static function setSession($data, $key = "SESSION_CLIENT") {
        if(!empty($data))
        {
            $session = new Zend_Session_Namespace('MyAppName');
            $session->$key = $data;
        }             
    }
    static function getSession($key = "SESSION_CLIENT") {
        
        $session = new Zend_Session_Namespace('MyAppName');
        return $session->$key;
    }
    
    static function clearSession($key = "SESSION_CLIENT") {
        $session = new Zend_Session_Namespace('MyAppName');
        $session->$key=null;
    }
    
    static function clearSessions() {
        $session = new Zend_Session_Namespace('MyAppName');
        $session->SESSION_CLIENT=null;
    }
}  

