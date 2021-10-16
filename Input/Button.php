<?php
	
	namespace ForMold\Input;
	
	class Button extends \ForMold\Input {
	
		public $type						= 'button';
		
		public $bootstrap4ClassInput		= ['btn','btn-primary'];
	
		public function __construct ($params) {
			
			parent::__construct($params);
			
			if ($this->value === FormBuilder_NULL)
				$this->value = $this->name;
			
		}
		
	}
	
?>