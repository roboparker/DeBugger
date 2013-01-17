<?php
	require 'bootstrap.php';

	function a() {
		b();
	}

	function b() {
        interface_exists( 'FooBar' );
		$f = new FooBar();
	}

	echo '{a: 39}';
	a( "<script>alert('blah')</script>");

	echo '{a: 39}';
