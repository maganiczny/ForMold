<?php

	/**
	 * example.php
	 *
	 * Examples for ForMold
	 *
	 * @category   Examples
	 * @package    ForMold
	 * @author     maganiczny
	 * @copyright  2021 maganiczny
	 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
	 * @version    0.1
	 * @link       https://github.com/maganiczny/ForMold
	 */

	//You can show all this examples using a static method: \ForMold\ForMold::example();

	function br2nl($string)
	{
		return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	}

	use \ForMold\ForMold as ForMold;
	use \ForMold\Validate as Validate;

	//Example 1, simple login with value for fast test validation
	
	echo '<h4>Example 1</h4>';
	//$fmd = new ForMold (['method'=>'get']);
	$fmd = new ForMold (['values'=>$_POST]);
	
	$fmd->add(['email'=>'email, Email'])->value('user@example.com')->required();
	$fmd->add(['password'=>'pass, Hasło'])->value('test')->required();
	$fmd->add('Name,name|textarea')->value('test')->required();
	$fmd->add('dezd')->label()->value('test2')->required();
	
	$fmd->add(['checkbox'=>'remember, Zapamiętaj'])->checked();
	
	$fmd->add('wybor|radio')->Label('Ziemniak');
	$fmd->add('wybor|radio')->Label('Ogórek')->checked();
	$fmd->add('wybor|radio')->Label('Marchewka');
	
	$fmd->add('lista|select')->addOption('opcja 1')->addOption('opcja 2');
	
	$fmd->add(['type'=>'submit'])->value('Send')->required();
	
	echo nl2br(htmlentities(br2nl($fmd->html())));
	echo '<br /><br />';
	echo $fmd->html();
	
	if (isset($_POST))
	{
		$fmd = new ForMold();
		$data = $fmd->validate();
		var_dump($data);
		$fmd->removeToken();
	}



	//Example 2, simple login form
	
	echo '<h4>Example 2</h4>';
	$fmd = new ForMold ('./login.php');

	$fmd->add('Login')->required()->placeholder('Email');
	$fmd->add('Password')->required()->label('Hasło')->value('treść');
	$fmd->add('Remember|checkbox')->checked();
	$fmd->addSubmit('Login');
	
	echo nl2br(htmlentities(br2nl($fmd->html())));
	echo '<br /><br />';
	echo $fmd->html();
	
	
	
	//Example 3, form to add comment

	echo '<h4>Example 3</h4>';
	$fmd = new ForMold ('./comment.php');
	
	$fmd->add('Name',['required',true]);
	$fmd->add('Email')->required(); //required() works same as ,['required'=>true]
	$fmd->add('Comment|textarea');
	$fmd->add('Comment|nonexistent');
	$fmd->addSubmit('Add');
	
	echo nl2br(htmlentities(br2nl($fmd->html())));
	echo '<br /><br />';
	echo $fmd->html();
	
	
	
	//Example 4, simple settings form with labels
	
	echo '<h4>Example 4</h4>';
	$fmd = new ForMold (['action'=>'./action.php','label'=>true]);
	
	$fmd->add('SiteName');
	
	echo nl2br(htmlentities(br2nl($fmd->html())));
	echo '<br /><br />';
	echo $fmd->html();
	
	
	
	//Example 5, simple login form but You can get each element
	
	echo '<h4>Example 5</h4>';
	$fmd = new ForMold ('./login.php');

	$fmd->add('Login')->required()->placeholder('Email');
	$fmd->add('Password')->required();
	$fmd->add('Remember|checkbox')->checked();
	$fmd->addSubmit('Login');
	
	echo htmlentities($fmd->formOpen()).'<br />';
	echo htmlentities($fmd->get('Remember')->html()).'<br />';
	echo htmlentities($fmd->get('Password')->html()).'<br />';
	echo htmlentities($fmd->get('Login')->html()).'<br />';
	echo htmlentities($fmd->formClose()).'<br />';
	echo '<br /><br />';
	echo $fmd->formOpen();
	echo $fmd->get('Remember')->html();
	echo $fmd->get('Password')->html();
	echo $fmd->get('Login')->html();
	echo $fmd->formClose();
	
	
	
	
	
?>