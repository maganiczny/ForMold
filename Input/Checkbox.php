<?php
	
	namespace FoRMold\Input;
	
	class Checkbox extends \FoRMold\Input {
		
		public $label = true;
		public $labelRight = true;
	
		public $type		= 'checkbox';
		
		public $bootstrap4ClassInput = 'form-check-input';
		public $bootstrap4ClassLabel = 'form-check-label';
	
		public function checked()
		{
			$this->checked = true;
			
			return $this;
		}
	
	}
	
?>