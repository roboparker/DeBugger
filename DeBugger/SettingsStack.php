<?php
namespace DeBugger;
class SettingsStack {
	const TYPE_ERROR = "ERROR";
	const TYPE_EXCEPTION = "EXCEPTION";
	const LogPHP = 1;
	const LogFile = 2;
	const LogEmail = 4;
	const LogDisplay = 8;
	
	//levels this stack is for
	protected $ErrorLV;
	protected $ExceptionLV;
	
	//locations for logs
	protected $Email;
	protected $Headers;
	protected $File;
	
	//log options as a bitmask
	protected $Log;
	
	/**
	 * create a new stack for the debugger
	 * @param int $log the log options you want to set. 
	 * @param string $file file name to save logs if LogFile is enabled
	 * @param string $email email to send logs if LogEmail is enabled
	 * @param string $headers email headers for the email logs
	 * @throws \InvalidArgumentException file not found or invalid email
	 */
	final public function __construct($log, $file = NULL, $email = NULL, $headers = NULL){
		//log options
		$this->Log = (int)$log;
		
		//file
		if($log & self::LogFile && $file)
			if(!file_exists($file))
				throw new \InvalidArgumentException(__METHOD__ . "@PARAM \$file[$file] file not found");
			else
				$this->File = $file;
		
		//email
		if($log & self::LogEmail && $email)
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				throw new \InvalidArgumentException(__METHOD__ . "@PARAM \$email[$email] email is not valid");
			else{
				$this->Email = $email;
				$this->Headers = (string)$headers;
			}
	}
	
	/**
	 * set what error levels this stack is for
	 * @param int $errlv the error lv. if null it will use the currently set error reporting level
	 * @return \DeBugger\SettingsStack
	 */
	final public function &SetErrorLV($errlv = NULL){
		$this->ErrorLV = ((int)$errlv ?: error_reporting());
		return $this;
	}
	
	/**
	 * set what exception codes this stack is for
	 * @param array|bool $code array of accepted codes or bool for all/none
	 * @return \DeBugger\SettingsStack
	 */
	final public function &SetExceptionLV($code = TRUE){
		$this->ExceptionLV = $code;
		return $this;
	}
	
	final public function &SetFile($file){
		//check if directory exist?
		$this->File = $file;
		return $this;
	}
	
	final public function &SetEmail($email, $headers = NULL){
		if(!filter_var($value, FILTER_VALIDATE_EMAIL))
			throw new \Exception(__METHOD__ . " \$email[$email] is an invalid email.");
		$this->Email = $email;
		$this->Headers = $headers;
		return $this;
	}
	
	/**
	 * get the log options if the type and code are valid for the instance
	 * @param type $type
	 * @param type $code
	 * @return int
	 */
	final public function Get($type, $code){
		if(($type == self::TYPE_ERROR && $this->ErrorLV & $code)//error
			|| ($type == self::TYPE_EXCEPTION && ($this->ExceptionLV === TRUE || $this->ExceptionLV & $code)))//exception
			return $this->Log;
		else return 0;
	}
	
	final public function GetFile(){
		return ($this->File ?: $this->DefaultFile());
	}
	
	final public function GetEmail(){
		return ($this->Email ?: $this->DefaultEmail());
	}
	
	final public function GetHeaders(){
		return ($this->Headers ?: $this->DefaultHeaders());
	}
	
	protected function DefaultFile(){
		return 'errors.log';
	}
	
	protected function DefaultEmail(){
		return 'webmaster@localhost.com';
	}
	
	protected function DefaultHeaders(){
		return '';
	}
}
?>