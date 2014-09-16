<?php

class Zend_Controller_Action_Helper_Section extends Zend_Controller_Action_Helper_Abstract {

    function getSections($userid, $type = 1) {
        $api = new CS_Api_Core_StudentClass();
        return $api->getSectionList($userid, $type);
    }

    

    function getSection($userid) {
        $CS_Api_Core_SectionsClass = new CS_Api_Core_SectionsClass();
        return $CS_Api_Core_SectionsClass->GetListByTeacherId($userid);
    }

    

}

?>
