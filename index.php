<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
<?php
require 'DeBugger/DeBugger.php';
require 'DeBugger/DeBuggerTheme.php';
require 'DeBugger/DeBuggerThemeCollection.php';
require 'Demo/autoloader.php';
require 'err.php';
use DeBugger\DeBugger;
DeBugger::Start();
DeBugger::SetRoot($_SERVER['DOCUMENT_ROOT'] . '/DeBugger/');
$e = new err();
?>
    </body>
</html>
