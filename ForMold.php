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
		
		public $node				= 'form';
							
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
		
		private $rKey;
		private $keyLifetime		= 60; // in seconds
		
		public $values				= [];
		
		public $bootstrap			= false;
		
		public function __construct()
		{
			parent::__construct(...func_get_args());
			
			foreach($_SESSION['_fmd']['keys'] as $k=>$v)
			{
				if ($v < time() - $this->keyLifetime)
				{
					unset($_SESSION['_fmd']['keys'][$k]);
					unset($_SESSION['_fmd']['elements'][$k]);
				}
			}
		}
		
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
			
			foreach($this->elements as $k => $e)
			{
				if ($this->label == true && !property_exists($e,'label'))
					$e->label = true;
				
				if (property_exists($e,'id'))
				{
					$id = $this->elements[$k]->id;
					
					$sercz = array_search($id,array_column($this->elements,'id'));
					
					while(in_array($id,array_column($this->elements,'id')) && $sercz < $k)
					{
						preg_match('/\d+$/i',$id,$lastNumbers);
					
						if ($lastNumbers)
						{
							$lastNumbers[0]++;
							$id = preg_replace('/(\d+)$/',''.$lastNumbers[0],$id);
						}
						else
							$id .= '2';
						
					}
					$this->elements[$k]->id = $id;
				}
				
				if (!empty($e::$bootstrapClass))
				{
					$e->addClass($e::$bootstrapClass);
				}
				
				$html[] = $e->html();
			}
			
			return implode("<br />\n",$html);
		}
		
		public function html()
		{
			if (!in_array('fmdtoken',array_column($this->elements,'name')))
				$this->add('fmdtoken|hidden')->value($this->rKey);
			
			return $this->formOpen() . $this->formElements() . $this->formClose();
		}
		
		public function add()
		{
			if (empty($this->rKey))
			{
				$this->rKey = $this->randomString();
				$_SESSION['_fmd']['lastForm'] = $this->rKey;
				$_SESSION['_fmd']['keys'][$this->rKey] = time();
			}
			
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
				preg_match('/^(?:(?P<label>.+)\s*,\s*)?(?P<name>[^,|]+)(?:\|(?P<type>.+))?$/i',$fattr[0],$match_attr);

				if (!empty($match_attr['name']))
					$attr['name'] = $match_attr['name'];

				if (!empty($match_attr['type']))
					$attr['type'] = $match_attr['type'];

				if (!empty($match_attr['label']))
					$attr['label'] = $match_attr['label'];
			}
			
			//second possibility - one arg type array
			elseif ($countAttr == 1 && is_array($fattr[0]))
			{
				if (count($fattr[0]) == 1 && strpos(array_values($fattr[0])[0], ',') !== false)
				{
					$attr['type'] = array_keys($fattr[0])[0];
					$nameLabel = explode(',',array_values($fattr[0])[0]);
					$attr['name'] = $nameLabel[0];
					$attr['label'] = $nameLabel[1];
				}
				else
				{
					$attr = array_merge($attr,$fattr[0]);
				}
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
			
			if (!isset($_SESSION['_fmd']['elements'][$this->rKey]) || !is_array($_SESSION['_fmd']['elements'][$this->rKey]))
				$_SESSION['_fmd']['elements'][$this->rKey] = [];
			
			$thisEl = [
				'dataType'	=> self::$dataType,
				'class'		=> $class,
				'type'		=> $attr['type'],
				'name'		=> isset($attr['name']) ? $attr['name'] : null
			];
			
			if (isset($attr['name']) && $classExists)
				$_SESSION['_fmd']['elements'][$this->rKey][$attr['name']]  = $thisEl;
			
			$el = new $class($attr);
			
			$this->elements[] = $el;
			
			return $el;
			
		}
		
		public function validate($elements = null)
		{
			
			$params = func_get_args();
			$paramsNumber = func_num_args();
			
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$data = $this->test_input($_POST);
			}
			else
				$data = $this->test_input($_GET);
			
			if (isset($data['fmdtoken']) && isset($_SESSION['_fmd']['elements']))
			{
				$csrf_keys = array_keys($_SESSION['_fmd']['elements']);
				
				if (!in_array($data['fmdtoken'],$csrf_keys))
					return false;
				
				$this->rKey = $data['fmdtoken'];
				
				if ($elements == null)
					$elements = $_SESSION['_fmd']['elements'][$data['fmdtoken']];
				
			}
			
			if (is_array($elements))
			{
				foreach ($elements as $k => $e)
				{
					if (isset($e['type']))
					{
						if ($e['type'] == 'checkbox')
							$data[$e['name']] = (isset($data[$e['name']]));
					}
				}
			}
			
			if (!isset($data['submit']) || empty($data['submit']))
				return false;
			
			unset($data['submit']);
			
			return($data);
		}
		
		private function test_input($input)
		{
			if (is_string($input))
			{
				
			}
			elseif (is_array($input))
			{
				foreach($input as $k => $i)
				{
					$input[$k] = $this->test_input($i);
				}
			}
			
			return $input;
		}
		
		public function get ($name)
		{
			if (!in_array('fmdtoken',array_column($this->elements,'name')))
				$this->add('fmdtoken|hidden')->value($this->rKey);
			
			$names = array_column($this->elements,'name');
			$element = array_search($name,$names);
			
			return ($element === false) ? new \ForMold\Input\Text(['disabled'=>true]) : $this->elements[$element];
		}
		
		public function getHtml($name)
		{
			return $this->get($name)->html();
		}
		
		public function addSubmit($value = null)
		{
			return $this->add('submit|submit')->value(($value !== null) ? $value : 'Submit');
		}
		
		public function removeToken()
		{
			unset($_SESSION['_fmd']['elements'][$this->rKey]);
			unset($_SESSION['_fmd']['keys'][$this->rKey]);
		}
	
	}

?>