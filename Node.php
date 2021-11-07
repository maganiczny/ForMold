<?php

	namespace ForMold;
	
	class Node {
		
		const FMD_NULL				= FMD_NULL;
		
		public $selfClosed			= true;
		public $defaults 			= [];
		public $id_prefix			= '_fmd_';
		
		public $globalAttr 			= ['accesskey','class','contenteditable','dir','draggable',
			'hidden','id','lang','spellcheck','style','tabindex','title','translate'
		];
		
		public $labelRight			= false;
		public $label				= false;
		
		static $dataType			= 'string';
		
		public $class				= [];
		
		public function __construct ()
		{					
		
			$this->attr = array_unique(array_merge($this->attr,$this->globalAttr));
		
			if (func_num_args() > 0)
			{
				$this->setAttr(...func_get_args());
			}
			
			$this->setDefaults();
			
			if (!property_exists($this,'id') && property_exists($this,'name'))
			{
				$this->id = $this->id_prefix . $this->name;
			}
			
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			
			if (!isset($_SESSION['_fmd']) || !is_array($_SESSION['_fmd']))
			{
				$_SESSION['_fmd'] = [];
			}
			
			if (!isset($_SESSION['_fmd']['elements']) || !is_array($_SESSION['_fmd']['elements']))
			{
				$_SESSION['_fmd']['elements'] = [];
			}
			
			if (!isset($_SESSION['_fmd']['keys']) || !is_array($_SESSION['_fmd']['keys']))
			{
				$_SESSION['_fmd']['keys'] = [];
			}
			
			if (!defined('FMD_OBJECT_EXIST'))
				define('FMD_OBJECT_EXIST',true);
			
		}
		
		private function setDefaults()
		{
			foreach($this->defaults as $k=>$v)
			{
				if (property_exists($this,$k) || (property_exists($this,$k) && $this->{$k} !== FMD_NULL))
					continue;
				
				$this->setAttr($k,$v);
			}
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
			if ($countAttr == 2 && is_string($attr[0]) && !empty($attr[1]))
			{
				$attr[0] = $this->dashes2CamelCase($attr[0]);
				$this->{$attr[0]} = $attr[1];
				
				if ($attr[0] == 'name' && !property_exists($this,'id'))
				{
					$this->id = $this->id_prefix . $attr[1];
				}
			}
			
			//second possibility - only value ->
			elseif ($countAttr == 1 && is_string($attr[0]))
			{
				// this is action (if parse url)
				if (filter_var($attr[0], FILTER_VALIDATE_URL) || $this->isPath($attr[0]))
					$this->setAttr('action',$attr[0]);
				// toggle boolean if this bool
				elseif (property_exists($this,$attr[0]) && is_bool($this->{$attr[0]}))
					$this->{$attr[0]} = !$this->{$attr[0]};
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
			
			return $this;
			
		}
		
		public function required()
		{
			$this->required = true;
			
			return $this;
		}
		
		public function label($str = null)
		{
			if ($str !== null)
				$this->label = $str;
			elseif (property_exists($this,'name') && (!property_exists($this,'label') || !is_string($this->label)))
				$this->label = $this->name;
			
			return $this;
		}
		public function placeholder($str)
		{
			$this->placeholder = $str;
			
			return $this;
		}
		
		public function value($val)
		{
			$this->value = $val;
			
			return $this;
		}
		
		public function html()
		{
			$html = '';
			
			$wrapLabel = false;
			
			if (property_exists($this,'label') && $this->label !== false && property_exists($this,'name'))
			{
				$wrapLabel = true;
				
				if (!is_string($this->label))
				{
					$this->label = $this->name;
				}
			}
			
			
			
			if ($wrapLabel)
			{
				$html .= '<label for="'.$this->id.'">';
				if (!$this->labelRight)
					$html .= $this->label . ' ';
			}
			
			$html .= '<' . $this->node . $this->getAttr();
			if ($this->selfClosed)
				$html .= ' />';
			else
			{
				$html .= '>';
				
				if (property_exists($this,'value'))
					$html .= $this->value;
				
				$html .= '</' . $this->node . '>';
			}
			
			if ($wrapLabel)
			{
				if ($this->labelRight)
					$html .= ' ' . $this->label;
				$html .= '</label>';
			}
			
			return $html;
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
				
				if (!$this->selfClosed && $a == 'value' && $this->type !== 'submit')
					continue;
				
				if (is_bool($this->{$a}) && $this->{$a} !== false)
					$args[] = $a;
				elseif (is_array($this->{$a}) && !empty($this->{$a}))
					$args[] = $a . '="' . implode(' ',$this->{$a}) . '"';
				else
					$args[] = $a . '="' . $this->{$a} . '"';
					
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
		
		public function phpSelf()
		{
			return $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}
		
		public function randomString()
		{
			 if(!isset($length) || intval($length) <= 8 ){
				$length = 32;
			}
			if (function_exists('random_bytes')) {
				$rs =  bin2hex(random_bytes($length));
			}
			elseif (function_exists('openssl_random_pseudo_bytes')) {
				$rs =  bin2hex(openssl_random_pseudo_bytes($length));
			}
			
			$rs = time() . $rs . 'j83j!(#)n@0snxA)S(AS09sdm2d0(#@RDS0@($&%^ąółaoif';
			
			return hash('sha256',$rs);
		}
		
		static function selfValidate($data)
		{
			return true;
		}
		
		public function addClass()
		{
			$args = func_get_args();
			//case first - one arg
			if (func_num_args() == 1)
			{
				//if string
				if (is_string($args[0]))
					$this->class[] = $args[0];
				//if array
				elseif (is_array($args[0]))
					$this->class = array_unique(array_merge([],$this->class,$args[0]));
			}
			elseif (func_num_args() > 1)
			{
				foreach($args as $arg)
				{
					$this->addClass($arg);
				}
			}
		}
		
	}

?>
