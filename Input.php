<?php

	namespace ForMold;
	
	class Input extends Node {
		
		protected $attr				= [
			'accept','alt','autocomplete','autofocus','checked','dirname',
			'disabled','form','formaction','formenctype','formmethod','formnovalidate',
			'formtarget','height','list','max','maxlength','multiple','name','patter',
			'placeholder','readonly','required','size','src','step','type','value','width'
		];
		
		static $defaultType			= 'text';
		
		static $types				= [
			'button','checkbox','color','date','datetimelocal','email','file','hidden',
			'image','month','number','password','radio','range','reset','search','submit',
			'tel','text','time','url','week'
		];
		
		public $node				= 'input';
		
	}

?>