<?php
require 'PHP/DeBugger.php';
use DeBugger\DeBugger;
require 'PHP/Template.php';
Template::$Content = function(){
	
	echo '
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="396Q7SC4PF844">
		<input type="submit" name="submit" value="Donate" id="donate" />
	</form>';
	
	echo '<h3>Basic Setup</h3>';
	DeBugger::DumpCode(<<<'EOD'
//include the file
require_once 'DeBugger.php';
//use the namespace
use DeBugger;
//set handler with no options for default settings
DeBugger::SetHandler();
EOD
	);
	
	echo '<h3>SetHandler Options</h3>';
	echo '<p>You can control the error levels to be handled and/or set your own handler.</p>';
	DeBugger::DumpCode(<<<'EOD'
//$lv will set what error levels trigger the handler
//null will use the error reporting level
DeBugger::SetHandler(E_ALL ^ E_NOTICE);//all errors except E_NOTICE

//$handler lets you set your own handler
function Handler($errno, $errstr, $errfile, $errline){
	//handle the error
	die();
}
DeBugger::SetHandler(NULL, 'Handler');
EOD
	);
	
	echo '<h3>General Options</h3>';
	DeBugger::DumpCode(<<<'EOD'
//path to jquery
DeBugger::$PathJQuery = '//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js';

//path to the scripts directory for syntax highlighter
DeBugger::$PathSyntaxHighlighterScripts = 'syntaxhighlighter/scripts/';

//Set the number of lines of code to show before the error
DeBugger::$LinesBefore = 6;

//Set the number of lines of code to show after the error
DeBugger::$LinesAfter = 4;

/**
 * document root path. It will strip out the path from the file names on 
 * the error page. defaults to $_SERVER['DOCUMENT_ROOT']
 */
DeBugger::$Root = $_SERVER['DOCUMENT_ROOT'];
EOD
	);
	
	echo '<h3>Display Options</h3>';
	echo '<p>Control what sections are displayed on the error page.</p>';
	DeBugger::DumpCode(<<<'EOD'
//Turn on/off the display of the $_SERVER['http_*'] section
DeBugger::$DisplayHTTP = TRUE;

//Turn on/off the display of the $_SERVER['request_*'] section
DeBugger::$DisplayRequest = TRUE;

//Turn on/off the display of the $_POST[] variables
DeBugger::$DisplayPost = TRUE;

//Turn on/off the display of the $_GET[] variables
DeBugger::$DisplayGet = TRUE;

//Turn on/off the display of $_COOKIE[]
DeBugger::$DisplayCookie = TRUE;

//Turn on/off the display of the $_SESSION[] variables
DeBugger::$DisplaySession = TRUE;

//Turn on/off the display of the $_SERVER[] variables
DeBugger::$DisplayServer = TRUE;
EOD
	);
	
	echo '<h3>Options for Syntax highlighter</h3>';
	echo '<p>Set options to be used by syntax highlighter. The options are used as class names and some may be overwritten or not used.</p>';
	DeBugger::DumpCode(<<<'EOD'
// Allows you to turn detection of links in the highlighted element on and off
DeBugger::$Auto_links = TRUE;

// Allows you to add a custom class (or multiple classes) to every highlighter element that will be created on the page
DeBugger::$Class_name = '';

// Allows you to force highlighted elements on the page to be collapsed by default.
DeBugger::$Collapse = FALSE;

// Allows you to turn gutter with line numbers on and off
DeBugger::$Gutter = TRUE;

/**
 * Allows you to highlight a mixture of HTML/XML code and a script which is very common in web development. 
 * Setting this value to true requires that you have shBrushXml.js loaded and that the brush you are using supports this feature.
 * PHP code must have opening and closing tags to be rendered correctly if this is set to true
 */
DeBugger::$Html_script = FALSE;

// Allows you to turn smart tabs feature on and off
DeBugger::$Smart_tabs = TRUE;

//number of spaces in a tab
DeBugger::$Tab_size = 4;

// Toggles toolbar on/off
DeBugger::$Toolbar = TRUE;
EOD
	);
	
	echo '<h3>Use your own themes</h3>';
	DeBugger::DumpCode(<<<'EOD'
DeBugger::SetTheme(new DeBuggerTheme(
			'#001',			//$background
			'#400',			//$highlightedBackground 
			'#200',			//$hoverBackground
			'#666',			//$gutter
			'#400',			//$gutterBorder
			'transparent',	//$gutterBackground
			'#FFF',			//$gutterHighlighted
			'#400',			//$gutterHighlightedBackground
			'#FFF',			//$plain
			'#39F',			//$keyword
			'#FC3',			//$constant
			'#F0E386',		//$function
			'#9CF',			//$variable
			'#FFA0A0',		//$string
			'#CCC',			//$script
			'#C6F',			//$number
			'#999'			//$comments
));
EOD
	);
	
	echo '<h3>Use a Theme from the collection</h3>';
	echo '<p>These themes are based off of the syntax highlighte themes. including a syntax highlighter css theme can cause errors.</p>';
	echo '<p>The Themes are stored internally as arrays. to get the name of the desired theme use the NAME_* propertry constants</p>';
	DeBugger::DumpCode(<<<'EOD'
DeBugger::SetTheme(
	DeBuggerThemeCollection::Get(
		DeBuggerThemeCollection::NAME_DEFAULT
));
EOD
	);
	
	echo '<h3>Add your own theme to the collection</h3>';
	DeBugger::DumpCode(<<<'EOD'
DeBugger::Set('MyTheme', new DeBuggerTheme(
			'#001',			//$background
			'#400',			//$highlightedBackground 
			'#200',			//$hoverBackground
			'#666',			//$gutter
			'#400',			//$gutterBorder
			'transparent',	//$gutterBackground
			'#FFF',			//$gutterHighlighted
			'#400',			//$gutterHighlightedBackground
			'#FFF',			//$plain
			'#39F',			//$keyword
			'#FC3',			//$constant
			'#F0E386',		//$function
			'#9CF',			//$variable
			'#FFA0A0',		//$string
			'#CCC',			//$script
			'#C6F',			//$number
			'#999'			//$comments
));
EOD
	);
	
	echo '<h3>Dumping</h3>';
	echo 'var_dump and print_r need to be wrapped in pre tags to keep there spacing. 
		This does it autoamatically and can also dump code ready to be highlighted by syntax highlighter</p>';
	DeBugger::DumpCode(<<<'EOD'
//print_r
DeBugger::Dump($var);

//var_dump
DeBugger::Dump($var, FALSE);

//code
DeBugger::DumpCode(<<<'FOO'
<?php
	echo "hello world";
?>
Foo
);

/* to highlight code */
DeBugger::Style();//in head
DeBugger::JS();between </body> and </html>
EOD
	);
	
	echo '<h3>Getting Error Names</h3>';
	echo '<p>PHP Errors are numbers and this handy method will get you the name of the error level</p>';
	DeBugger::DumpCode(<<<'EOD'
//gets the error name for error 1 which is E_ALL
$errname = DeBugger::GetErrorName(1);
EOD
	);
	
	echo '<h3>Other Info</h3>';
	echo '<p>PHP DeBugger uses a modified version of <a href="http://alexgorbatchev.com/SyntaxHighlighter/" target="_blank">syntaxhighlighter</a> version 3.0.83. 
		using other versions may cause errors or unexpedected results.</p>';
	echo '<p>Using syntaxhighlighter themes will overwrite my own. 
		Just note they do not have styles for numbers since this is a small feature I added along with 
		updating it with all php functions, keywords and constants.</p>';
	echo '<p>If you wish to use another version you can download the modified <a href="downloads/shBrushPhp.js" target="_blank">PHP Brush</a> seperatly and place it in the scripts directory.';
	
	echo '<h3>TO DO</h3>';
	echo '<p>I plan on adding var_logging with backtrace support, email notifications, 
		and exception handling.</p>';
};
	
Template::$Debugger = TRUE;
Template::get();
?>