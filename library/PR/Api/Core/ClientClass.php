<?php
class PR_Api_Core_ClientClass
{
    private $avaiUpdateFields = array('usertype','firstname','middlename','lastname','dob','CompanyID','CandidateProfileID','loginname','password','emailaddress','URL','PhoneNumber','Address1','Address2','City','State','PostalCode','Country','HeardFrom','Role','active');
    private $avaiUpdateFields1 = array('Companyname','Industry','Address','Description','images','PhoneNumber','country','emailinfo','Zipcode','city','state');       
    
    public function  __construct() {
        $errMsg="";
    }    
    public function updateClientProfile($clientID,$clientData)
    {
        if(count($clientData)==0){
            return;
        }
        
        $updateFields = array();
        foreach($clientData as $key=>$value){
            if (in_array($key,$this->avaiUpdateFields)) {
                $updateFields[$key] = $value;
            }            
        }

        $criteria = "UserID = '$clientID'";
       $user= PR_Database::update('user',$updateFields,$criteria);
       //echo ("user:".$user);
               
    }
    
     public function getListUsersCompany($companyid)
    {             
        $db = PR_Database::getInstance();
        $select = $db->select();
        
        $select->from(array('cm'=>'company'),array('CompanyID','Companyname'));
        $select->join(array('us'=>'user'),
                    'us.CompanyID = cm.CompanyID',
                    array("*")
        );
        $select->where("us.CompanyID = '".$companyid."' ");
       
       // $select->from("user", array("*"));  
      //  $select->where("usertype = 1");  
         $record = PR_Database::fetchAll($select);
        if(empty($record)) return NULL;
        else return $record;                 
         
    }
    
     public function getListCompany()
    {             
        $db = PR_Database::getInstance();
        $select = $db->select();
        //$fieldList = array("Companyname","Industry","Address","Decreption");   
       
        $select->from("company", array("*"));             
      
        $companys = PR_Database::fetchAll($select);
        
        return $companys;
    }
     public function getDefaultCompany()
    {             
       $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from("company", array("*"));   
        $select->where("IsDefaultCompany = 1");
        $record = PR_Database::fetchAll($select);
        if(empty($record)) return NULL;
        else return $record[0];        
    }
    
