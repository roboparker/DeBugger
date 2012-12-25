<?php
namespace DeBugger;
use DeBugger\Core;
use DeBugger\Themes\STD as STD;
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
class Display {
	/**
	 * path to SyntaxHighlighter scripts folder
	 * @var string
	 */
	public static $PathSH = 'DeBugger/syntaxhighlighter/scripts/';
	
	/**
	 * path to jQuery
	 * @var string
	 */
	public static $PathJQuery = '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js';
	
	/**
	 * application root path. It will be striped from file names on the display
	 * @var string
	 */
	public static $Root;
	
	/**
	 * number of lines of code to show before the error line
	 * @var int
	 */
	public static $LinesBefore = 6;
	
	/**
	 * number of lines of code to show after the error line
	 * @var int
	 */
	public static $LinesAfter = 4;
	
	/**
	 * display a section for $_SERVER["request_*"]
	 * @var bool
	 */
	public static $DisplayRequest = TRUE;
	
	/**
	 * display a section for $_SERVER["htpp_*"]
	 * @var bool
	 */
	public static $DisplayHTTP = TRUE;
	
	/**
	 * display a section for post data
	 * @var bool
	 */
	public static $DisplayPost = TRUE;
	
	/**
	 * display a section for get data
	 * @var bool
	 */
	public static $DisplayGet = TRUE;
	
	/**
	 * display a section for session data
	 * @var bool
	 */
	public static $DisplaySession = TRUE;
	
	/**
	 * display a section for cookie data
	 * @var bool
	 */
	public static $DisplayCookie = TRUE;
	
	/**
	 * display a section for all server data
	 * @var bool
	 */
	public static $DisplayServer = TRUE;
	
	/**
	 * stores a debugger theme
	 * @var \DeBugger\Themes\STD 
	 */
	private static $Theme;
	
	/**
	 * container for user added sections
	 * @var array
	 */
	protected static $ExtraSection = [];

	/**
	 * set a debugger theme. if null the default will be used
	 * @param \DeBugger\STD $theme
	 */
	public static function SetTheme(STD $theme = NULL){
		self::$Theme = ($theme ?: new STD());
	}
	
	/**
	 * add a section to the display page
	 * @param string $name name/title of the section
	 * @param array $data data to diplay in key value pairs
	 */
	public static function SetSection($name, array $data){
		self::$ExtraSection[$name] = $data;
	}
	
	/**
	 * get a user specific section by the name/title
	 * @param string $name name/title of the section
	 * @param array $data data to diplay in key value pairs
	 * @return array
	 */
	public static function GetSection($name, array $dat){
		return self::$ExtraSection[$name];
	}
	
	/**
	 * remove a user specified section from the display
	 * @param type $name
	 * @param array $dat
	 */
	public static function UnsetSection($name, array $dat){
		unset(self::$ExtraSection[$name]);
	}


	/**
	 * build the page
	 * @param string $title messgae
	 * @param array $backtrace 
	 */
	public static function Build($title, $backtrace){
		foreach($backtrace as $v){
			$b = (object) $v;
			$b->file = str_replace(self::$Root, '', $b->file);
			$files[] = self::File($b);
			$code[] = self::Code($b->file, $b->line);
		}
		self::GetHtml($title, implode('', $code), implode('', $files));
	}
	
	/**
	 * generate the files section
	 * @param object $b object cast of backtrace
	 * @return string
	 */
	protected static function File($b){
		$param = '(' . self::FormatArgs($b->args) . ')';
		$return[] = "<td class='file'>$b->file</td>";
		$return[] = '<td><pre class=\'brush: php; '
			. 'collapse: false;'
			. "first-line: {$b->line};"
			. 'gutter: true;'
			. 'html-script: false;'
			. 'smart-tabs: true;'
			. 'tab-size: 4;'
			. 'toolbar: false;'
			. '\'>'
			. htmlentities($b->class . $b->type . $b->function . $param)
			. '</pre></td>';
			
		return (!$b->line ? '<tr class="internal">' : '<tr>')
			. implode('', $return) 
			. '</tr>';
	}
	
