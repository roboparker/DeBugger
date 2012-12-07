<?php
class err {
	public function __construct() {
		$this->Foo('hello exception');
	}
	
	private function Foo($msg){
//		trigger_error('hello error');
		throw new Exception($msg);
	}
}

?>
