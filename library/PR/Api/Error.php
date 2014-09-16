<?php
class PR_Api_Error
{
	static private $instance = NULL;
	private $errors = array();
	private $index = array();
	private $standardErrors = array(1 => 'External SOAP error',
	                                2 => 'Required fields are not set',
		                            3 => 'Authenticate is failed',
		                            4 => 'Not HTTPS protocol used',
		                            5 => 'Not enough permissions',
		                            6 => 'Validating error',
		                            7 => 'SQL error');

	private function __construct(){}

	private function __clone(){}

	/**
	 * Singleton
	 * @return Core_Api_Error
	 */
	public static function getInstance()
	{
		if (self::$instance == NULL)
			self::$instance = new PR_Api_Error();

		return self::$instance;
	}

    /**
     * hasErrors
     *
     * @access public
     * @return boolean
     */
    public function hasErrors()
    {
        return (bool) sizeof($this->errors);
    }

	private function makeStandardErrors()
	{
	}

	/**
	 * Enter description here...
	 *
	 * @param int $errorId
	 * @param string $description
	 */
	public function addError($errorId, $description= null)
	{
		//$this->makeStandardErrors();
		$error['description'] = $description;
		$error['error_id'] = $errorId;
		$error['id'] = $this->standardErrors[$errorId];
		$this->errors[] = $error;
	}

	/**
	 * Enter description here...
	 *
	 * @param int $errorId
	 * @param array $errors
	 */
	public function addErrors($errorId, $errors)
	{
		foreach ($errors as $val) {
			$error['description'] = $val;
    		$error['error_id'] = $errorId;
    		$error['id'] = $this->standardErrors[$errorId];
    		$this->errors[] = $error;
		}
	}

	public function getErrorArray() {
		return $this->errors;
	}
    
    


	/**
	 * Return text description of errors
	 * @return string
	 */
	public function toString()
	{
		$result = '';
		foreach ($this->errors as $error) {
			$result .= '[' . $error['id'] . '] ' . $error['description'] . "\n";
		}
		return $result;
	}

    public function getFirstError()
    {
        $result = '';
        if(!empty($this->errors))
        {
            $errs = $this->errors; 
            return $errs[0]['description'];
        }
    }
    
	public function __toString()
	{
		return $this->toString();
	}
	
	
	public function resetErrors()
	{
	    $this->errors = array();
	}
}
