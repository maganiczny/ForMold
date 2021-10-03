<?php

	/*author!*/

	namespace ForMold;
	
	class Node {
		
		const FMD_NULL				= FMD_NULL;
		
		public $defaults 			= [];
		
		public function __construct ()
		{					
			if (func_num_args() > 0)
			{
				call_user_func_array([$this,'setAttr'],func_get_args());
			}
			
			$this->setDefaults();
		}
		
		private function setDefaults()
		{
			$this->setAttr($this->defaults);
		}
		
		
		public function setAttr()
		/*
			setAttr('(string)value') => set action attr
			setAttr('(string)key','(string)value') => set key as value
			setAttr((array)['key1'=>'value1','key2'=>'value2']) => set attributes foreach
			
			!! It is important to take care to change the value of the attribute using
			this method whenever possible..
		*/
		{
			$countAttr = func_num_args();
			$attr = func_get_args();
			
			//first possibility - 'key','value'
			if ($countAttr == 2 && is_string($attr[0]) && is_string($attr[1]))
			{
				$attr[0] = $this->dashes2CamelCase($attr[0]);
				$this->{$attr[0]} = $attr[1];
			}
			
			//second possibility - only value -> this is action (if parse url)
			elseif ($countAttr == 1 && is_string($attr[0]) && (filter_var($attr[0], FILTER_VALIDATE_URL) || $this->isPath($attr[0])))
			{
				$this->setAttr('action',$attr[0]);
			}
				
			//third possibility - is pass only one attr and is array []key=>val (isAssoc)
			elseif ($countAttr == 1 && is_array($attr[0]) && self::isAssoc($attr[0]))
			{
				foreach($attr[0] as $k=>$v)
				{
					if (in_array($k,$this->attr) || property_exists($this,$k))
						$this->setAttr($k,$v);
				}
			}
				
		}
		
		public function required()
		{
			$this->required = true;
		}
		
		public function html()
		{
			return '<' . $this->node . $this->getAttr() . ' />';
		}
		
		public function dashes2CamelCase($string, $capitalizeFirstCharacter = false) 
		{

			$str = str_replace('-', '', ucwords($string, '-'));

			if (!$capitalizeFirstCharacter) {
				$str = lcfirst($str);
			}

			return $str;
		}
		
		public function camelCase2dashed($className) {
			return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $className));
		}
		
		protected function isAssoc(array $arr)
		{
			if (array() === $arr) return false;
			return array_keys($arr) !== range(0, count($arr) - 1);
		}
		
		protected function getAttr()
		{
			$args = [];
			
			foreach($this->attr as $a)
			{				
			
				if (!property_exists($this,$a) || $this->{$a} === FMD_NULL || $this->{$a} === false)
					continue;
				
				if (is_string($this->{$a}))
					$args[] = $a . '="' . $this->{$a} . '"';
				elseif (is_bool($this->{$a}) && $this->{$a})
					$args[] = $a . '="true"';
				elseif (is_array($this->{$a}))
					$args[] = $a . '="' . implode(',',$this->{$a}) . '"';
			}
			return ((!empty($args)) ? ' ' : '') . implode(' ',$args);
		}
		
		public function isPath($path)
		{
			$path = trim($path);
			if(preg_match('/^[^*?"<>|:]*$/',$path)) return true; // good to go

			if(!defined('WINDOWS_SERVER'))
			{
				$tmp = dirname(__FILE__);
				if (strpos($tmp, '/', 0)!==false) define('WINDOWS_SERVER', false);
				else define('WINDOWS_SERVER', true);
			}
			/*first, we need to check if the system is windows*/
			if(WINDOWS_SERVER)
			{
				if(strpos($path, ":") == 1 && preg_match('/[a-zA-Z]/', $path[0])) // check if it's something like C:\
				{
					$tmp = substr($path,2);
					$bool = preg_match('/^[^*?"<>|:]*$/',$tmp);
					return ($bool == 1); // so that it will return only true and false
				}
				return false;
			}
			//else // else is not needed
				 return false; // that t
		}
		
	}

?>