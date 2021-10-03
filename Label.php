<?php

	namespace ForMold;
	
	class Label extends Node {
		
		protected $attr				= [
			'for','form'
		];
		
		static $defaultType			= 'text';
		public $node				= 'input';
		
		public function checked()
		{
			
		}
		
	}

?>