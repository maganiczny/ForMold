<?php
	
	namespace ForMold\Button;
	
	class Submit extends \ForMold\Button {
	
		public $type 		= 'submit';
		
		public $bootstrap4ClassInput		= ['btn','btn-primary'];
	
		public function __construct ($params)
		{
			
			if (!array_key_exists('name',$params))
				$params['name'] = 'submit';
			
			parent::__construct($params);
			
		}

	}
	
?>