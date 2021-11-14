<?php
	
	namespace ForMold;
	
	class Button extends Node {
	
		public $node 			= 'button';
		public $selfClosed		= false;
		
		static $defaultType 	= 'button';
	
		public $attr 		= [
			'id', 'type', 'autofocus', 'checked', 'disabled',
			'form', 'formaction', 'min', 'max', 'name',
			'placeholder', 'readonly', 'required', 'size',
			'value', 'class'
		];
		
		static $bootstrapClass	= ['btn','btn-primary'];
		
	}
	
?>