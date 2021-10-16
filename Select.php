<?php
	
	namespace ForMold;
	
	class Select extends Node {
	
		public $selfClosed		= false;
		public $node 			= 'select';
		
		static $defaultType		= 'select';
		
		public $type			= 'select';
	
		public $attr 			= [
			'id', 'type', 'autofocus', 'checked', 'disabled',
			'form', 'formaction', 'min', 'max', 'name',
			'placeholder', 'readonly', 'required', 'size',
			'value', 'class'
		];
		
		public $options			= [];
		
		public function addOption($title,$name = null)
		{
			
			if ($name !== null)
				$this->options[$name] = $title;
			else
				$this->options[] = $title;
			
			return $this;
		}
		
		public function html()
		{
			
			$value = '';
			
			foreach($this->options as $name => $opt)
			{
				$value .= "\n<option value=\"".$name."\">".$opt."</option>";
			}
			
			$this->value = $value."\n";
			
			return parent::html();
		}
	
	}
	
?>