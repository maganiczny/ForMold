<?php

	/**
	 * ForMold.php
	 *
	 * ForMold main library file
	 *
	 * @category   LibraryFile
	 * @package    ForMold
	 * @author     maganiczny
	 * @copyright  2021 maganiczny
	 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
	 * @version    0.1
	 * @link       https://github.com/maganiczny/ForMold
	 */
	 
	declare(strict_types=1);

	namespace ForMold;
	
	if ( ! defined ( 'FMD_NULL' ) )
		define('FMD_NULL', 'FMD_NULL');
	
	class ForMold extends Node {
		
		protected $attr				= [
			'id','class','accept-charset','action','autocomplete','enctype','method','name','novalidate','rel','target'
		];
							
		public $defaults			= [
			'accept-charset'		=> 'utf-8',
			'enctype'				=> 'application/x-www-form-urlencoded',
			'method'				=> 'post',
			'target'				=> '_self'
		];
		
		private $availableTags		= [
			'input'
		];
		
		private $elements			= [];
		
		//set default tag for new elements like input, textarea, button, select
		private $family				= 'input';
		
		public $label 				= false;
		
		public static function example()
		{
			include(str_replace('ForMold.php','example.php',__FILE__));
		}
		
		public function formOpen()
		{
			return '<form' . $this->getAttr() . '>';
		}
		
		public function formClose()
		{
			return '</form>';
		}
		
		public function formElements()
		{
			$html = [];
			
			foreach($this->elements as $e)
			{
				if ($this->label == true && !property_exists($e,'label'))
					$e->label = true;
				
				$html[] = $e->html();
			}
			
			return implode('<br />',$html);
		}
		
		public function html()
		{
			return $this->formOpen() . $this->formElements() . $this->formClose();
		}
		
		public function add()
		{
			$countAttr = func_num_args();
			$fattr = func_get_args();
			$attr = [];
			
			$attr['node'] = null;
			$attr['type'] = null;
			$classExists = true;
			
			//first possibility - one arg type string with | or without (Name or Name|type)
			//it always family node: input
			if ($countAttr == 1 && is_string($fattr[0]))
			{
				if (strpos($fattr[0], '|') === false)
				{
					$attr['name'] = $fattr[0];
				}
				else
				{
					$fattr[0] = explode('|',$fattr[0]);
					$attr['name'] = $fattr[0][0];
					$attr['type'] = $fattr[0][1];
				}				
			}
			
			//second possibility - one arg type array
			elseif ($countAttr == 1 && is_array($fattr[0]))
			{
				$attr = array_merge($attr,$fattr[0]);
			}
			
			if ($attr['node'] == null)
			{
				if ($attr['type'] !== null && class_exists('\\ForMold\\' . ucfirst($attr['type'])))
					$attr['node'] = $attr['type'];
				else
					$attr['node'] = $this->family;
			}
			
			$parentClass = '\\ForMold\\' . ucfirst($attr['node']);
			
			if ($attr['type'] == null && isset($attr['name']) && is_string($attr['name']))
			{
				$matches = preg_grep('/^'.$attr['name'].'$/i',$parentClass::$types);
				if ($matches !== false && !empty($matches))
				{
					$attr['type'] = strtolower(array_values($matches)[0]);
				}
					
			}
			
			$class = $parentClass;
			if (class_exists($parentClass))
			{
				if (property_exists($parentClass,'defaultType') && $attr['type'] == null)
					$attr['type'] = $parentClass::$defaultType;
			}
			
			if ($attr['type'] !== null)
				$class .= '\\' . ucfirst($attr['type']);
			
			
			if (!class_exists($class))
			{
				$class = $parentClass;
				if($attr['node'] !== $attr['type'])
				{
					$class = '\\ForMold\\Input\\Text';
					$attr['value'] = 'Element '. $attr['node'] . (isset($attr['type']) && $attr['type'] !== null ? ' (' . $attr['type'] . ' type)' : '') . ' not found';
					$attr['type'] = 'text';
					$attr['disabled'] = true;
					$classExists = false;
				}
			}
			
			$el = new $class($attr);
			
			$this->elements[] = $el;
			
			return $el;
			
		}
		
		public function get ($name)
		{
			$names = array_column($this->elements,'name');
			$element = array_search($name,$names);
			
			return ($element === false) ? new \ForMold\Input\Text(['disabled'=>true]) : $this->elements[$element];
		}
		
		public function submit()
		{
			return $this->add([
				'node'	=> 'Input',
				'name'	=> 'Submit',
				'value'	=> 'Submit'
			]);
		}
	
	}

?>