     public function getCompany($companyid)
    {             
       $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from("company", array("*"));
        $select->where("CompanyID = '$companyid'");
        $record = PR_Database::fetchAll($select);
        if(empty($record)) return NULL;
        else return $record[0];        
    }
      public function getCompanyid($userid)
    {             
       $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from("user", array("*"));
        $select->where("UserID = '$userid'");
        $record = PR_Database::fetchAll($select);
        if(empty($record)) return NULL;
        else return $record[0];        
    }
    public function updatecompanyProfile($companyid,$Data)
    {
        if(count($companyid)==0){
            return;
        }
        
        $updateFields = array();
        foreach($Data as $key=>$value){
            if (in_array($key,$this->avaiUpdateFields1)) {
                $updateFields[$key] = $value;
            }            
        }

        $criteria = "CompanyID = '$companyid'";
        //echo ("criteria:".$criteria);print_r($updateFields);die();
        $result= PR_Database::update('company',$updateFields,$criteria);
        return $result;
       //echo ("user:".$user);

    }
     public function updateUserProfile($userid,$Data)
        {
            if(count($userid)==0){
                return;
            }
            //echo ("Data:");print_r($Data);die();
            $updateFields = array();
            foreach($Data as $key=>$value){
                if (in_array($key,$this->avaiUpdateFields)) {
                    $updateFields[$key] = $value;
                }            
            }

            $criteria = "UserID = '$userid'";
           // echo ("criteria:".$criteria);print_r($updateFields);die();
           $result= PR_Database::update('user',$updateFields,$criteria);
           return $result;
           //echo ("user:".$user);
                   
        }
        public function deleteUser($userid)
        {
             $db = PR_Database::getInstance();
             $criteria = "UserID = '$userid'";
             $result = $db->delete('user', $criteria);
                    
        }
         public function AddUser($data)
            {
                    
                    if(empty($data) || !is_array($data)) return NULL;

                    $db = PR_Database::getInstance();
                    $Fields = array();   
                   
                   foreach($data as $key=>$value){
                    if (in_array($key,$this->avaiUpdateFields)) {
                        $Fields[$key] = $value;
                    }            
                   }              
                  
                    //$api=new PR_Api_Core_ClientClass();
                    $defaultCompany=$this->getDefaultCompany();
                    $defaultCompanyID = $defaultCompany['CompanyID'];
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
                            $updateFields=array(
                            'UserID'=>$User_ID,
                            'usertype'=>'1',
                            'firstname'=>$Fields['firstname'],
                            'lastname'=>$Fields['lastname'],
                            "emailaddress" => $Fields['emailaddress'],
                            "Address1" => $Fields['Address1'],
                            "password" => $Fields['password'],
                            "CompanyID"=>$defaultCompanyID,
                            "PhoneNumber"=>$Fields['PhoneNumber'],
                            "URL"=>$Fields['URL'],
                            "City"=>$Fields['City'],
                            "Country"=>$Fields['Country'],
                            "PostalCode"=>$Fields['PostalCode'],
                            "Role"=>$Fields['Role'],
                            "activate"=>$Fields['activate']);
                            
                                 $result = PR_Database::insert("user", $updateFields);
                        
                }
            }
             public function AddCompany($data)
            {
        
                    if(empty($data) || !is_array($data)) return NULL;

                    $db = PR_Database::getInstance();
                    $Fields = array();   
                   
                   foreach($data as $key=>$value){
                    if (in_array($key,$this->avaiUpdateFields1)) {
                        $Fields[$key] = $value;
                    }            
                   }              
                  
                    
                    $maxIdSql = "SELECT MAX(CompanyID) AS CompanyID  FROM company";
                    $result = $db->fetchAll($maxIdSql);
                    $CompanyID=$result[0]['CompanyID']+1;
                    $Companyname=$Fields['Companyname'];
                    $select = $db->select()->from(PR_Database::TABLE_COMPANY, 
                                array('Companyname'));
                    $select->where("Companyname = '$Companyname'");
                    $res = $db->fetchAll($select);
                
                if(!empty($res) && count($res) > 0 ) {
                    
                    return array("error" => "Company name exists","CompanyID"=>"");
                    echo("Companyname exists");
                }else{
                    $updateFields=array(
                            'CompanyID'=>$CompanyID,
                            'Companyname'=>$Fields['Companyname']
                         
                            );
                            
                    $result = PR_Database::insert("company", $updateFields);
                    
                    // return $CompanyID;   
                    return array("error"=>"","CompanyID" => "$CompanyID");
                } 
                   
                    
                
            }
            public function deleteCompany($companyid)
			{
				 $db = PR_Database::getInstance();
				 $criteria = "CompanyID = '$companyid'";
				 $result = $db->delete('company', $criteria);
                    
			}
			public function getListCountry()
			{
				 $db = PR_Database::getInstance();
				 $select = $db->select();
				 $select->from("country",array("*"));
				 $countrys = PR_Database::fetchAll($select);
				 return $countrys;	
			}
			public function getCompany_Industry($Industry)
			{             
			   $db = PR_Database::getInstance();
				$select = $db->select();
				$select->from("company", array("*"));   
				$select->where("Industry = '$Industry'");
				$record = PR_Database::fetchAll($select);
				if(count($record)<=1) return NULL;
				else return $record;        
			}
		   public function uploadVideo($CompanyID,$video)
			{
				
			   $result= PR_Database::update('company',array('video'=>$video),
						"CompanyID = '$CompanyID'"
				);

				return $result;
			}
					 

        
}  

