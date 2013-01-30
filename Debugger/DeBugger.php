<?php
namespace DeBugger;
use DeBugger\Settings;
use DeBugger\Log;
use DeBugger\Display;
class DeBugger {
	/**
	 * If the Debugger is currently running
	 * stored as a variable so it can be paused
	 * forcing the handlers to exit before doing anything
	 * @var bool
	 */
	private static $Running;
	
	/**
	 * stored if the handlers are currently registered
	 * @var bool
	 */
	private static $Init;
	
	/**
	 * array of settings
	 * @var Settings
	 */
	private static $Settings = array();
	
	/**
	 * the title for the log message
	 * @var string
	 */
	private static $Title;
	
	/**
	 * the log message
	 * @var string
	 */
	private static $Log;
	
	/**
	 * the html for the list of files on the display page
	 * @var array
	 */
	private static $File = array();
	
	/**
	 * the html for the code samples for the display
	 * @var array
	 */
	private static $Code = array();
	
	/**
	 * root path. It will be striped from the file names in the log and on the display page.
	 * @var string
	 */
	public static $Root;
	
	/**
	 * add options on how to handler errors and exceptions
	 * @param \DeBugger\Settings $settings
	 */
	public static function SetSettings(Settings $settings){
		self::$Settings[] = $settings;
	}
	
	/**
	 * start the debugger.
	 * At least one set of setting must be set
	 */
	public static function Start(){
		if(!self::$Settings)
			trigger_error(__METHOD__ . " A settings has not been set", E_USER_ERROR);
		if(!self::$Init)
			self::Init();
		self::$Running = TRUE;
	}
	
	/**
	 * pause the handlers
	 */
	public static function Pause(){
		self::$Running = FALSE;
	}
	
	/**
	 * stop and unset the handlers
	 */
	public static function Stop(){
		self::$Running = FALSE;
		self::$Init = FALSE;
		set_error_handler(NULL);
		set_exception_handler(NULL);
	}
	
	/**
	 * initilize the handlers
	 */
	private static function Init(){
		self::$Init = TRUE;
		//error handler
		set_error_handler(function($errno, $errstr, $errfile, $errline){
			self::ErrorHandler($errno, $errstr, $errfile, $errline);
		});
		register_shutdown_function(function(){
			self::Shutdown();
		});
		set_exception_handler(function(\Exception $exception){
			self::ExceptionHandler($exception);		
		});
	}
	
	/**
	 * shitdown handler
	 * @return void
	 */
	private static function Shutdown(){
		if(!self::$Running)
			return;
		
		//check if there is an error that couldnt be handled by an error handler
		$error = error_get_last();
		if(!$error)
			return;
		if(!$errno & (E_ERROR | E_PARSE | E_COMPILE_ERROR))
			return;
		
		//check if it matches any settings
		$mask = self::GetLogMask(Settings::TYPE_EXCEPTION, $error["type"]);
		if(!$mask)
			return;
		
		$file = file($error["file"]);
		self::$Title = self::GetErrorName($error["type"]) . ": {$error['message']}";
		$error["function"] = trim($file[$error["line"] - 1]);
		$error["type"] = '';
		self::BacktraceDefaults($error);
		
		self::Parse(array($error), $mask);		
		self::Log(Settings::TYPE_ERROR, $error["type"]);
	}
	
	/**
	 * the error handler
	 * @param int $errno error number
	 * @param string $errstr error message
	 * @param string $errfile the error file
	 * @param int $errline line of the error
	 * @return void
	 */
	private static function ErrorHandler($errno, $errstr, $errfile, $errline){
		if(!self::$Running)
			return;
		
		$mask = self::GetLogMask(Settings::TYPE_ERROR, $errno);
		if(!$mask)
			return;
		
		self::$Title = Settings::TYPE_ERROR . ' ' . self::GetErrorName($errno) . ": $errstr";
		
		$backtrace = debug_backtrace();
		array_shift($backtrace);//remove the stack about this handler
		array_shift($backtrace);//remove the closure that triggers this handler

// code from previous version keeping it for reference
// I might display the line of the file for the file list rather than the class function type args format
//		fix the first in stack. it has the right file and line but the function and args are for the handler
//		so instead we will put the line from the file in it
//		$lines = file($errfile);		
//		$backtrace[0]['file'] = $errfile;
//		$backtrace[0]['line'] = $errline;
//		$backtrace[0]['class'] = '';
//		$backtrace[0]['type'] = '';
//		$backtrace[0]['function'] = trim($lines[$errline - 1]);
//		$backtrace[0]['args'] = [];
		
		self::Parse($backtrace, $mask);
		self::Log(Settings::TYPE_ERROR, $errno);
	}
	
