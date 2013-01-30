<?php
namespace Debugger;
class Dump {
	
	/**
	 * dump data.
	 * @param mixed $data data to dump
	 * @param bool $capture set to true if you want it to return a string instead of echoing the data
	 * @return string|void if capture is true it will return a string
	 */
	public static function Dump($data, $capture = FALSE){
		if($capture)
			ob_start ();
		echo '<pre>';
		htmlentities(var_dump($data));
		echo '</pre>';
		if($capture)
			return ob_get_clean();
	}
	
	/**
	 * dump export data or catch it as a string
	 * @param mixed $data data to dump
	 * @param bool $capture set to true if you want it to return a string instead of echoing the data
	 * @return string|void if capture is true it will return a string
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
	 * @return string|void if capture is true it will return a string
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
	 * @param string $data code to dump
	 * @param bool $capture set to true if you want it to return a string instead of echoing the data
	 * @param string $brush the syntaxhighlighter brush name to use.
	 * @param bool $Collapse set to true if you want the code to be collapsed by default
	 * @param bool $Gutter set to false to disable the guter
	 * @param bool $HtmlScript set to true if it is html with embeded languages. note this is buggy
	 * @param bool $SmartTabs convert multiple spaces to tabs
	 * @param int $TabSize number of spaces in a tab
	 * @param bool $Toolbar display the syntaxHighlighter toolbar
	 * @return string|void if capture is true it will return a string
	 */
	public static function DumpCode($data, $capture = TRUE, $brush = 'php', $Collapse = FALSE, $Gutter = TRUE, $HtmlScript = FALSE,  $SmartTabs = TRUE, $TabSize = 4, $Toolbar = TRUE){
		$return = "<pre class='brush: $brush;>"
			. 'collapse: ' . ($Collapse ? 'true' : 'false') . ';'
			. 'gutter: ' . ($Gutter ? 'true' : 'false') . ';'
			. 'html-script: ' . ($HtmlScript ? 'true' : 'false') . ';'
			. 'smart-tabs: ' . ($SmartTabs ? 'true' : 'false') . ';'
			. 'tab-size: ' . $TabSize . ';'
			. 'toolbar: ' . ($Toolbar ? 'true' : 'false') . ';'
			. "'>"
			. htmlentities($data)
			. '</pre>';
		if($capture)
			return $return;
		echo $return;
	}
}

?>
