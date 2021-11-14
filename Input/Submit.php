<?php

	namespace ForMold\Input;
	
	class Submit extends \ForMold\Input {
		
		public $type			= 'submit';
		
		public $name			= 'submit';
		
		static $bootstrapClass	= ['btn','btn-primary'];
	}

?>