	/**
	 * generate the code section of the page
	 * @param string $file file
	 * @param int $line line from the file
	 * @return string
	 */
	protected static function Code($file, $line){
		//if internal return empty
		if($line == NULL)
			return '<article></article>';
		
		//which lines to grab
		$lines = file($file);
		$start = (($line - self::$LinesBefore) > 1 ? $line - self::$LinesBefore : 1);
		$end = (($line + self::$LinesAfter) < count($lines) ? $line + self::$LinesAfter : count($lines));
		
		//grab the file content
		for($c = $start; $c <= $end; $c++)
			$content[] = $lines[$c - 1];
		$content = implode('', $content);
		
		//create and return the html
		$return[] = "<article>";
		$return[] = "<h3>$line: $file</h3>";
		$return[] = '<pre class=\'brush: php; '
			. 'collapse: false;'
			. "first-line: $start;"
			. "highlight: $line;"
			. 'gutter: true;'
			. 'html-script: false;'
			. 'smart-tabs: true;'
			. 'tab-size: 4;'
			. 'toolbar: false;'
			. '\'>'
			. htmlentities($content)
			. '</pre>';
		$return[] = '</article>';
		
		return implode('', $return);
	}
	
	/**
	 * generate the other data sections
	 * @return string
	 */
	protected static function Data(){
		//grab shorthand data from $_SERVER
		foreach($_SERVER as $k => $v){
			if(preg_match('/^http/i', $k))
					$http[substr($k, 5)] = $v;
			elseif(preg_match('/^request/i', $k))
					$request[substr($k, 8)] = $v;
		}
		
		//grab/format the data
		if(self::$DisplayRequest)
			$return[] = self::FormatOther ('REQUEST', $request);
		if(self::$DisplayHTTP)
			$return[] = self::FormatOther ('HTTP', $http);
		if(self::$DisplayPost)
			$return[] = self::FormatOther ('POST', $_POST);
		if(self::$DisplayGet)
			$return[] = self::FormatOther ('GET', $_GET);
		if(self::$DisplaySession)
			$return[] = self::FormatOther ('SESSION', (isset($_SESSION) ? $_SESSION : []));
		if(self::$DisplayCookie)
			$return[] = self::FormatOther ('COOKIE', $_COOKIE);
		if(self::$DisplayServer)
			$return[] = self::FormatOther ('SERVER', $_SERVER);
		if(count(self::$ExtraSection))
			foreach(self::$ExtraSection as $k => $v)
				$return[] = self::FormatOther ($k, $v);
		return implode('', $return);
	}

	/**
	 * generate the other sections of the page
	 * @param string $title section title
	 * @param array $data section data
	 * @return string
	 */
	private static function FormatOther($title, array $data){
		if(count($data) > 0){
			ksort($data);
			foreach($data as $k => $v){
				if(is_numeric($v))
					$class = 'number';
				elseif(preg_match ('/(true|false|null)/i', $v))
					$class = 'keyword';
				else{
					$class = 'string';
					$v = '"' . htmlentities($v) . '"';
				}
				$content[] = "<tr>" 
					. "<td class='comments'>$k</td>" 
					. '<td class="plain">=&gt;</td>' 
					. "<td class='$class'>" . htmlentities($v) . "</td>" 
					. '</tr>';
			}
		}else
			$content[] = '<tr><td colspan="3" class="variable">NULL</td></tr>';
		
		return '<table>'
			. "<thead><tr><th colspan='2' class='keyword'>$title</th></tr></thead>"
			. '<tbody>'
			. implode('', $content)
			. '</tbody></table>';
	}

	/**
	 * formats array values and will wrap in quotes if it is a string that is not not true, false or null.
	 * @param array $args array of args to format
	 * @return string
	 */
	public static function FormatArgs(array $args){
		if(!count($args)) return '';
		$formated = [];
		foreach($args as $v){
			if( is_null($v) || is_bool($v) || is_numeric($v) || is_object($v) || is_resource($v))
				$formated[] = $v;
			elseif(is_string($v))
				$formated[] = "\"$v\"";
			else
				$formated[] = $v;
		}
		return implode(', ', $formated);
	}
	
