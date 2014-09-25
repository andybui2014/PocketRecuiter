<?php
class PR_Session {
   // const SESSION_CLIENT = "SESSION_CLIENT"; 
    const SESSION_USER = "SESSION_USER";    
    //static $session_user;
    
    function __construct() {
        //Zend_Session::start();
    }

   // static function setSession($data, $key = "SESSION_CLIENT") {
        static function setSession($data, $key = "SESSION_USER") {
        if(!empty($data))
        {
            $session = new Zend_Session_Namespace('MyAppName');
            $session->$key = $data;
        }             
    }
   // static function getSession($key = "SESSION_CLIENT") {
    static function getSession($key = "SESSION_USER") {
        
        $session = new Zend_Session_Namespace('MyAppName');
        return $session->$key;
    }
    
   // static function clearSession($key = "SESSION_CLIENT") {
    static function clearSession($key = "SESSION_USER") {
        $session = new Zend_Session_Namespace('MyAppName');
        $session->$key=null;
    }
    
    static function clearSessions() {
        $session = new Zend_Session_Namespace('MyAppName');
       // $session->SESSION_CLIENT=null;
        $session->SESSION_USER=null;
    }
}  

