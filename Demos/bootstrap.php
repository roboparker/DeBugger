<?php
//include
require '../Debugger/Dump.php';
require '../Debugger/Log.php';
require '../Debugger/Theme.php';
require '../Debugger/Display.php';
require '../Debugger/Themes/STD.php';

//or autoload
//spl_autoload_register(function($class){
//	$class = '../' . str_replace('\\','/',$class) . '.php';
//	include_once $class;
//});

use Debugger\Log;
use Debugger\Display;

Display::$PathSH = '../' . Display::$PathSH;
Display::$DisplayCookie = FALSE;
Display::$DisplayServer = FALSE;
Display::$DisplaySession = FALSE;
Log::StartErrorHandler(Log::LOG_DISPLAY | Log::LOG_FILE, E_ALL);
?>
