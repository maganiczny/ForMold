<?php

	namespace ForMold;
	
	class Validate {
		
		function __construct()
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
			
			var_dump($data);
			
			if ($paramsNumber <= 0 && ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($data['submit']))))
				return;


			var_dump('not done!');
			/*if ($paramsNumber <= 0 || (!isset() && isset($_SESSION['_fmd']) || !is_array($_SESSION['_fmd']) || !isset($_SESSION['_fmd']['elements']) || !is_array($_SESSION['_fmd']['elements']) || empty($_SESSION['_fmd']['elements'])))
				return;*/
			
			

			if ($paramsNumber > 0)
			{
				
			}
			elseif (count($_SESSION['_fmd']['elements']) > 0)
			{
				
			}
			var_dump($data);
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
		
	}

?>