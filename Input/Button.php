<?php
	
	namespace FormBuilder\input;
	
	class button extends \FormBuilder\Input {
	
		public $type						= 'button';
		
		public $bootstrap4ClassInput		= ['btn','btn-primary'];
	
		public function __construct ($params) {
			
			parent::__construct($params);
			
			if ($this->value === FormBuilder_NULL)
				$this->value = $this->name;
			
		}
	
	}
	
?>