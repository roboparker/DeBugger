<?php
	require 'bootstrap.php';

	function a() {
		b();
	}

	function b() {
		$foo = $bar;
	}

	echo '{a: 39}';
	a( "<script>alert('blah')</script>");

	echo '{a: 39}';
