<?php
require 'PHP/DeBugger.php';
require 'PHP/Template.php';
Template::$Content = function(){
	
	echo '<a href="" title="Donate" id="donate">Donate</a>';
	
	echo '<h3>Basic Setup</h3>';
	DeBugger::DumpCode(<<<'EOD'
/* ALL reportable errors will generate a 
 * page with a full error report and kill the script
 */
DeBugger::SetHandler();
EOD
);
	
	echo '<h3>Control Error Reporting levels</h3>';
	DeBugger::DumpCode(<<<'EOD'
//with phps error report
error_reporting(E_ALL ^ E_NOTICE );
//or as the first parameter
DeBugger::SetHandler(E_ALL ^ E_NOTICE);
EOD
);

	echo '<h3>Display Settings</h3>';
	echo '<p>Turn On/Off whats displayed on the error page</p>';
	DeBugger::DumpCode(<<<'EOD'
//$_SERVER['http_*'] section
DeBugger::$DisplayHTTP = TRUE;

//$_SERVER['request_*'] section
DeBugger::$DisplayRequest = TRUE;

//$_POST[] variables
DeBugger::$DisplayPost = TRUE;

//$_GET[] variables
DeBugger::$DisplayGet = TRUE;

//$_COOKIE[]
DeBugger::$DisplayCookie = TRUE;

//$_SESSION[] variables
DeBugger::$DisplaySession = TRUE;

//$_SERVER[] variables
DeBugger::$DisplayServer = TRUE;
EOD
);
	
	echo '<h3>Styling</h3>';
	DeBugger::DumpCode(<<<'EOD'
DeBugger::$StyleBackground = '#001';
DeBugger::$StyleHighlighted = '#400';
DeBugger::$StyleHover = '#200';
DeBugger::$StyleGutter = '#666';
DeBugger::$StyleGutterHighlighted = '#FFF';
DeBugger::$StylePlain = '#FFF';
DeBugger::$StyleKeyword = '#39F';
DeBugger::$StyleConstant = '#FC3';
DeBugger::$StyleFunction = '#F0E386';
DeBugger::$StyleVariable = '#9CF';
DeBugger::$StyleString = '#FFA0A0';
DeBugger::$StyleNumber = '#C6F';
DeBugger::$StyleComments = '#999';
DeBugger::$StyleScript = '#CCCCCC';
EOD
);
	
	echo '<h3>Syntax Highlighter Options</h3>';
	echo '<p>Set options for syntax highlighter. 
		The oprions are set in the pre class tags.
		some options will not effect the code outputed on the error page
		</p>';
	DeBugger::DumpCode(<<<'EOD'
//Allows you to turn detection of links in the highlighted element on and off
private static $Auto_links = TRUE;

//Allows you to add a custom class (or multiple classes) to every highlighter element that will be created on the page
private static $Class_name = '';

//Allows you to force highlighted elements on the page to be collapsed by default.
private static $Collapse = FALSE;

//Allows you to turn gutter with line numbers on and off
private static $Gutter = TRUE;

/**
 * Allows you to highlight a mixture of HTML/XML code and a script which is very common in web development. 
 * Setting this value to true requires that you have shBrushXml.js loaded and that the brush you are using supports this feature.
 * PHP code must have opening and closing tags to be rendered correctly if this is set to true
 */
private static $Html_script = FALSE;

//Allows you to turn smart tabs feature on and off
private static $Smart_tabs = TRUE;

//number of spaces in a tab
private static $Tab_size = 4;

//Toggles toolbar on/off
private static $Toolbar = TRUE;
EOD
);
	
	echo '<h3>Dumping</h3>';
	DeBugger::DumpCode(<<<'EOD'
//print_r dump
DeBuger::Dump($data);

//var_dump
DeBugger::Dump($data, FALSE);
EOD
);
	
	echo '<h3>Dumping Code</h3>';
	DeBugger::DumpCode(<<<'EOD'
//in your head tags to set the styles and run the scripts
DeBuger::SyntaxHighligher();

//run this where you wish to dumo your code
DeBugger::DumpCode($code);
EOD
);

	echo '<h3>Get Error Names</h3>';
	DeBugger::DumpCode(<<<'EOD'
$errname = DeBugger::GetErrorName(1);//will return "E_ERROR"
EOD
);
	echo '<h3>Set Your Own Handler</h3>';
	DeBugger::DumpCode(<<<'EOD'
DeBugger::SetHandler(E_ALL ^ E_NOTICE, $errorhandler);
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
		exception handling and improving the theme support. I admit it\'s sloppy atm</p>';
};
	
Template::$Debugger = TRUE;
Template::get();
?>