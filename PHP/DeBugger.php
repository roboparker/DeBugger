<?php
/**
 * Debugger @version 1.0
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
 *			I also am using my own theme in style tags. 
 *			If you use a theme it should overwrite mine with no problems
 *      jQuery				<http://jquery.com/>
 * 
 * @todo
 *		Exception handling
 *		Error logging
 *		Code output
 *		Theme support
 */
class DeBugger {

	/**
	 * path to jquery
	 * @var string path to jQuery
	 */
	public static $PathJQuery = '//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js';
	
	/**
	 * path to the scripts directory for syntax highlighter
	 * @var string path to the scripts folder fo syntax highlighter
	 */
	public static $PathSyntaxHighlighterScripts = 'syntaxhighlighter/scripts/';

	/**
	 * Set the number of lines of code to show before the error
	 * @var int
	 */
	public static $LinesBefore = 6;
	
	/**
	 * Set the number of lines of code to show after the error
	 * @var int
	 */
	public static $LinesAfter = 4;
	
	/**
	 * document root path. It will strip out the path from the file names on 
	 * the error page. defaults to $_SERVER['DOCUMENT_ROOT']
	 * @var string
	 */
	public static $Root;
	
	/**
	 * Turn on/off the display of the $_SERVER['http_*'] section
	 * @var bool
	 */
	public static $DisplayHTTP = TRUE;
	
	/**
	 * Turn on/off the display of the $_SERVER['request_*'] section
	 * @var bool
	 */
	public static $DisplayRequest = TRUE;
	
	/**
	 * Turn on/off the display of the $_POST[] variables
	 * @var bool
	 */
	public static $DisplayPost = TRUE;
	
	/**
	 * Turn on/off the display of the $_GET[] variables
	 * @var bool
	 */
	public static $DisplayGet = TRUE;
	
	/**
	 * Turn on/off the display of $_COOKIE[]
	 * @var bool
	 */
	public static $DisplayCookie = TRUE;
	
	/**
	 * Turn on/off the display of the $_SESSION[] variables
	 * @var bool
	 */
	public static $DisplaySession = TRUE;
	
	/**
	 * Turn on/off the display of the $_SERVER[] variables
	 * @var bool
	 */
	public static $DisplayServer = TRUE;
	
	//styling
	
	/**
	 * Background color
	 * @var string
	 */
	public static $StyleBackground = '#001';
	
	/**
	 * highlighted line background color
	 * @var string
	 */
	public static $StyleHighlighted = '#400';
	
	/**
	 * filehover background color
	 * @var strin
	 */
	public static $StyleHover = '#200';
	
	/**
	 * line number color
	 * @var string
	 */
	public static $StyleGutter = '#666';
	
	/**
	 * highlighted line numbe color
	 * @var string
	 */
	public static $StyleGutterHighlighted = '#FFF';
	
	/**
	 * default/plain text color
	 * @var string
	 */
	public static $StylePlain = '#FFF';
	
	/**
	 * keyword text color
	 * @var string
	 */
	public static $StyleKeyword = '#39F';
	
	/**
	 * constants text color
	 * @var string
	 */
	public static $StyleConstant = '#FC3';
	
	/**
	 * function/method text color
	 * @var string
	 */
	public static $StyleFunction = '#F0E386';
	
	/**
	 * variable text color
	 * @var string
	 */
	public static $StyleVariable = '#9CF';
	
	/**
	 * string text color
	 * @var string
	 */
	public static $StyleString = '#FFA0A0';
	
	/**
	 * number text color
	 * @var string
	 */
	public static $StyleNumber = '#C6F';
	
	/**
	 * comments text color
	 * @var string
	 */
	public static $StyleComments = '#999';
	
	/**
	 * script text color. This would be the php tags if $Html_script is true
	 * @var string
	 */
	public static $StyleScript = '#CCCCCC';
	
	//syntaxHighlighter options
	/**
	 * Allows you to turn detection of links in the highlighted element on and off
	 * @var bool
	 */
	private static $Auto_links = TRUE;
	
