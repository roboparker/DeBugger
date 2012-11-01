<?php
require 'PHP/Template.php';
Template::$Content = function(){
?>
	<a href="" title="Download" id="download">Download</a>
	<a href="" title="Donate" id="donate">Donate</a>
	<h3>License</h3>
	<p>
		You may use Debuggger under the terms of either the MIT License or the 
		GNU General Public License (GPL) Version 3.
	</p>
	<p>
		The MIT License is recommended for most projects. It is simple and easy to understand and it 
		places almost no restrictions on what you can do with Debugger.
	<p>
		If the GPL suits your project better you are also free to use Debugger under that license.
	</p>
	<p>
		You don’t have to do anything special to choose one license or the other and you don’t have to notify anyone which license you are using.
		You are free to use Debugger in commercial projects as long as the copyright header is left intact.
	</p>
	<p>
		<b>Download: </b>
		<a href='downloads/MIT-LICENSE.txt' title='MIT LICENSE' target="_blank">MIT</a> - 
		<a href='downloads/GPL-LICENSE.txt' title='GPL LICENSE' target="_blank">GPL</a>
	</p>
	<p>
		<b>Links: </b><a href='http://www.gnu.org/licenses/gpl.html' title='GPL LICENSE' target="_blank">GPL</a>
	</p>
<?php
};
Template::get();
?>