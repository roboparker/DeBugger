<?php
	require 'bootstrap.php';

	function a() {
		b( 1, 2 );
	}

	function b( $a, $b, $c, $d=null ) {
	}

	a( "<script>alert('blah')</script>");
