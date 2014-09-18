<?php

class PR_Api_Client extends Zend_Db_Table_Abstract
{
    protected $_name = 'clients';
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
        $this->ClientID = $id;
        $errMsg="";
    }
    /**
     * @param array $authData login/password or just login
     * @return PR_Api_User $this
     */
    public function getClientArray($authData) {

        $errors = PR_Api_Error::getInstance();
        

        $this->UserName = $authData['UserName'];
        $this->Password = $authData['Password'];
        
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('clients',array('*'));
        $select->where("UserName = ?", $this->UserName);

        if (isset($authData['Password'])) {
            $select->where("Password = ?", $this->Password);
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

		if (empty($authData['UserName']) || empty($authData['Password'])) {
			$errors->addError(2, 'Username or password is empty but required');	
			return false;
		}
                
        $user = $this->getClientArray($authData);

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
	
    public function getPassword()
    {
        return $this->Password;
    }

	public function getLogin()
	{
	    return $this->UserName;
    }
	
    public function save($data = null){
        $errors = PR_Api_Error::getInstance();
        if( empty($data) ) {
            $info = $this->info();
            $data = array();
            foreach( $info['cols'] as $col ) $data[$col] = $this->$col;
        }

        if( empty($this->ClientID) ){
            $objDateNow = new Zend_Date();
            $data['RegDate'] = $objDateNow->toString('yyyy-MM-dd hh:mm:ss');
            
            $res = $this->insert($data);
            if( $res ) return new $this($res);
        } else {
		try {
            $res = $this->update($data, 'ClientID = '.$this->ClientID);
		} catch (Exception $e) {
		        $errors->addError('7', 'Incorrect SQL request');
		}
            if( $res ) {
                foreach( $data as $k=>$v ) $this->$k = $v;
                return $this;
            }
        }
        return null;
    }   
       
}
