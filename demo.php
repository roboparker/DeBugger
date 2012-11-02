<?php
require 'PHP/DeBugger.php';
use DeBugger\DeBugger;
DeBugger::SetHandler();
DeBugger::$DisplayHTTP = FALSE;
DeBugger::$DisplayServer = FALSE;
DeBugger::$DisplaySession = FALSE;
require 'Demo/autoloader.php';
Demo\Foo::FakeMethodCall(20, "string");
?>
