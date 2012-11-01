<?php
require 'PHP/Template.php';
Template::$Content = function(){
?>
	<a href="download.php" title="Download" id="download">Download</a>
	<a href="donate.php" title="Donate" id="donate">Donate</a>
	<a href="demo.php" title="Demo" id="demo">Demo</a>
	<table id='features'>
		<tbody>
			<tr><td>Full back trace</td><td>Syntax highlighted code</td></tr>
			<tr><td>Dump methods</td><td>Get string values of error numbers</td></tr>
			<tr><td>Easily customizable</td><td>Create your own themes</td></tr>
		</tbody>
	</table>
<?php
};
Template::get();
?>