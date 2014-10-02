<?php
class PR_Api_Career extends Zend_Db_Table_Abstract
{
    protected $_name = 'opportunity';
    protected $_classname = 'PR_Api_Career';
    protected $_pk = 'OpportunityID';
    
    public $OpportunityID;
    public $CompanyID;
    public $postedby;
    public $posteddate;
    public $title;
    public $careerdescription;
    public $status;
    public $industry;
    public $jobtype;
    public $duration;
    public $location;
    public $zipcode;
    public $minimuneducation;
    public $degreetitle;
    public $StaffFavorite;
    public $SkillID;
    
    public $errMsg = "";

    public function  __construct($id = null) {
        parent::__construct();
        $this->OpportunityID = $id;
        $errMsg="";
    }
    
    public function load()
    {
        $errors = PR_Api_Error::getInstance();

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from($this->_name,array('*'));
        $select->where($this->_pk." = ?", $this->{$this->_pk});
        //print_r($select->__toString());
        $records = PR_Database::fetchAll($select);        
        if (!empty($records) && count($records)>0) 
        {                    
            foreach ($records[0] as $key => $value) 
            {
                if (property_exists($this->_classname, $key)) {
                    $this->{$key} = $value;
                }
            }        
            return $records[0];
        } else {
            $errors->addError(3, 'Can not load object');
            $this->{$this->_pk} = NULL;
            return null;
        }
           
    }

    public function getInfo()
    {
        $errors = PR_Api_Error::getInstance();

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from($this->_name,array('*'));
        $select->where($this->_pk." = ?", $this->{$this->_pk});
        //print_r($select->__toString());
        $records = PR_Database::fetchAll($select);        
        if (!empty($records) && count($records)>0) 
        {                    
            return $records[0];
        } else {
            $errors->addError(3, 'Can not get object');
            return null;
        }
           
    }
    
    public function saveCareer($data = null){
        $errors = PR_Api_Error::getInstance();
        if( empty($data) ) {
            return;
        }
        if( empty($this->OpportunityID) ){
            $objDateNow = new Zend_Date();
            $data['posteddate'] = $objDateNow->toString('yyyy-MM-dd hh:mm:ss');
            $res = $this->insert($data);
            if( $res ) return new $this($res);
        } else {
            /*
            try {
                unset($data['NotificationID']);
                $objDateNow = new Zend_Date();
                $data['lmDateTime'] = $objDateNow->toString('yyyy-MM-dd hh:mm:ss');
                $res = $this->update($data, 'NotificationID = '.$this->NotificationID);
            } catch (Exception $e) {
                    $errors->addError('7', 'Incorrect SQL request');
            }
            if( $res ) {
                foreach( $data as $k=>$v ) $this->$k = $v;
                return $this;
            }
            */
        }
        return null;
    }   
    
}  