	/**
	 * generate the script section
	 */
	public static function GetScript(){
?>
		<script type="text/javascript" src="<?= self::$PathSH; ?>shCore.js"></script>
		<script type="text/javascript" src="<?= self::$PathSH; ?>shBrushPhp.js"></script>
		<script type="text/javascript" src="<?= self::$PathSH; ?>shBrushXml.js"></script>
		<script src="<?= self::$PathJQuery; ?>"></script>
		<script type="text/javascript">
			SyntaxHighlighter.all();
			
			$('#code>article:nth-child(1)').show();
			$('#code>table>tbody>tr:nth-child(1)').addClass('highlighted');
		
		$('#code>table>tbody>tr').live("click", function(){
			if($(this).hasClass('highlighted') || $(this).hasClass('internal'))
				return;
			var index = $(this).index() +1;
					$('#code>table>tbody>tr.highlighted').toggleClass('highlighted');
					$(this).toggleClass('highlighted');
			$('#code>article:visible').fadeOut(500, function(){
				$('#code>article:nth-child('+ index + ')').fadeIn(500);
			});
		});
		</script>
<?php
	}
	
	/**
	 * generate the page styles
	 */
	public static function GetStyle(){
		if(!self::$Theme) self::SetTheme();
?>
		<?=self::$Theme->GetStyle();?>
		<style>
			/* formating */
			*{
				margin: 0px;
				padding: 0px;
			}
			h1{
				margin-bottom: 1em;
				font-size: 1.5em;
			}
			h1, h2, h3{
				font-family: Georgia, serif;
			}
			body>div{
				width: 95%;
				margin: 1.5em auto;
			}
			article{
				display: none;
				margin-left: 3em;
				font-size: 1em;
				height: <?php echo (self::$LinesBefore + self::$LinesAfter) *1.1 + 4.7; ?>em;
			}
			article>h3{
				font-size: 1.2em;
				margin: 0px 0px 1em -1em;
			}
			#code>table{
				width: 100%;
				margin: 2em 0em;
				font-size: 1.1em;
				border-collapse: collapse;
			}
			#code>table td{
				padding: 5px;
			}
			#code>table .file{
				width: 350px;
				padding-left: 1.5em;
			}
			#code>table .gutter .line{
				border: 0 !important;
			}
			#other>table{
				display:inline-block;
				border-collapse: collapse;
				min-width: 50%;
				margin-bottom: 1em;
			}
			#other>table tr:hover{
				background-color: <?= self::$Theme->HighlightedLineBG; ?>;
			}
			#other>table thead>tr:hover{
				background-color: transparent;
			}
			#other>table th{
				text-align: left;
			}
			#other>table td{
				padding: 0em 0.5em;
			}
			
			/* colors */
			body{
				background-color: <?=self::$Theme->BG?>;
				color: <?=self::$Theme->Plain?>;
			}
			#code>table tr.highlighted{
				background-color: <?= self::$Theme->HighlightedLineBG; ?>;
				-webkit-transition: background-color 300ms linear;
				-moz-transition: background-color 300ms linear;
				-o-transition: background-color 300ms linear;
				-ms-transition: background-color 300ms linear;
				transition: background-color 300ms linear;
			}
			#code>table>tbody>tr:hover{
				cursor: pointer;
				background-color: <?= self::$Theme->HighlightedLineHoverBG; ?>;
				-webkit-transition: background-color 300ms linear;
				-moz-transition: background-color 300ms linear;
				-o-transition: background-color 300ms linear;
				-ms-transition: background-color 300ms linear;
				transition: background-color 300ms linear;
			}
			#code>table>tbody>tr.internal:hover{
				cursor: default;
				background-color: transparent;
			}
			#other .number{color: <?= self::$Theme->Number; ?>;}
			#other .keyword {color: <?= self::$Theme->Keyword; ?>;}
			#other .string{color: <?= self::$Theme->String; ?>;}
			#other .comments{color: <?= self::$Theme->Comment; ?>;}
			#other .variable {color: <?= self::$Theme->Variable; ?>;}
			tr.internal{
				opacity: 0.4;
			}
			table .syntaxhighlighter{
				background-color: transparent;
			}
			h1{
				color: <?=self::$Theme->Keyword?>;
			}
			h3, td.file{
				color: <?=self::$Theme->Variable?>;
			}
		</style>
<?php
	}
	
	protected static function GetHtml($title, $code, $files){
		ob_get_contents();
		ob_end_clean();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
		<title><?=$title?></title>
		<?=self::GetStyle();?>
	</head>
	<body>
		<div>
			<h1><?=$title?></h1>
			<section id="code">
				<?=$code?>
				<table>
					<tbody>
						<?=$files?>
					</tbody>
				</table>
			</section>
			<section id="other">
				<?=self::Data()?>
			</section>
		</div>
	</body>
	<?=self::GetScript()?>
</html>
<?php
	}
}
?>
