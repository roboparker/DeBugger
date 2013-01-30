<?php
namespace Debugger;
/**
 * didnt merge correctly
 */
/**
 * @todo 
 *		exit on
 *		INI config file
 *		set use defined display?
 *		user defined email template?
 *		user defined log templates?
 *		ignore certain files/folder in the stack
 */
class Log {	
	const MODE_ERROR = 1;
	const MODE_EXCEPTION = 2;
	
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
}
?>
