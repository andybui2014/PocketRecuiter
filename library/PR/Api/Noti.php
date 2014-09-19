<?php
class PR_Api_Noti extends Zend_Db_Table_Abstract
{
    protected $_name = 'notis';
    protected $_classname = 'PR_Api_Noti';
    protected $_pk = 'NotiID';
    
    public $NotiID;
    public $NotiType;
    public $cbUserTypeID;
    public $cbClientID;
    public $NotiDesc;
    public $cbDateTime;
    public $lmDateTime;
    public $lmbClientID;
    
    public $errMsg = "";

    public function  __construct($id = null) {
        parent::__construct();
        $this->NotiID = $id;
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
    
    public function save($data = null){
        $errors = PR_Api_Error::getInstance();
        if( empty($data) ) {
            return;
        }
        if( empty($this->NotiID) ){
            $objDateNow = new Zend_Date();
            $data['cbDateTime'] = $objDateNow->toString('yyyy-MM-dd hh:mm:ss');
            $data['lmDateTime'] = $objDateNow->toString('yyyy-MM-dd hh:mm:ss');
            if(empty($data['NotiType'])){
                $data['NotiType'] = 1; //public noti.
            }
            if(empty($data['NotiType'])){
                $data['cbUserTypeID'] = 1; //client create nodifications
            }
            $res = $this->insert($data);
            if( $res ) return new $this($res);
        } else {
            try {
                unset($data['NotiID']);
                $objDateNow = new Zend_Date();
                $data['lmDateTime'] = $objDateNow->toString('yyyy-MM-dd hh:mm:ss');
                $res = $this->update($data, 'NotiID = '.$this->NotiID);
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
