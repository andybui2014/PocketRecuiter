<?php

class PR_Api_Core_Register
{
  
     public function getAboutUsList_forRegisterPage()
    {
        $sourceList = array('AnyoneHiring.com',
        'Beyond.com',
        'Craigslist.com',
        'Dice.com',
        'GetHired.com',
        'Indeed.com',
        'SimplyHired.com',
        'Search Engine',
        'Contacted by FieldSolutions',
        'Referral - Client',
        'Referral - Technician',
        'SkillNet',
        'LinkedIn',
        'Facebook',
        'Twitter',
        'Internet Forum',
        'College Job Boards',
        'Local Employment Agency',
        'Misc Job Search Site',
        'ISO',
        'Other'
);
        return $sourceList;
    }
    public function registerClient($data)
    {
        
        if(empty($data) || !is_array($data)) return NULL;

        $db = PR_Database::getInstance();
        $ClientID = false;
        
       $mapping = array(
            "firstname" => "firstname",
            "lastname" => "lastname",
            "emailaddress" => "emailaddress",
            "usertype" => "usertype",
            "password" => "password",
            "HeardFrom" => "HeardFrom",
            "CompanyID" =>"CompanyID"
//"loginname" => "loginname"
            
        );
        
        foreach ($data as $id => $value) {
            if (!array_key_exists($id, $mapping) || empty ($value)) continue;
            $data[$mapping[$id]] = $value;
        }
            $api=new PR_Api_Core_ClientClass();
            $defaultCompany=$api->getDefaultCompany();
            $defaultCompanyID = $defaultCompany['CompanyID'];
           // echo ("defaultCompanyID:".$defaultCompanyID);
        $primaryEmail=$data["emailaddress"];
            $maxIdSql = "SELECT MAX(UserID) AS UserID  FROM user";
            $result = $db->fetchAll($maxIdSql);
        $User_ID=$result[0]['UserID']+1;
             $select = $db->select()->from(PR_Database::TABLE_USER, 
                                array('emailaddress'));
                $select->where("emailaddress = '$primaryEmail'");
                $res = $db->fetchAll($select);
                
                if(!empty($res) && count($res) > 0 ) {
                    
                    return array("error" => "email exists");
                    echo("email exists");
                } 
               
                else
                {
                    if($data['usertype']==1)
                    {
                        $db->insert(PR_Database::TABLE_USER, array(
                        "UserID" => $User_ID,
                        "firstname" => $data['firstname'],
                        "lastname" => $data['lastname'],
                        "usertype" => $data['usertype'],
                        "emailaddress" => $data['emailaddress'],
                        "password" => $data['password'],
                        "HeardFrom" => $data['HeardFrom'],
                       // "loginname" => $data['loginname'],
                        //"CompanyID"=>$defaultCompanyID
                         "CompanyID"=>$data['CompanyID']
                        
            ));
                    }
                    
                    if($data['usertype']==2)
                    {
                        $db->insert(PR_Database::TABLE_USER, array(
                        "UserID" => $User_ID,
                        "firstname" => $data['firstname'],
                        "lastname" => $data['lastname'],
                        "usertype" => $data['usertype'],
                        "emailaddress" => $data['emailaddress'],
                        "password" => $data['password'],
                        "HeardFrom" => $data['HeardFrom']
                       // "loginname" => $data['loginname']
                       ));
                    }
                    
                }
            
      

            $db->commit();
    } 
}
