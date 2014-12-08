<?php

class PR_Api_Core_UserClass {

    public function __construct()
    {
        
    }

    //==================================================================    
    public function Update($userId, $updateFields)
    {
        $criteria = "user_id = '$userId'";
        $result = PR_Database::update("users", $updateFields, $criteria
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
            $errors = PR_Api_Error::getInstance();
            $errors->addError(6, 'User Name is empty');
            return false;
        } else
        {
            $userObj = $this->GetUserByUserName($userName);
            if (!empty($userObj))
            {
                $errors = PR_Api_Error::getInstance();
                $errors->addError(6, 'User Name already existed.');
                return false;
            }
        }
        //--- insert
        $result = PR_Database::insert("users", $updateFields);
        return $result;
    }

    //==================================================================   
    public function Delete($userId)
    {
        $db = PR_Database::getInstance();
        $db->delete('users', "user_id='$userId'");
    }

    //==================================================================   
    public function GetUserByUserName($userName)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('users');
        $select->where("username = '$userName'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }
	//===============================================
	public function checkUser($emailaddress){
		$db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('user');
        $select->where("emailaddress = '$emailaddress'");
        $records = PR_Database::fetchAll($select);
		if(!empty($records)){
		return $records[0];
		}else{
		return array("Error"=>"This email is not found.");
		}
	}



}
