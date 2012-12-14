<?php
namespace Demo;
class Foo {
	public static function FakeMethodCall($int, $string){
		$result = DB::Get($int, $string);
		return (object) $result;
	}
}
?>
