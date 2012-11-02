<?php
require '../DeBugger/DeBugger.php';
use DeBugger\DeBugger;
DeBugger::SetHandler();
DeBugger::$DisplayHTTP = FALSE;
DeBugger::$DisplayServer = FALSE;
DeBugger::$DisplaySession = FALSE;
//set the path to scripts relative to this file
DeBugger::$PathSyntaxHighlighterScripts = '../' . DeBugger::$PathSyntaxHighlighterScripts;
DeBugger::$Root .= $_SERVER['DOCUMENT_ROOT'] . '/DeBugger/Demo/';
require 'autoloader.php';
Demo\Foo::FakeMethodCall(20, "string");
?>
