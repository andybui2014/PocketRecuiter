<?php

class PR_Api_Sections extends Zend_Db_Table_Abstract
{
    protected $_name = 'sections';

    private $section_id = '';
    private $fk_course_id = '';
    private $section_name = '';
    private $fk_teacher_id = '';
    private $section_credits = '';
    private $section_lcms_id = '';
    private $section_weeks = '';
    private $section_comments = '';
    private $section_status = '';
    private $students_num = '';
    private $fk_school_id = '';
    
    
    protected $authenticate = false;
    public $errMsg = "";
    
    public function  __construct($id = null) {
        parent::__construct();
        $this->section_id = $id;
        $errMsg="";
    }
    public function getById($id)
     {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from("sections");
        $select->where("section_id= '$id'");
        $records = PR_Database::fetchAll($select);
        if(empty($records)) return NULL;
        else return $records[0];        
     } 
    /**
     * @param array $authData login/password or just login
     * @return PR_Api_User $this
     */
    
    
}
