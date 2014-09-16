<?php


require_once 'Zend/Exception.php';

class CS_Exception extends Zend_Exception
{
    
    /**
     * Project critical error
     * @var unknown_type
     */
    const CRITICAL_ERROR = 1;
    
    /**
     * Cot critical error
     * @var unknown_type
     */
    const MINOR_ERROR = 2;
    
    /**
     * Developer level error. 
     * Incorrect using or initialization.
     * @var unknown_type
     */
    const USING_ERROR = 3;
    
    private $_errType;
    
    /**
     * Constructor
     * @param string $message
     * @param string $code
     * @param int $type
     */
    function __construct($message = 'Exception', $code = 0, $type = CS_Exception::MINOR_ERROR)
    {
        if (empty($code) ) {
            $code = crc32(get_class($this));
        }
        
        $this->_errType = $type;
        parent::__construct($message, $code);
        
        //self::makeExceptionLog($this);
    }
    
    
    /**
     * Get Error Type
     * @return number
     */
    public function getErrorType()
    {
        return $this->_errType;
    }
    
    
    /**
     * Check error is cruitical
     * @return boolean
     */
    public function isCriticalError()
    {
        return ($this->getErrorType() == self::CRITICAL_ERROR) ? true : false;
    }
            
}

