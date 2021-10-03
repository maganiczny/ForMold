<?php
	
	namespace FormBuilder\input;
	
	class email extends \FormBuilder\Input {
	
		public $type 		= 'email';
		
		public $validable	= true;
		
		public function validate (string $input)
		{
			return filter_var($input, FILTER_VALIDATE_EMAIL);
		}
	
	}
	
?>