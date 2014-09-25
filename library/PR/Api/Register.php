<?php

class PR_Api_Register extends Zend_Db_Table_Abstract
{
    protected $_name = 'user';
    private $ClientID;
    private $UserName;
    private $Password;
    private $CompanyName;
    private $Company_ID;
    private $ContactName;
    private $ContactPhone1;
    private $ContactPhone2;
    private $Email1;
    private $Email2;
    private $BusinessAddress;
    private $BusinessCity;
    private $BusinessState;
    private $BusinessZip;
    private $CompanyDescription;
    private $RegDate;
    private $AccountEnabled;
    private $Company_Logo;
    private $Country;
    private $CreatedBy;
    private $LastLoginDate;
    
    protected $authenticate = false;
    public $errMsg = "";
    
    public function  __construct($id = null) {
        parent::__construct();
      //  $this->user_id = $id;
      //  $errMsg="";
    }
    
    

   
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
            "loginname" => "loginname"
            
        );
        
        foreach ($data as $id => $value) {
            if (!array_key_exists($id, $mapping) || empty ($value)) continue;
            $data[$mapping[$id]] = $value;
        }
        
       $primaryEmail=$data["emailaddress"];
       $loginname=$data["loginname"];
       // {
            $maxIdSql = "SELECT MAX(UserID) AS UserID  FROM user";
            $result = $db->fetchAll($maxIdSql);
        $User_ID=$result[0]['UserID']+1;
             $select = $db->select()->from(PR_Database::TABLE_USER, 
                                array('emailaddress'));
                $select->where("emailaddress = '$primaryEmail'");
                $res = $db->fetchAll($select);
                $select1 = $db->select()->from(PR_Database::TABLE_USER, 
                                array('loginname'));
                $select1->where("loginname = '$loginname'");
                $res1 = $db->fetchAll($select1);
                if(!empty($res) && count($res) > 0 ) {
                    
                    return array("error" => "email exists");
                   // echo("email exists");
                } 
                else if(!empty($res1) && count($res1) > 0){
                    return array("error" => "User exists");
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
                        "loginname" => $data['loginname'],
                        "CompanyID"=>"1"
                        
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
                        "HeardFrom" => $data['HeardFrom'],
                        "loginname" => $data['loginname']
                        
                        
            ));
                    }
                    
                }
            
      

            $db->commit();
    } 
}
