<?php

class Application_Controller_Action extends Zend_Controller_Action {

    var $_client = null;
    var $_request = null;
    var $_pagenumleft = 1;

    public function init() {
        /* Initialize action controller here */
        // for URL
        $uri = new Zend_View_Helper_BaseUrl();
        define('URL_BASE', $uri->getBaseUrl() . "/");
        define('URL_MEDIA_COMPANY_PROFILE',  URL_BASE.'library/media/companyprofiles/');
        define('URL_MEDIA_PORTFOLIO',  URL_BASE.'library/media/portfolio/');
         
        define('URL_THEMES', URL_BASE. "library/themes/standard/");
        //Mobile
        define('URL_THEMES_MOBILE', URL_BASE. "library/themes/mobile/");
        //End Mobile
        
        define('URL_MEDIA', URL_BASE. "library/media/");
        define('URL_MEDIA_PROFILE', URL_MEDIA. "profiles/");
        define('URL_MEDIA_TEMP', URL_MEDIA. "temp/");
        define('URL_MEDIA_SCHOOLLOGO', URL_MEDIA. "schoollogos/");
        //define('URL_MEDIA_PROFILE_NOAVATAR', "DefaultPerson.jpg");
        define('URL_MEDIA_PROFILE_NOAVATAR', "none.png");
        //define('LAYOUT', "");
        // for DIR
        define('DIR_BASE',  realpath(APPLICATION_PATH . '/../'));
        define('DIR_MEDIA_COMPANY_PROFILE',  DIR_BASE.'/library/media/companyprofiles/');
        define('DIR_MEDIA_PORTFOLIO',  DIR_BASE.'/library/media/portfolio/');
        
        define('DIR_MEDIA',  DIR_BASE.'/library/media/');
        define('DIR_MEDIA_TEMP',  DIR_MEDIA.'temp/');
        
        define('LIMIT_PAGE_LEFT', 10);
        define('LIMIT_PAGE_NUMBER_LEFT', 3);
        
        define('USER_TYPE_COMPANY', 1);
        define('USER_TYPE_CANDIDATE', 2);
        
      //  $this->_client = PR_Session::getSession(PR_Session::SESSION_CLIENT);pii
        $this->_client = PR_Session::getSession(PR_Session::SESSION_USER);
        $this->view->loginclient = $this->_client;
        //$this->layout()-

        $this->_layout = $this->_helper->layout->getLayoutInstance();
        $this->_layout->loginclient = $this->_client;
        $this->_layout->logout = $this->_helper->url('do-logout','user');
        $this->_layout->_helper = $this->_helper;

        //for querystring
        $this->_request = $this->getRequest();
        $ajax = $this->_request->getParam('ajax', 0);
        $this->view->ajax = $ajax;
        if ($ajax == 1) {
            $this->_helper->layout->disableLayout();
        }
        $pageajax = $this->_request->getParam('pageajax', "");
        $this->view->pageajax = $pageajax;
                
        
    }

}

