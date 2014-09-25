<?php

class PR_Api_User extends Zend_Db_Table_Abstract
{
    protected $_name = 'user';
    private $UserID;
    private $usertype;
    private $firstname;
    private $middlename;
    private $lastname;
    private $dob;
    private $CompanyID;
    private $CandidateProfileID;
    private $loginname;
    private $password;
    private $emailaddress;
    private $URL;
    private $PhoneNumber;
    private $Address1;
    private $Address2;
    private $City;
    private $State;
    private $PostalCode;
    private $Country;
    private $HeardFrom;
    
    
    protected $authenticate = false;
    public $errMsg = "";
    
    public function  __construct($id = null) {
        parent::__construct();
        $this->user_id = $id;
        $errMsg="";
    }
    /**
     * @param array $authData login/password or just login
     * @return PR_Api_User $this
     */
    public function getUserArray($authData) {

        $errors = PR_Api_Error::getInstance();
        

      //  $this->UserName = $authData['UserName'];
      //  $this->Password = $authData['Password'];
        
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('user',array('*'));
        $select->where("emailaddress = ?", $authData['emailaddress']);

        if (isset($authData['password'])) {
            $select->where("password = ?", $authData['password']);
        }
        
        //print_r($select->__toString());die();
        
        $user = PR_Database::fetchAll($select);
        if (!empty($user)) 
        {                    
            foreach ($user[0] as $key => $value) 
            {
                if (property_exists('PR_Api_Client', $key)) {
                    $this->{$key} = $value;
                }
            }        
            return $user[0];
        } else {
            $errors->addError(3, 'Login or password is not correct');
            $this->ClientID = NULL;
            $this->UserName      = NULL;
            return null;
        }
    }

	/**
	 * Check up user information
	 *
	 * @param array $authData
	 * @return bool
	 */
	public function loadAndCheckAuthentication($authData)
	{
		$errors = PR_Api_Error::getInstance();
        $this->authenticate = false;

		if (empty($authData['emailaddress']) || empty($authData['password'])) {
			$errors->addError(2, 'emailaddress or password is empty but required');	
			return false;
		}
                
        $user = $this->getUserArray($authData);

        if (!empty($user)) {
            $this->authenticate = true;
        }
        return $user;
	}

    /**
     * Load PR_Api_User By Username
     * @param string $username
     * @return boolean
     */
	public function lookupUsername( $username ) {
		if ( empty($username) ) return false;

        $fieldList = array("user_id","username","name","fname","mname","lname");
        $fieldList = array_combine($fieldList, $fieldList);

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(PR_Database::TABLE_users, $fieldList)
            ->where("username = ?",$username);
        $users = PR_Database::fetchAll($select);

		if ( !empty($user) ) {
			$this->authenticate = false;
			$this->user_id  = $user[0]['user_id'];
            $this->username = $user[0]['username'];
			$this->name = $user[0]['name'];
			$this->fname = $user[0]['fname'];
			$this->mname = $user[0]['mname'];
			$this->lname = $user[0]['lname'];
            return true;
		} else {
            if ( !$errors->hasErrors() ) $errors->addError(3, 'Cannot find user');
			$this->authenticate     = false;
			$this->user_id  = NULL;
           	$this->username       = NULL;
			$this->name       = NULL;
            return false;
		}
	}
	
	public function loginHistory($userName)
	{		
		$class = new Core_Api_Class;
		$class->loginHistory($userName, "User");
	}

    public function getPassword()
    {
        return $this->Password;
    }

	public function getLogin()
	{
	    return $this->UserName;
    }
	
    
    

     
}