	/**
	 * exception handler
	 * @param \Exception $exception uncoaught exception
	 * @return type
	 */
	private static function ExceptionHandler(\Exception $exception){
		if(!self::$Running)
			return;
		
		$mask = self::GetLogMask(Settings::TYPE_EXCEPTION, $exception->getCode());
		if(!$mask)
			return;
		
		self::$Title = Settings::TYPE_EXCEPTION . ' ' . $exception->getCode() . ': ' . $exception->getMessage();
		
		$backtrace = $exception->getTrace();
		array_unshift($backtrace, array(
			'file' => $exception->getFile(), 
			'line' => $exception->getLine(),
			'function' => get_class($exception),
			'args' => array($exception->getMessage(), $exception->getCode(), ($exception->getPrevious() ?: 'NULL'))));
		
		self::Parse($backtrace, $mask);
		self::Log(Settings::TYPE_EXCEPTION, $exception->getCode());
	}
	
	/**
	 * parse the backtrace and set up the log data
	 * @param array $backtrace
	 * @param int $mask
	 */
	private static function Parse(&$backtrace, $mask){
		$date = date(\DATE_W3C);
		$root = str_replace('/', '\\', (self::$Root ?: $_SERVER['DOCUMENT_ROOT'] . '/'));
		
		//to increase performance we set variables for if we need text logs and if we need to do a display page
		$LogText = ($mask & (Settings::LogPHP | Settings::LogFile | Settings::LogEmail));
		$logDisplay = ($mask & Settings::LogDisplay);
		
		foreach($backtrace as $k => $v){
			self::BacktraceDefaults($backtrace[$k]);
			
			//further format data and set as variables so we dont modify the backtrace any more
			$file = str_replace($root, '', $backtrace[$k]['file']);
			$function = ($backtrace[$k]['function'] ?: '[CLOSURE]');
			$line = ($backtrace[$k]['line'] ?: 'INTERNAL ');
			$args = '(' . self::FormatArgs($backtrace[$k]['args']) . ')';
			
			//create textual logs
			if($LogText){
				$log[] = "\t"
					. $file . ' '
					. $line . ': '
					. $function . ' '
					. $args;
			}
			
			//display
			if($logDisplay){
				self::$File[] = Display::PrepFile($file, $backtrace[$k]['line'], $backtrace[$k]['class'], $backtrace[$k]['type'], $function, $args);
				self::$Code[] = Display::PrepCode($backtrace[$k]['file'], $backtrace[$k]['line']);
			}
			
		}
		
		//finish the text log
		//when logs are logged a newline is added at the end automatically
		if($LogText)
			self::$Log = self::$Title . \PHP_EOL
				. "[$date] " . $_SERVER['REQUEST_URI'] . \PHP_EOL
				. implode (\PHP_EOL, $log);
	}
	
	/**
	 * go through the stack and log the data according to the stacks
	 * @param string $type
	 * @param int $lv
	 */
	private static function Log($type, $lv){
		//we only want to display once.
		//There is no reason to remake the display multiple times.
		//once something has been displayed the value changes to false
		$display = true;
		
		//loop through the settings and log accordingly
		foreach(self::$Settings as $v){
			$mask = $v->Get($type, $lv);
			
			if($mask & Settings::LogPHP)
				Log::LogToPHP(self::$Log);
			if($mask & Settings::LogFile)
				Log::LogToFile(self::$Log, $v->GetFile());
			if($mask & Settings::LogEmail)
				Log::LogToEmail(self::$Log, $v->GetEmail(), $v->GetHeaders());
			if($mask & Settings::LogDisplay && $display){
				Display::Build(self::$Title, implode('', self::$File), implode('', self::$Code));
				$display = false;
			}
		}
	}
	