	/**
	 * Allows you to add a custom class (or multiple classes) to every highlighter element that will be created on the page
	 * @var string
	 */
	private static $Class_name = '';
	
	/**
	 * Allows you to force highlighted elements on the page to be collapsed by default.
	 * @var bool
	 */
	private static $Collapse = FALSE;
	
	/**
	 * Allows you to turn gutter with line numbers on and off
	 * @var bool
	 */
	private static $Gutter = TRUE;
	
	/**
	 * Allows you to highlight a mixture of HTML/XML code and a script which is very common in web development. 
	 * Setting this value to true requires that you have shBrushXml.js loaded and that the brush you are using supports this feature.
	 * 
	 * PHP code must have opening and closing tags to be rendered correctly if this is set to true
	 * @var bool 
	 */
	private static $Html_script = FALSE;
	
	/**
	 * Allows you to turn smart tabs feature on and off
	 * @var bool
	 */
	private static $Smart_tabs = TRUE;
	
	/**
	 * Allows you to adjust tab size
	 * @var int
	 */
	private static $Tab_size = 4;
	
	/**
	 * Toggles toolbar on/off
	 * @var bool
	 */
	private static $Toolbar = TRUE;
	
	/**
	 * An error Handler that echos a page and then dies. The page shows a full error backtrace, code from the errors and other usefull information.
	 * This is stricly for development and debugging and reveals secure infomation. <b>DO NOT</b> use on live sites.
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @return null
	 */
	public static function Handler($errno, $errstr, $errfile, $errline){
		//exit debug and continue?
		$report = error_reporting();
		if($report != ($report | $errno))
			return FALSE;		
		
		//backtrace
		$backtrace = debug_backtrace();
		array_shift($backtrace);//remove the stack about this handler
		$data = self::_parse($backtrace);

		//title
		$errname = self::GetErrorName($errno);
		$title = "<b>$errname: $errstr</b>";
		
		//echo page
		echo self::_Page($title, $data['code'], $data['file'], $data['other']);
		die();
	}
	/**
	 * Set an error handler.
	 * @param callback $handler A callback to handle php errors. 
	 * If null it will use the Thebugger::Handler.
	 * Thebugger::Handler is intended for development only
	 * @param int $lv The error levels the handler should be used for. 
	 * If null it will use the currently set error levels by using error_reportimg()
	 * Errors that are not passed to the handler are completly ignored
	 */
	public static function SetHandler($lv = NULL, $handler = NULL){
		$handler = ($handler === NULL ? 'TheBugger::Handler' : NULL);
		$lv = ($lv === NULL ? error_reporting() : NULL);
		set_error_handler($handler, $lv);
	}
	
	/**
	 * returns the string equvalent of an error number.
	 * i.e. 1 will return E_ERROR
	 * @param int $errno the error number
	 * @return string the error name
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
	 * dumps/echos information about the variable passed.
	 * This uses var_dump or print_r and outputs them in pre tags
	 * @param type $data The variable you want to get information on
	 * @param type $readable If true it will use print_r instead of var_dump
	 * @return null returns nothing. The data is autmatically outputed
	 */
	public static function Dump($data, $readable = TRUE){
		echo '<pre>';
		if($readable)
			print_r($data);
		var_dump($data);
		echo '</pre>';
	}

	public static function DumpCode($code){
		echo "<pre class='brush: php;>"
			. 'collapse: ' . (self::$Collapse ? 'true' : 'false') . ';'
			. (self::$Class_name ? 'class-name: ' . self::$Class_name . ';' : '')
			. 'gutter: ' . (self::$Gutter ? 'true' : 'false') . ';'
			. 'html-script: ' . (self::$Html_script ? 'true' : 'false') . ';'
			. 'smart-tabs: ' . (self::$Smart_tabs ? 'true' : 'false') . ';'
			. 'tab-size: ' . self::$Tab_size . ';'
			. 'toolbar: ' . (self::$Toolbar ? 'true' : 'false') . ';'
			. "'>"
			. $code
			. '</pre>';
	}
	
