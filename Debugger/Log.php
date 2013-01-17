<?php
namespace Debugger;
/**
 * Debugger @version 2.1 pre release
 * 
 * @author Robert M. Parker <webmaster@yamiko.org>
 * 
 * @license
 * <This is a class for debugging code. It has an error handler and a few other useful methods>
 * Copyright (C) <2012>  Robert M. Parker <webmaster@yamiko.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or    
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the    
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * uses:
 *		syntaxhighlighter 3.0.83 <http://alexgorbatchev.com/SyntaxHighlighter/> 
 *			I modified the php brush to add numbers, user functions and 
 *			new keywords, constants and php function to the definitions.
 *			The styles folder is removed since I have themes which can output the css styles
 *      jQuery				<http://jquery.com/>
 * 
 */
/**
 * @todo 
 *		exit on
 *		ignore certain files/folder in the stack
 *		allow settings for specific levels
 *		INI config file
 *		set use defined display?
 *		user defined email template?
 *		user defined log tempolates?
 */
class Log {
	/**
	 * root path. It will be striped from the file names in the log and on the display page.
	 * @var string
	 */
	public static $Root;
	
	const MODE_ERROR = 1;
	const MODE_EXCEPTION = 2;
	
	const ATTR_FILE = 2;
	const ATTR_EMAIL = 4;
	
	const LOG_PHP = 1;
	const LOG_FILE = 2;
	const LOG_EMAIL = 4;
	const LOG_DISPLAY = 8;
	
	private static $Mode;
	
	private static $ErrorReportLv;
	private static $ExceptionReportLv;
	
	private static $ErrorLog;
	private static $ExceptionLog;
	
	private static $ErrorFile;
	private static $ExceptionFile;
	
	private static $ErrorEmail;
	private static $ExceptionEmail;
	
	private static $ErrorRunning;
	private static $ExceptionRunning;

	/**
	 * 
	 * @param int $mode which mode your setting the attribute for
	 * @param int $attr which attribute to set
	 * @param mixed $value the value
	 */
	public static function SetAttribute($mode, $attr, $value){
		self::Reset();
		
		if($mode != self::MODE_ERROR && $mode != self::MODE_EXCEPTION)
				trigger_error (__METHOD__ . " Invalid \$mode[$mode] use one of " . __CLASS__ ."::MODE_* ", E_USER_ERROR);
		self::$Mode = $mode;
		
		switch ($attr){
			case self::ATTR_FILE:
				if(!file_exists($value))
					trigger_error (__METHOD__ . " \$value[$value] file does not exist.", E_USER_ERROR);
				if(self::$Mode == self::MODE_ERROR) self::$ErrorFile = $value;
				else self::$ExceptionFile = $value;
				break;
			case self::ATTR_EMAIL:
				if(!filter_var($value, FILTER_VALIDATE_EMAIL))
					trigger_error (__METHOD__ . " \$value[$value] is an invalid email.", E_USER_ERROR);
				if(self::$Mode == self::MODE_ERROR) self::$ErrorEmail = $value;
				else self::$ExceptionEmail = $value;
				break;
			default:
				trigger_error(__METHOD__ . " \$attr[$attr] is not a valid attribute option. Refer to " . __CLASS__ ."::ATTR_* ", E_USER_ERROR);
				break;
		}
	}
	
	public static function StartAll($log = 1){
		if(self::$ErrorRunning)
			self::StartErrorHandler($log);
		if(!self::$ExceptionRunning)
			self::StartExceptionHandler($log);
	}
	
	public static function StartErrorHandler($log = 1, $error = -1){
		if(self::$ErrorRunning)
			self::StopErrorHandler();
		
		self::$ErrorRunning = TRUE;
		self::$ErrorLog = $log;
		self::$ErrorReportLv = ($error === NULL ?: error_reporting());
		
		if(self::$ErrorLog)
			set_error_handler(function($errno, $errstr, $errfile, $errline){
				self::ErrorHandler($errno, $errstr, $errfile, $errline);
			});
			
		if(self::$ErrorReportLv & (E_ERROR | E_PARSE | E_COMPILE_ERROR))
			register_shutdown_function(function(){
				self::Shutdown();
			});
	}
	
