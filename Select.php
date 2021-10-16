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
		
		public $selected		= null;
		
		public function addOption($name,$value = null)
		{
			if (is_string($name))
			{
				if ($value === null)
					$value = $name;
				
				$this->options[] = ['name'=>$name,'value'=>$value];
			}
			elseif (is_array($name))
			{
				foreach($name as $v=>$n)
				{
					$this->addOption($n,$v);
				}
			}
			
			return $this;
		}
		
		public function selected($sel)
		{
			$this->selected = $sel;
			
			return $this;
		}
		
		public function html()
		{
			
			$value = '';
			
			foreach($this->options as $opt)
			{
				$value .= "\n<option value=\"".$opt['value']."\"".(($this->selected == $opt['value']) ? " selected" : "").">".$opt['name']."</option>";
			}
			
			$this->value = $value."\n";
			
			return parent::html();
		}
	
	}
	
?>