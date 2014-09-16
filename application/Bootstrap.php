<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Cloud
 * @subpackage examples
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @category   Zend
 * @package    Zend_Cloud
 * @subpackage examples
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initConfig() {
        /* for local */
        
        $host = "localhost";
        $username = "root";
        $password = "";   
        $dbname = "pocket_recruiter";
        
        /* for TEST Site */
        /*
        $host = "andybui.ipagemysql.com";
        $username = "adsis";
        $password = "ad5!5";        
        $dbname = "advantages_sis";
        */
        //--- db        
        $db = Zend_Db::factory('MYSQLI', array(
                    'host' => $host,
                    'username' => $username,
                    'password' => $password,
                    'dbname' => $dbname
                ));
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('DB', $db);
        Zend_Registry::set('DB_Info', array(
            'host' => $host,
            'username' => $username,
            'password' => $password,
            'dbname' => $dbname
        ));
        $sql = "SET NAMES utf8";
        $db->query($sql);

        if (!$db->isConnected()) {
            throw new PR_Exception('No Db connection', 1, PR_Exception::CRITICAL_ERROR);
        }
        //--- user_types
        $user_types = array(1=>"Super Admin",
                            2=>"School Admin",
                            3=>"Teacher",
                            4=>"Student");
        Zend_Registry::set('USER_TYPES', $user_types);        
        //--- return
        return new Zend_Config($this->getOptions());
    }

    public function _initSession()
    {      
        Zend_Session::start();
    }

    protected function _initResourceInjector() {
        Zend_Controller_Action_HelperBroker::addHelper(
                new PR_ResourceInjector()
        );
        // Action Helpers
        Zend_Controller_Action_HelperBroker::addPath(
                APPLICATION_PATH . '/controllers/helpers');
    }
    
    protected function setconstants($constants){
        foreach ($constants as $key=>$value){
            if(!defined($key)){
                define($key, $value);
            }
        }
    }


}