	/**
	 * parses a backtrace array and returns an array with the parsed information
	 * @param array $backtrace the backtrace array
	 * @return array returns an array with the parsed code, files and other information
	 */
	private static function _parse(&$backtrace){
		$code = [];
		$file = [];
		$other = [];
		$otherStr = '';
		for($i = count($backtrace); $i >= 1; $i--){
			$b = (object) $backtrace[$i - 1];
			$code[] = self::_code($b->file, $b->line);
			$file[] = self::_File($b);
		}
		$code = implode('', $code);
		$file = '<table cellspacing="0" cellpadding="0" border="0"><tbody>' . implode('', $file) . '</tbody></table>';
		foreach($_SERVER as $k => $v){
			if(preg_match('/^http/i', $k))
					$HTTP[substr($k, 5)] = $v;
			elseif(preg_match('/^request/i', $k))
					$request[substr($k, 8)] = $v;
		}
		if(self::$DisplayRequest)
			$other['request'] = self::_Other('REQUEST_*', $request);
		if(self::$DisplayHTTP)
			$other['http'] = self::_Other('HTTP_*', $HTTP);
		if(self::$DisplayPost)
			$other['post'] = self::_Other('POST', $_POST);
		if(self::$DisplayGet)
			$other['get'] = self::_Other('GET', $_GET);
		if(self::$DisplaySession)
			$other['session'] = self::_Other('SESSION', (isset($_SESSION) ? $_SESSION : []));
		if(self::$DisplayCookie)
			$other['cookie'] = self::_Other('COOKIE', $_COOKIE);
		if(self::$DisplaySession)
			$other['other'] = self::_Other('SERVER', $_SERVER);
		foreach($other as $k=>$v)
			$otherStr .= "<div class='$k'>$v</div>";
		return ['code'=>$code, 'file'=>$file, 'other'=>$otherStr];
	}
	
	/**
	 * Formats the code from the files in the backtrace and returns a string with html
	 * @param string $errfile the filename of the file with an error
	 * @param int $errline the line of the errror
	 * @return string an html formated string with the file and code from the file near the error line
	 */
	private static function _code(&$errfile, &$errline){
		$lines = file($errfile);
		$start = (($errline - self::$LinesBefore) > 1 ? $errline - self::$LinesBefore : 1);
		$end = (($errline + self::$LinesAfter) < count($lines) ? $errline + self::$LinesAfter : count($lines));
		$file = '';
		for($c = $start; $c <= $end; $c++)
			$file .= htmlentities($lines[$c - 1]);
		
		$errfile = str_replace((self::$Root ? self::$Root : $_SERVER['DOCUMENT_ROOT'] . '/'), '', str_replace('\\', '/', $errfile));
		return '<div>'
			. "<h3 class='comments'>$errline: $errfile</h3>"
				. "<pre class='brush: php;>"
					. 'collapse: false;'
					. (self::$Class_name ? 'class-name: ' . self::$Class_name . ';' : '')
					. "first-line: $start;"
					. 'gutter: ' . (self::$Gutter ? 'true' : 'false') . ';'
					. 'highlight: ' . $errline . ';'
					. 'html-script: ' . (self::$Html_script ? 'true' : 'false') . ';'
					. 'smart-tabs: ' . (self::$Smart_tabs ? 'true' : 'false') . ';'
					. 'tab-size: ' . self::$Tab_size . ';'
					. 'toolbar: ' . (self::$Toolbar ? 'true' : 'false') . ';'
				. "'>"
					. $file
				. "</pre>"
			. '</div>';
	}
	
