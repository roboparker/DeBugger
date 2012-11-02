<?php
/**
 * autoload classes based on namespaces
 */
spl_autoload_register(function($class){
	$class = str_replace('\\','/',$class) . '.php';
	require($class);
});
?>
