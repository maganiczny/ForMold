<?php
	
	namespace ForMold\Input;
	
	class Radio extends \ForMold\Input {
	
		public $label = true;
		public $labelRight = true;
	
		public $type 		= 'radio';
	
		public $bootstrap4ClassInput = ['form-check-input'];
		public $bootstrap4ClassLabel = 'form-check-label';
		
		public function checked()
		{
			$this->checked = true;
			
			return $this;
		}
	
	}
	
?>