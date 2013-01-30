<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
<?php
spl_autoload_register(function($class){
	$class = __DIR__ . '/' . $class . '.php';
	if(file_exists($class))
		include_once $class;
});


error_reporting(E_ALL);
//setup
use DeBugger\DeBugger;
use DeBugger\Settings;

$opt = new Settings(Settings::LogFile | Settings::LogDisplay);
$opt->SetErrorLV(E_ALL);
$opt->SetExceptionLV();
$opt->SetFile('log.txt');
DeBugger::SetSettings($opt);

//$opt = new SettingsStack(SettingsStack::LogFile | SettingsStack::LogDisplay);
//$opt->SetExceptionLV();
//DeBugger::SetStack($opt);

DeBugger::Start();
//test
//throw new Exception("foo");
trigger_error("foo", E_USER_ERROR);
//$foo = new Bar;
?>
    </body>
</html>
