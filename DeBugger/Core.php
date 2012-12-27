<?php
namespace DeBugger;
use DeBugger\Display;
/**
 * Debugger @version 2.0 pre release
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
class Core {
	
	/**
	 * file to save data. You must also set LogErrorsToFile and/or LogExceptionsToFile
	 * @var string
	 */
	public static $file;
	
	/**
	 * email to send data. You must also set LogErrorsToEmail and/or LogExceptionsToEmail
	 * @var string
	 */
	public static $Email;
	
	/**
	 * if errors should be logged by php error_log
	 * @var bool
	 */
	public static $LogErrors = FALSE;
	
	/**
	 * if errors should be displayed with the display class
	 * @var bool
	 */
	public static $LogErrorsToDisplay = TRUE;
	
	/**
	 *	if errors should be loged into a use defined file
	 * File must be set.
	 * @var bool
	 */
	public static $LogErrorsToFile = FALSE;
	
	/**
	 * if an email should be sent with error info.
	 * Email must also be set or an error will occur when trying to send
	 * @var type 
	 */
	public static $LogErrorsToEmail = FALSE;
	
	/**
	 * if uncaught exception should be logged by php error_log
	 * @var bool
	 */
	public static $LogExceptions = FALSE;
	
	/**
	 * if uncaught exception should be displayed with the display class
	 * @var bool
	 */
	public static $LogExceptionsToDisplay = TRUE;
	
	/**
	 * if uncaught exception should be loged into a use defined file
	 * file must be set.
	 * @var bool
	 */
	public static $LogExceptionsToFile = FALSE;
	
	/**
	 * if an email should be sent with uncaught exception info.
	 * Email must also be set or an error will occur when trying to send
	 * @var type 
	 */
	public static $LogExceptionsToEmail = FALSE;
	
	/**
	 * starts the error handler for the passed or current error reporting level and then starts the exception handler.
	 * @param int $level ther error reporting level you wigh to set. if null it will use the current error reporting level
	 */
	public static function StartAll($level = NULL){
		self::StartErrorHandler($level);
		self::StartExceptionHandler();
	}
	
	/**
	 * starts just the error handler
	 * @param int $level ther error reporting level you wigh to set. if null it will use the current error reporting level
	 */
	public static function StartErrorHandler($level = NULL){
		if($level === NULL)
			$level = error_reporting ();
		set_error_handler(
				function($errno, $errstr, $errfile, $errline){
					self::ErrorHandler($errno, $errstr, $errfile, $errline);
				}, $level);
	}
	
	/**
	 * starts just the exception handler
	 */
	public static function StartExceptionHandler(){
		set_exception_handler(
				function(\Exception $exception){
					self::ErrorHandler($exception);
				});
	}
	
	/**
	 * stops and unsets the error and exception handlers
	 */
	public static function StopAll(){
		self::StopErrorHandler();
		self::StopExceptionHandler();
	}
	
	/**
	 * stop just the error handler
	 */
	public static function StopErrorHandler(){
		set_error_handler(NULL);
	}
	
	/**
	 * stop just the exception handler
	 */
	public static function StopExceptionHandler(){
		set_exception_handler(NULL);
	}
	
	/**
	 * the error handler.
	 * @param int $errno the error number
	 * @param string $errstr the error message
	 * @param string $errfile the absolute file path
	 * @param int $errline line of the error
	 */
	protected static function ErrorHandler($errno, $errstr, $errfile, $errline){
		//surpress @ errors
		if(error_reporting() == 0)
			return;
		
		//backtrace
		$backtrace = debug_backtrace();
		array_shift($backtrace);//remove the stack about this handler
		array_shift($backtrace);//remove the wrapping callback
		$backtrace = self::BacktraceDefault($backtrace);
		
		//format data
		$date = date(\DATE_W3C);
		$errname = self::GetErrorName($errno);
		$title = "$errname: $errstr";
		$backtraceString = self::FormatBacktraceString($backtrace);
		
		//php log
		if(self::$LogErrors){
			$fileLog = self::FormatFileLog($date, 'rror', $title, $backtraceString);
			self::Log($fileLog);
		}
		
		//log to file
		if(self::$LogErrorsToFile){
			if(!isset($fileLog)) $fileLog = self::FormatFileLog($date, 'Error', $title, $backtraceString);
			self::LogFile($fileLog);
		}
		
		//log to email
		if(self::$LogErrorsToEmail){
			$emailLog = self::FormatEmailLog($date, 'ERROR', $title, $backtraceString);
			self::LogEmail($date, $emailLog);
		}
		
		$backtrace = self::BacktraceDefault($backtrace);
		//display page
		Display::Build($title, $backtrace);
		
		exit();
	}
	
	/**
	 * the exception handler.
	 * @param \Exception $exception
	 */
	protected static function ExceptionHandler(\Exception $exception){
		//backtrace
		$backtrace = $exception->getTrace();
		array_unshift($backtrace, [
			'file' => $exception->getFile(), 
			'line' => $exception->getLine(),
			'function' => get_class($exception),
			'args' => [$exception->getMessage(), $exception->getCode(), ($exception->getPrevious() ?: 'NULL')]]);
		
		//format data
		$date = date(\DATE_W3C);
		$title = $exception->getCode() . ': ' . $exception->getMessage();
		$backtraceString = self::FormatBacktraceString($backtrace);
		
		//php log
		if(self::$LogExceptions){
			$fileLog = self::FormatFileLog($date, 'EXCEPTION', $title, $backtraceString);
			self::Log($fileLog);
		}
		
		//log to file
		if(self::$LogExceptionsToFile){
			if(!isset($fileLog)) $fileLog = self::FormatFileLog($date, 'EXCEPTION', $title, $backtraceString);
			self::LogFile($fileLog);
		}
		
		//log to email
		if(self::$LogExceptionsToEmail){
			$emailLog = self::FormatEmailLog($date, 'EXCEPTION', $title, $backtraceString);
			self::LogEmail($date, $emailLog);
		}
		
		exit();
	}
	
	/**
	 * I do my code with error reporting set to E_ALL. 
	 * because of this I need to set default values to things that are not set to avoid E_NOTICE
	 * @param array $backtrace the backtrace array
	 * @return array backtrace array with default values for unset elements
	 */
	private static function BacktraceDefault($backtrace){
		foreach($backtrace as $k => $v){
			//defaults
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
	 * Log to phps default log
	 * @param string $log the log to be added
	 */
	public static function Log($log){
		error_log($log, 0);
	}

	/**
	 * log to the file. 
	 * @param string $log the log to be added
	 * @param string $file the file to add the log to.
	 * if no file is provided it will use the file property.
	 * if the property is not set then it will save to logs/Y-m-d.log
	 * Y-m-d being the year, month and day
	 */
	public static function LogFile($log, $file = NULL){
		error_log($log, 3, ($file ?: (self::$file ?: 'logs/' . date('Y-m-d') . '.log')));
	}
	
	/**
	 * send log to an email
	 * @param string $log the log to be added
	 * @param type $email the email address to send the log to.
	 * if none is provided it will try to use the email set int the email property.
	 */
	public static function LogEmail($log, $email = NULL){
		error_log($log, 1, self::$Email);
	}
	
	/**
	 * formats array values and will wrap in quotes if it is a string that is not not true, false or null.
	 * This method actually only calls the equavalent in the display class
	 * @param array $args array of args to format
	 */
	private static function FormatArgs(array $args){
		Display::FormatArgs($args);
	}
	
	/**
	 * formats backtrace as a string to use in log files
	 * @param array $backtrace
	 * @return string
	 */
	protected static function FormatBacktraceString($backtrace){
		foreach($backtrace as $v){
			$args = self::FormatArgs((isset($v['args']) ? $v['args'] : []));
			$log = PHP_EOL . "\t";
			$log .= (isset($v['file']) ? $v['file'] : 'N/A') . ' ';
			$log .= (isset($v['line']) ? $v['line'] : 'N/A ') . ': ';
			$log .= (isset($v['function']) ? $v['function'] : 'INTERNAL ') . '(';
			$log .= "($args)";
		}
		return $log;
	}
	
	/**
	 * formats the content for a file log
	 * @param string $date current date
	 * @param string $type error or exception
	 * @param string $title title message. this is the error message in this case
	 * @param string $log the log content
	 * @return string
	 */
	protected static function FormatFileLog($date, $type, $title, $log){
		return  "$type $title" . PHP_EOL
			. "[$date] " . $_SERVER['REQUEST_URI']
			. $log;
	}
	
	/**
	 * formats the content for an email log
	 * @param string $date current date
	 * @param string $type error or exception
	 * @param string $title title message. this is the error message in this case
	 * @param string $log the log content
	 * @return string
	 */
	protected static function FormatEmailLog($date, $type, $title, $log){
		return "An $type has been triggered on {$_SERVER['HTTP_HOST']}." . "\r\n"
			. "Time: $date" . "\r\n"
			. "Message: $title" . "\r\n"
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
	
	/**
	 * dump data.
	 * @param mixed $data data to dump
	 */
	public static function Dump($data){
		echo '<pre>';
		htmlentities(var_dump($data));
		echo '</pre>';
	}
	
	/**
	 * dump export data or catch it as a string
	 * @param mixed $data data to dump
	 * @param bool $capture set to true if you want it to return a string instead of echoing the data
	 * @return string
	 */
	public static function DumpExport($data, $capture = FALSE){
		if($capture)
			return '<pre>' 
				. htmlentities(var_export($data, TRUE)) 
				. '</pre>';
		echo '<pre>';
		htmlentities(var_export($data, TRUE));
		echo '</pre>';
	}
	
	/**
	 * dump "readable" data or catch it as a string. readable as in print_r instead of var_dump/var_export
	 * @param mixed $data data to dump
	 * @param bool $capture set to true if you want it to return a string instead of echoing the data
	 * @return string
	 */
	public static function DumpReadable($data, $capture = FALSE){
		if($capture)
			return '<pre>' 
				. htmlentities(print_r($data, TRUE)) 
				. '</pre>';
		echo '<pre>';
		htmlentities(print_r($data));
		echo '</pre>';
	}
	
	/**
	 * dump or catch code that is ready to be highlighted with syntaxHighlighter
	 * @param string $code code to dump
	 * @param string $brush the syntaxhighlighter brush name to use.
	 * @param bool $capture set to true if you want it to return a string instead of echoing the data
	 * @param bool $Collapse set to true if you want the code to be collapsed by default
	 * @param bool $Gutter set to false to disable the guter
	 * @param bool $HtmlScript set to true if it is html with embeded languages. note this is buggy
	 * @param bool $SmartTabs convert multiple spaces to tabs
	 * @param int $TabSize number of spaces in a tab
	 * @param bool $Toolbar display the syntaxHighlighter toolbar
	 * @return string
	 */
	public static function DumpCode($code, $capture = TRUE, $brush = 'php', $Collapse = FALSE, $Gutter = TRUE, $HtmlScript = FALSE,  $SmartTabs = TRUE, $TabSize = 4, $Toolbar = TRUE){
		$return = "<pre class='brush: $brush;>"
			. 'collapse: ' . ($Collapse ? 'true' : 'false') . ';'
			. 'gutter: ' . ($Gutter ? 'true' : 'false') . ';'
			. 'html-script: ' . ($HtmlScript ? 'true' : 'false') . ';'
			. 'smart-tabs: ' . ($SmartTabs ? 'true' : 'false') . ';'
			. 'tab-size: ' . $TabSize . ';'
			. 'toolbar: ' . ($Toolbar ? 'true' : 'false') . ';'
			. "'>"
			. htmlentities($code)
			. '</pre>';
		if($capture)
			return $return;
		echo $return;
	}		
}
?>