	/**
	 * formats a table with the file names, line of the error, and the function/method call
	 * @param array $backtrace the backtrace array
	 * @return string an html formated string with the files and the line the error was on
	 */
	private static function _File(&$backtrace){
		foreach($backtrace->args as $k => $v)
			$backtrace->args[$k] = (is_string($v) ? '"' . $v . '"' : $v);
		$file = str_replace((self::$Root ? self::$Root : $_SERVER['DOCUMENT_ROOT'] . '/'), '', str_replace('\\', '/', $backtrace->file));
		return '<tr>'//highlight?
			. "<td class='file variable'>{$file}</td>"
			. "<td class='code'>"
				. '<pre class=\'brush: php; '
				. 'collapse: false;'
				. (self::$Class_name ? 'class-name: ' . self::$Class_name . ';' : '')
				. "first-line: {$backtrace->line};"
				. 'gutter: true;'
				. 'html-script: false;'
				. 'smart-tabs: ' . (self::$Smart_tabs ? 'true' : 'false') . ';'
				. 'tab-size: ' . self::$Tab_size . ';'
				. 'toolbar: false;'
				. '\'>'	
					. htmlentities(
						(isset($backtrace->class) ? $backtrace->class : '')
						. (isset($backtrace->type) ? $backtrace->type : '')
						. (isset($backtrace->function) ? $backtrace->function : '')
						. (isset($backtrace->args) ? '(' . implode(', ', $backtrace->args) . ')' : '')
					)
				. '</pre>'
			. '</td>'
			. '</tr>';
	}
	
	/**
	 * formats other data arrays to a table showing the key and value pairs
	 * @param string $title title of the section
	 * @param array $arr the data to display
	 * @return string an html formated string with the data as a table
	 */
	private static function _Other($title, $arr){
		
		$str = "<h3 class='keyword'>$title</h3>";
		if(count($arr) > 0){
			ksort($arr);
			$str .= '<table><tbody>';
			foreach($arr as $k => $v)
				$str .= "<tr><td class='comments'>$k</td><td class='plain'>=&gt;</td><td class='" . (!is_numeric($v) ? 'string\'>"' . htmlentities($v) . '"' : 'number\'>' . $v) ."</td></tr>";
			$str .= '</tbody></table>';
		}else
			$str .= "<div class='plain'>NULL</div>";
		return $str;
	}
	
