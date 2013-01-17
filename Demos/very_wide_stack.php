<?php
	require 'bootstrap.php';

	function a() {
		b();
	}

	function b() {
		$foo = $bar;
	}

	a( "fooobar fooobar fooobar fooobar fooobar fooobar fooobar fooobar fooobar fooobar", "fooobar", "fooobar", "fooobar", "fooobar", "fooobar", "fooobar", "fooobar" );
