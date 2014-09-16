<?php

class CS_Api_Core_UserClass {

    public function __construct()
    {
        
    }

    //==================================================================    
    public function Update($userId, $updateFields)
    {
        $criteria = "user_id = '$userId'";
        $result = CS_Database::update("users", $updateFields, $criteria
        );
        return $result;
    }

    //==================================================================    
    public function Add($updateFields)
    {
        $userName = $updateFields['username'];
        //--- validate
        if (empty($userName))
        {
            $errors = CS_Api_Error::getInstance();
            $errors->addError(6, 'User Name is empty');
            return false;
        } else
        {
            $userObj = $this->GetUserByUserName($userName);
            if (!empty($userObj))
            {
                $errors = CS_Api_Error::getInstance();
                $errors->addError(6, 'User Name already existed.');
                return false;
            }
        }
        //--- insert
        $result = CS_Database::insert("users", $updateFields);
        return $result;
    }

    //==================================================================   
    public function Delete($userId)
    {
        $db = CS_Database::getInstance();
        $db->delete('users', "user_id='$userId'");
    }

    //==================================================================   
    public function GetUserByUserName($userName)
    {
        $db = CS_Database::getInstance();
        $select = $db->select();
        $select->from('users');
        $select->where("username = '$userName'");
        $records = CS_Database::fetchAll($select);
        return $records;
    }

//==================================================================   
    public function GetTeacherEnrollmentsNum($userId)
    {
        $db = CS_Database::getInstance();
        $select = $db->select();
        $select->from(array('u' => 'users'), array('u.teacher_enrollments_num'));
        $select->where("user_id = '$userId'");
        $records = CS_Database::fetchAll($select);

        if (!empty($records))
            return $records[0]['teacher_enrollments_num'];
        else
            return 0;
    }

    public function UpdateTeacherEnrollmentsNum($userId, $num)
    {
        $students_num = $this->GetTeacherEnrollmentsNum($userId);
        $students_num = $students_num + $num;
        $dbFields = array();
        $dbFields['teacher_enrollments_num'] = $students_num;
        $criteria = "user_id = '$userId'";
        $result = CS_Database::update('users', $dbFields, $criteria);
        return $result;
    }

}