	/**
	 * @return void echos the script and style tags for syntac highlighter
	 */
	public static function syntaxHighlighter(){
?>
		<style>
			/* page layout */
			*{
				margin: 0px;
				padding: 0px;
			}
			body, .syntaxhighlighter {
			  background-color: <?= self::$StyleBackground; ?>
			}
			h2{
				font-size: 1.5em;
				margin-bottom: 1em;
			}
			#wrapper{
				width:95%;
				margin:20px auto;
			}
			#code{
				margin: 0 0 2em 3em;
				height: <?php echo (self::$LinesBefore + self::$LinesAfter) *1 + 3.7; ?>em;
			}
			#code>div{
				display: none;
			}
			#code h3{
				font-size: 1.2em;
				margin: 0 0 0.5em -1em;
			}
			#file{
				margin-bottom: 2em;
			}
			#file>table{
				width: 100%;
			}
			#file>table tr:hover{
				cursor: pointer;
				background-color: <?= self::$StyleHover; ?>;
			}
			#file>table td.file{
				width: 250px;
				padding:0.5em 0 0.5em 1em;
			}
			#file>table td .gutter .line{
				border: 0;
			}
			#other{
				width: 100%;
				font-size: 0.9em;
			}
			#other>div{
				display:inline-block;
				min-width: 50%;
			}
			#other>div>h3{
				margin-top:1em;
			}
			#other>div>table, #other>div>div.plain{
				margin-left: 1em;
			}			
			#other>div>table td.plain{
				padding: 0px 1em;
			}
			/* syntax highlighter layout*/
			.syntaxhighlighter a,
			.syntaxhighlighter div,
			.syntaxhighlighter code,
			.syntaxhighlighter table,
			.syntaxhighlighter table td,
			.syntaxhighlighter table tr,
			.syntaxhighlighter table tbody,
			.syntaxhighlighter table thead,
			.syntaxhighlighter table caption,
			.syntaxhighlighter textarea {
				line-height: 1.1em;
				font-family: "Courier New", Courier, monospace;
			}
			.syntaxhighlighter{
				position: relative;
				overflow: auto;
			}
			.syntaxhighlighter table td.code {
				width: 100%;
			}
			.syntaxhighlighter table td.gutter .line {
				text-align: right;
				padding: 0 0.5em;
				min-width:1.5em;
			}
			.syntaxhighlighter table td.code{
				white-space: pre;
			}
			.syntaxhighlighter table td.code .line {
				padding: 0 0.2em;
			}
			.syntaxhighlighter.collapsed table {
				display: none;
			}
			.syntaxhighlighter.collapsed .toolbar {
				width: auto;
				padding: 0.1em 0.5em;
				position: static;
			}
			.syntaxhighlighter.collapsed .toolbar span a {
			  display: none;
			}
			.syntaxhighlighter.collapsed .toolbar span a.expandSource {
				display: inline;
			}
			.syntaxhighlighter .toolbar {
				position: absolute;
				right: 0px;
				top: 0px;
				width: 15px;
				height: 15px;
				font-size: 0.8em;
			}
			.syntaxhighlighter .toolbar a {
				color: <?= self::$StyleComments; ?>;
				display: block;
				padding-top: 0.1em;
				text-align: center;
				text-decoration: none;
			}
			.syntaxhighlighter .toolbar a.expandSource {
				display: none;
			}
			/* colors */
			.highlighted{
				background-color: <?= self::$StyleHighlighted; ?>
			}
			.syntaxhighlighter .gutter {
				color: <?= self::$StyleGutter; ?>
			}
			.syntaxhighlighter .gutter .line {
				border-right: 3px solid <?= self::$StyleHighlighted; ?>;
			}
			.syntaxhighlighter .gutter .line.highlighted {
				background-color: <?= self::$StyleHighlighted; ?>;
				color: <?= self::$StyleGutterHighlighted; ?>;
			}
			.plain, .plain a {
				color: <?= self::$StylePlain; ?>;
			}
			.comments, .comments a {
				color: <?= self::$StyleComments; ?>;
			}
			.string, .string a {
				color: <?= self::$StyleString; ?>;
			}
			.number, .number a {
				color: <?= self::$StyleNumber; ?>;
			}
			.keyword {
				color: <?= self::$StyleKeyword; ?>;
			}
			.variable {
				color: <?= self::$StyleVariable; ?>;
			}
			.functions {
				color: <?= self::$StyleFunction; ?>;
			}
			.constants {
				color: <?= self::$StyleConstant; ?>;
			}
			.script {
				font-weight: bold;
				color: <?= self::$StyleScript; ?>;
			}
			.keyword {
				font-weight: bold;
			}
		</style>
		<script type="text/javascript" src="<?= self::$PathSyntaxHighlighterScripts; ?>shCore.js"></script>
		<script type="text/javascript" src="<?= self::$PathSyntaxHighlighterScripts; ?>shBrushPhp.js"></script>
		<script type="text/javascript" src="<?= self::$PathSyntaxHighlighterScripts; ?>shBrushXml.js"></script>
		<script src="<?= self::$PathJQuery; ?>"></script>
		<script type="text/javascript">
			SyntaxHighlighter.all();
		</script>
<?php
	}
	
	/**
	 * echos a page with all the error data
	 * @param string $title the error message
	 * @param string $code the code
	 * @param string $file the file list
	 * @param string $other other data
	 */
	private static function _Page($title, $code, $file, $other){
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Error</title>
		<?php self::syntaxHighlighter(); ?>
    </head>
    <body>
		<div id="wrapper">
			<h2 class='keyword'><?= $title; ?></h2>
			<div id="code">
				<?= $code; ?>
			</div>
			<div id="file">
				<?= $file; ?>
			</div>
			<div id="other">
				<?= $other; ?>
			</div>
		</div>
    </body>
	<script type="text/javascript">
		$('#code>div:nth-child(1)').show();
		$('#file>table>tbody>tr:nth-child(1)').addClass('highlighted');
		$('#file>table>tbody>tr').live("click", function(){
			if($(this).hasClass('highlighted'))
				return;
			var index = $(this).index() +1;
			$('#file>table>tbody>tr.highlighted').toggleClass('highlighted');
			$(this).toggleClass('highlighted');
			$('#code>div:visible').fadeOut(750, function(){
				$('#code>div:nth-child('+ index + ')').fadeIn(750);
			});
		});
	</script>
</html>
<?php
	}
}
?>