<?php
namespace Demo;
require 'autoloader.php';
use \DeBugger\Core;
use \DeBugger\Display;
Core::StartAll(E_ALL);
Display::$PathSH = '../' . Display::$PathSH;

Foo::FakeMethodCall(20, "string");
?>