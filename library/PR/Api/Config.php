<?php
class PR_Api_Config extends Zend_Db_Table_Abstract
{
    protected $_name = 'config';
    
    private $id=''; 
    private $cfgkey=''; 
    private $cfgval='';  
    private $title='';  
    private $editable=''; 
    private $validationJs=''; 
    
    public function  __construct($id = null) {
        parent::__construct();
        $this->id = $id;
    }
    
    public static function getConfig($cfgkey)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(PR_Database::TABLE_config);
        $select->where("cfgkey = ?", $cfgkey);
        $records = PR_Database::fetchAll($select);
        if(!empty($records)) return $records[0];
        else  return null;
    }
    public static function getConfigVal($cfgkey)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(PR_Database::TABLE_config);
        $select->where("cfgkey = ?", $cfgkey);
        $records = PR_Database::fetchAll($select);
        if(!empty($records)) return $records[0]['cfgval'];
        else  return '';
    }
    
}  

