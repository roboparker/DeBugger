<?php
class Template {
	public static $Content;
	public static $Debugger = false;

	public static function get(){
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>PHP Debugger</title>
		<?php if(self::$Debugger)DeBugger::syntaxHighlighter(); ?>
		<style>
			*{
				margin: 0px;
				padding: 0px;
				position: relative;
			}
			body{
				font-family: "Trebuchet MS", Helvetica, sans-serif;
				background-image: url('noisy_net.png');
			}
			a{
				color: #006;
				font-weight: bold;
				text-decoration: none;
			}
			a:hover{
				color: #000;
				text-decoration: underline;
			}
			input[type=submit]{
				cursor: pointer;
			}
			h1, h2, h3, h4, h5, h6{
				margin-bottom: 10px;
				font-family: "Times New Roman", Times, serif;
				color: #003;
			}
			h3{
				font-size: 2em;
			}
			P{
				margin-bottom: 10px;
			}
			.center{
				width: 960px;
				margin:0px auto;
			}
			.syntaxhighlighter{
				padding: 10px 0px;
				margin-bottom: 20px;
			}
			#top, footer{
				padding: 5px;
				color: #FFF;
			}
			#top>nav{
				font-size:1.4em;
				text-align: right;
			}
			#top>nav>h1{
				color: #9CF;
				float: left;
			}
			#top>nav a{
				color: #9CF;
				display: inline-block;
				margin: 0px 5px;
				text-decoration: none;
				-webkit-transition: color .4s linear;  
				-moz-transition: color .4s linear;  
				-o-transition: color .4s linear;  
				-ms-transition: color .4s linear;  
				transition: color .4s linear; 
			}
			#top>nav a:hover{
				color: #FFF;
				-webkit-transition: color .4s linear;  
				-moz-transition: color .4s linear;  
				-o-transition: color .4s linear;  
				-ms-transition: color .4s linear;  
				transition: color .4s linear; 
			}
			#top>nav>a, #top>nav>span{
				top: 0.3em;
			}
			
			#content{
				min-height: 600px;
				padding: 20px 0px;
				background-image: url('polaroid.png');
			}
			header{
				text-align: center;
				font-size: 2.5em;
				margin-bottom: 20px;
			}
			#shadowbox{
				position: absolute;
				top:0px;
				left:0px;
				width: 100%;
				height: 100%;
				background-color: rgba(0,0,0,0.9);
				display: none;
			}
			#shadowbox>form{
				width:460px;
				margin: 75px auto 0px auto;
				padding: 30px;
				background-image: url('noisy_net.png');
				-webkit-border-radius: 15px;
				-moz-border-radius: 15px;
				border-radius: 15px;
				overflow: visible;
			}
			#shadowbox>form>label{
				font-weight: bold;
				color: #FFF;
				display: inline-block;
				margin-bottom: 10px;
			}
			#shadowbox>form>label.error{
				width: 275px;
				position: absolute;
				left: 510px;
				font-weight: normal;
				color: #C00;
				background-color: #FFF;
			}
			#shadowbox>form>label.error:after{
				position: absolute;
				left: -20px;
				top: 0px;
				content: ' ';
				width: 0px;
				height: 0px;
				border: 10px solid transparent;
				border-right-color: #FFF;
			}
			#shadowbox>form>input{
				float: right;
				width: 380px;
			}
			#shadowbox>form>textArea{
				width: 100%;
				height: 200px;
				margin-bottom: 10px;
			}
			#shadowbox>form>input[type=submit]{
				width: 100%;
				padding: 5px 0px;
				text-align: center;
			}
			
			/* features */
			blockquote{
				margin-bottom: 20px;
				font-size: 1.1em;
				font-weight: bold;
				font-style: italic;
				text-align: center;
			}
			#features{
				margin: 20px auto 0px auto;
			}
			#features td{
				font-size: 1.5em;
				padding: 5px 50px;
			}
			
			/* download */
			#download, #donate, #demo{
				display: block;
				width: 100%;
				padding: 10px 0px;
				margin-bottom: 15px;
				color: #FFF;
				font-family: "Times New Roman", Times, serif;
				font-size: 2.5em;
				font-weight: bold;
				text-align: center;
				text-decoration: none;
				-webkit-border-radius: 15px;
				-moz-border-radius: 15px;
				border-radius: 15px;
				-webkit-transition: background-color 500ms linear, color 500ms linear;
				-moz-transition: background-color 500ms linear, color 500ms linear;
				-o-transition: background-color 500ms linear, color 500ms linear;
				-ms-transition: background-color 500ms linear, color 500ms linear;
				transition: background-color 500ms linear, color 500ms linear;
			}
			#download, #demo{
				background-color: #003;
			}
			#donate{
				background-color: #EA7600;
			}
			#download:hover, #demo:hover{
				color: #000;
				background-color: #9CF;
			}
			#donate:hover{
				color: #000;
				background-color: #FC0;
			}
		</style>
    </head>
    <body>
		<div id='top'>
			<nav class="center">
				<h1><a href='index.php' title='Home'>PHP Debugger</a></h1>
				<a href='download.php' title='Download'>Download</a>
				<span> - </span>
				<a href='documentation.php' title='Document'>Documentation</a>
				<span> - </span>
				<a href='#contact' title='Contact'>Contact</a>
			</nav>
			<br style='clear: both;' />
		</div>
		
		<div id="content">
			<article class='center'>
				<header>
					<h2>Elegant Debugging for PHP 5.4+</h2>
				</header>
				<blockquote>
					PHP Debugger will handle your errors beautifully by printing out a full back trace stack 
					with syntax highlighted code for each level along with other helpful information 
					for debugging during PHP web development.
				</blockquote>
				<?php call_user_func(self::$Content); ?>
			</article>
		</div>
		
		<footer>
			
		</footer>
		
		<div id='github'  style="position: absolute; top: 0; left: 0; border: 0;" >
			<a href="https://github.com/you">
			<img
				 src="https://s3.amazonaws.com/github/ribbons/forkme_left_orange_ff7600.png" alt="Fork me on GitHub">
			</a>
		</div>
		
		<div id='shadowbox'>
			<form action="" method="post" id='contact'>
				<label>Name: </label><input type='text' name="name" class="required" minlength="2" maxlength="64"/><br />
				<label>Email: </label><input type='text' name="email" class="required email" minlength="2" maxlength="64"/><br />
				<label>Subject: </label><input type='text' name="subject" class="required" minlength="2" maxlength="64"/><br />
				<label>Message: </label><br />
				<textarea name='message' class="required" minlength="20"></textarea><br />
				<input type='submit' value='Send' /><br style ="clear: both;"/>
			</form>
		</div>
		
    </body>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
	<script type="text/javascript" >
		$('a[href=#contact]').click(function(){
			 $('#shadowbox').fadeIn(500);
		});
		var mouse_is_inside = false;
		$('#shadowbox>form').hover(function(){ 
			mouse_is_inside = true;
		}, function(){ 
			mouse_is_inside = false; 
		});
		$("#shadowbox").mouseup(function(){
			if(!mouse_is_inside) $('#shadowbox').fadeOut(500);
		});
		$('#contact').validate({
			submitHandler: function(form){
				$.post('PHP/contact.php', $('#contact').serialize(), function(){
					$('#contact').html('<label style="font-size:1.5em;">Your message has benn sent!</label>');
				});
        }});
	</script>
</html>
<?php
	}
}

?>
