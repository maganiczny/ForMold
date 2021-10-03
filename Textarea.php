<?php
	
	namespace ForMold;
	
	class Textarea extends Node {
	
		public $selfClosed		= false;
		public $node 			= 'textarea';
		
		static $defaultType		= 'textarea';
	
		public $attr 		= [
			'id', 'type', 'autofocus', 'checked', 'disabled',
			'form', 'formaction', 'min', 'max', 'name',
			'placeholder', 'readonly', 'required', 'size',
			'value', 'class'
		];
	
	}
	
?>