	public static function StartExceptionHandler($log = 1, $lv = NULL){
		self::$ExceptionRunning = TRUE;
		self::$ExceptionReportLv = $lv;
		
		if(self::$ExceptionLog)
			set_exception_handler(function(\Exception $exception){
				self::ExceptionHandler($exception);
			});
	}
	
	public static function IsErrorHandlerRunning(){
		return (bool) self::$ErrorRunning;
	}
	
	public static function IsExceptionHandlerRunning(){
		return (bool) self::$ExceptionRunning;
	}
	
	public static function StopAll(){
		self::StopErrorHandler();
		self::StopExceptionHandler();
	}
	
	public static function StopErrorHandler(){
		self::$ErrorRunning = FALSE;
		set_error_handler(NULL);
	}
	
	public static function StopExceptionHandler(){
		self::$ExceptionRunning = FALSE;
		set_exception_handler(NULL);
	}
	
	public static function PauseAll(){
		self::PauseErrorHandler();
		self::PauseExceptionHandler();
	}
	
	public static function PauseErrorHandler(){
		self::$ErrorRunning = FALSE;
	}
	
	public static function PauseExceptionHandler(){
		self::$ExceptionRunning = FALSE;
	}
	
	public static function ResumeAll(){
		self::ResumeErrorHandler();
		self::ResumeExceptionHandler();
	}
	
	public static function ResumeErrorHandler(){
		self::$ErrorRunning = TRUE;
	}
	
	public static function ResumeExceptionHandler(){
		self::$ExceptionRunning = TRUE;
	}
	
	private static function ExceptionHandler(\Exception $exception){
		if(!self::$ExceptionRunning 
			|| ($exception->getCode() && self::$ExceptionReportLv) 
			&& $exception->getCode() & self::$ExceptionReportLv)
			return;
		self::$Mode = self::MODE_EXCEPTION;
		
		$backtrace = $exception->getTrace();
		array_unshift($backtrace, [
			'file' => $exception->getFile(), 
			'line' => $exception->getLine(),
			'function' => get_class($exception),
			'args' => [$exception->getMessage(), $exception->getCode(), ($exception->getPrevious() ?: 'NULL')]]);
		
		$title = $exception->getCode() . ': ' . $exception->getMessage();
		self::Log($title, $backtrace);
	}
	
	private static function ErrorHandler($errno, $errstr, $errfile, $errline){
		//surpress @ errors or return if non reported error lv
		if(!self::$ErrorRunning 
				|| error_reporting() == 0 
				|| !$errno & self::$ErrorReportLv)
			return;
		self::$Mode = self::MODE_ERROR;
		
		$backtrace = debug_backtrace();
		array_shift($backtrace);//remove the stack about this handler
		
		//fix the first in stack. it has the right file and line but the function and args are for the handler
		//so instead we will put the line from the file in it
		$lines = file($errfile);		
		$backtrace[0]['file'] = $errfile;
		$backtrace[0]['line'] = $errline;
		$backtrace[0]['class'] = '';
		$backtrace[0]['type'] = '';
		$backtrace[0]['function'] = trim($lines[$errline - 1]);
		$backtrace[0]['args'] = [];
		
		$title = self::GetErrorName($errno) . ": $errstr";
		
		self::Log($title, $backtrace);
	}
	
	private static function Shutdown(){
		$error = error_get_last();
		if(!$error)
			return;
		$trace = self::BacktraceDefault(array($error));
		$title = self::GetErrorName($trace[0]["type"]) . ": {$trace[0]['message']}";
		$trace[0]["type"] = '';
		ob_get_clean();
		Display::Build($title, $trace);
	}
	
