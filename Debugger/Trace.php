<?php
namespace DeBugger;
class Trace {
	public static function getCaller(){
		$trace = debug_backtrace(FALSE, 2);
		// 0 is this call, 1 is call in previous function, 2 is caller of that function
		return $trace[2];
	}
	
	/**
	 * gets a specific attribute of the caller
	 * @param string $attr the attribute
	 * @return mixed|null returns null if not set
	 */
	private static function getCallerAttr($attr){
		$trace = debug_backtrace(FALSE, 3);
		// 0 is this call, 1 is calling method, 2 is caller of that method, 3 is what we want
		return (isset($trace[3][$attr]) ? $trace[3][$attr] : null);
	}
	
	/**
	 * The calling function name. See also __FUNCTION__
	 * @return string|null
	 */
	public static function getCallerFunction(){
		return self::getCallerAttr('function');
	}
	
	/**
	 * The calling line number. See also __LINE__
	 * @return int|null
	 */
	public static function getCallerLine(){
		return self::getCallerAttr('line');
	}
	
	/**
	 * The calling file name. See also __FILE__
	 * @return string|null
	 */
	public static function getCallerFile(){
		return self::getCallerAttr('file');
	}
	
	/**
	 * The calling class name. See also __CLASS__
	 * @return string|null
	 */
	public static function getCallerClass(){
		return self::getCallerAttr('class');
	}
	
	/**
	 * The calling object
	 * @return string|null
	 */
	public static function getCallerOject(){
		return self::getCallerAttr('object');
	}

	/**
	 * The calling call type. If a method call, "->" is returned. 
	 * If a static method call, "::" is returned. 
	 * If a function call, nothing is returned
	 * @return string|null
	 */
	public static function getCallerType(){
		return self::getCallerAttr('type');
	}
	
	/**
	 * If inside a function, this lists the functions arguments. 
	 * If inside an included file, this lists the included file name(s)
	 * @return array|null
	 */
	public static function getCallerArgs(){
		return self::getCallerAttr('args');
	} 
}

?>
