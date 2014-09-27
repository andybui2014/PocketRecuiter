<?php
class PR_Api_Core_CareerClass
{

    public function getListSkill()
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('sk'=>'skill'),array('*'));
        $records = PR_Database::fetchAll($select);
        return $records;
    }

    public function getListCareer()
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('cr'=>'careertype'),array('*'));
        $records = PR_Database::fetchAll($select);
        return $records;
    }
}
