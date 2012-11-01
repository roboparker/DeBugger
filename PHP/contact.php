<?php
$sbj = 'PHP Debugger: ' 
	. htmlentities($_POST['subject']);
$message = 'From: ' 
	. htmlentities($_POST['name']) 
	."\n" 
	. htmlentities($_POST['message']);
@mail('webmaster@yamikowebs.com', $sbj, $message)
?>
