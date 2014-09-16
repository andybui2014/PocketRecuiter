<?php

class PR_Api_Core_SectionsClass 
{
    public function  __construct() 
    {
        
    }
    public function GetListByTeacherId($Id)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from("sections");
        $select->where("fk_teacher_id = '$Id'");  
        $select->where("section_status = 'Open'");  
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    
    public function GetSectionWeeks($sectionId)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('s' => 'sections'), array('s.section_weeks'));
        $select->where("section_id = '$sectionId'");
        $records = PR_Database::fetchAll($select);

        if (!empty($records))
            return $records[0]['section_weeks'];
        else
            return 0;
    }
    
    public function GetSectionNum($sectionId)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('s' => 'sections'), array('s.students_num'));
        $select->where("section_id = '$sectionId'");
        $records = PR_Database::fetchAll($select);

        if (!empty($records))
            return $records[0]['students_num'];
        else
            return 0;
    }

    public function UpdateSectionStudentNum($sectionId, $num)
    {
        $students_num = $this->GetSectionNum($sectionId);
        $students_num = $students_num + $num;
        $dbFields = array();
        $dbFields['students_num'] = $students_num;
        $criteria = "section_id = '$sectionId'";
        $result = PR_Database::update('sections', $dbFields, $criteria);
        return $result;
    }
    
    

}