	private static function Log($title, $backtrace){
		$date = date(\DATE_W3C);
		$backtrace = self::BacktraceDefault($backtrace);
		$backtraceString = self::FormatBacktraceString($backtrace);
		$type = (self::$Mode == self::MODE_ERROR ? 'ERROR' : 'EXCEPTION');
		$log = self::FormatLog($type, $title, $backtraceString);
		
		$settings = (self::$Mode == self::MODE_ERROR ? self::$ErrorLog : self::$ExceptionLog);

		if($settings & self::LOG_PHP)
			self::LogToPHP ($log);
		
		if($settings & self::LOG_FILE)
			self::LogToFile ($log);
		
		if($settings & self::LOG_EMAIL)
			self::LogToEmail ($log);
		
		if($settings & self::LOG_DISPLAY)
			echo Display::Build($title, $backtrace);
		
		exit();
	}
	
	public static function LogToPHP($log){
			error_log($log, 0);
	}
	
	public static function LogToFile($log, $file = NULL){
		$file = (self::$Mode == self::MODE_ERROR ? self::$ErrorFile : self::$ExceptionFile);
		error_log($log, 3, ($file ?: (date('Y-m-d') . '.log')));
	}
	
	public static function LogToEmail($log, $email = NULL){
		$email = (self::$Mode == self::MODE_ERROR ? self::$ErrorEmail : self::$ExceptionEmail);
		error_log($log, 1, $email);
	}
	
	/**
	 * sets default values to backtrace if they are not set to avoid triggering E_STRICT errors.
	 * @param array $backtrace the backtrace array
	 */
	private static function BacktraceDefault($backtrace){
		foreach($backtrace as $k => $v){
			$backtrace[$k]['file'] = (isset($backtrace[$k]['file']) ? $backtrace[$k]['file'] : '[INTERNAL PHP]');
			$backtrace[$k]['line'] = (isset($backtrace[$k]['line']) ? $backtrace[$k]['line'] : NULL);
			$backtrace[$k]['class'] = (isset($backtrace[$k]['class']) ? $backtrace[$k]['class'] : NULL);
			$backtrace[$k]['type'] = (isset($backtrace[$k]['type']) ? $backtrace[$k]['type'] : NULL);
			$backtrace[$k]['function'] = (isset($backtrace[$k]['function']) ? $backtrace[$k]['function'] : NULL);
			$backtrace[$k]['args'] = (isset($backtrace[$k]['args']) ? $backtrace[$k]['args'] : []);
		}
		return $backtrace;
	}
	
	/**
	 * formats backtrace as a string to use in log files
	 * @param array $backtrace
	 * @return string
	 */
	private static function FormatBacktraceString($backtrace){
		$root = str_replace('/', '\\', (self::$Root ?: $_SERVER['DOCUMENT_ROOT'] . '/'));
		foreach($backtrace as $v){
			$args = self::FormatArgs((isset($v['args']) ? $v['args'] : []));
			$log = PHP_EOL . "\t";
			$log .= (isset($v['file']) ? str_replace($root, '', $v['file']) : 'N/A') . ' ';
			$log .= (isset($v['line']) ? $v['line'] : 'N/A ') . ': ';
			$log .= (isset($v['function']) ? $v['function'] : 'INTERNAL ') . '(';
			$log .= "($args)";
		}
		return $log;
	}

	/**
	 * formats array values and will wrap in quotes if it is a string that is not not true, false or null.
	 * @param array $args array of args to format
	 * @return string
	 */
	public static function FormatArgs(array $args){
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
	
	private static function FormatLog($type, $title, $log){
		$date = date(\DATE_W3C);
		return \PHP_EOL 
			. "$type $title" . \PHP_EOL
			. "[$date] " . $_SERVER['REQUEST_URI']
			. $log;
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
