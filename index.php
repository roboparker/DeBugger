<?php
require 'PHP/Template.php';
Template::$Content = function(){
?>
	<a href="download.php" title="Download" id="download">Download</a>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="396Q7SC4PF844">
		<input type="submit" name="submit" value="Donate" id="donate" />
	</form>
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