	/**
	 * gets the combined log Mask to determine what is being loged
	 * @param string $type
	 * @param int $lv
	 * @return int bitmask of the log options
	 */
	private static function GetLogMask($type, $lv){
		$settings = 0;
		foreach(self::$Settings as $v)
			$settings = $settings | $v->Get($type, $lv);
		return $settings;
	}
	
	/**
	 * set default values to the backtrace if they are not set to avoid strict errors.
	 * @param array $trace
	 */
	private static function BacktraceDefaults(&$trace){
		//set defaults to avoid strict error when trying to access the keys
		$trace['file'] = (isset($trace['file']) ? $trace['file'] : '[INTERNAL PHP]');
		$trace['line'] = (isset($trace['line']) ? $trace['line'] : NULL);
		$trace['class'] = (isset($trace['class']) ? $trace['class'] : NULL);
		$trace['type'] = (isset($trace['type']) ? $trace['type'] : NULL);
		$trace['function'] = (isset($trace['function']) ? $trace['function'] : NULL);
		$trace['args'] = (isset($trace['args']) ? $trace['args'] : array());
	}
	
	/**
	 * formats argument list by wrapping strings in quotes
	 * not 100% accurate but better than nothing
	 * @param array $args
	 * @return string
	 */
	private static function FormatArgs(array $args){
		if(!count($args)) return '';
		$formated = array();
		foreach($args as $v){
			if(is_callable($v))
				$formated[] = '{closure}';
			elseif(is_null($v) || is_bool($v) || is_numeric($v) || is_object($v) || is_resource($v) || defined($v))
				$formated[] = $v;
			elseif(is_string($v))
				$formated[] = "\"$v\"";
			else
				$formated[] = $v;
		}
		return implode(', ', $formated);
	}
	
	/**
	 * takes the error number and returns the error name
	 * @param int $errno
	 * @return string
	 */
	public static function GetErrorName($errno){
		$return = '';
		if($errno & E_ERROR) // 1 //
			$return .= '& E_ERROR ';
		if($errno & E_WARNING) // 2 //
			$return .= '& E_WARNING ';
		if($errno & E_PARSE) // 4 //
			$return .= '& E_PARSE ';
		if($errno & E_NOTICE) // 8 //
			$return .= '& E_NOTICE ';
		if($errno & E_CORE_ERROR) // 16 //
			$return .= '& E_CORE_ERROR ';
		if($errno & E_CORE_WARNING) // 32 //
			$return .= '& E_CORE_WARNING ';
		if($errno & E_CORE_ERROR) // 64 //
			$return .= '& E_COMPILE_ERROR ';
		if($errno & E_CORE_WARNING) // 128 //
			$return .= '& E_COMPILE_WARNING ';
		if($errno & E_USER_ERROR) // 256 //
			$return .= '& E_USER_ERROR ';
		if($errno & E_USER_WARNING) // 512 //
			$return .= '& E_USER_WARNING ';
		if($errno & E_USER_NOTICE) // 1024 //
			$return .= '& E_USER_NOTICE ';
		if($errno & E_STRICT) // 2048 //
			$return .= '& E_STRICT ';
		if($errno & E_RECOVERABLE_ERROR) // 4096 //
			$return .= '& E_RECOVERABLE_ERROR ';
		if($errno & E_DEPRECATED) // 8192 //
			$return .= '& E_DEPRECATED ';
		if($errno & E_USER_DEPRECATED) // 16384 //
			$return .= '& E_USER_DEPRECATED ';
		if(!$errno)
			$return .= 'UNKNOWN';
		return trim(substr($return, 2));
	}
}
?>
