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

	use \ForMold\ForMold as ForMold;
	use \ForMold\Validate as Validate;

	//Example 1, simple login form
	
	echo '<h4>Example 1</h4>';
	$fmd = new ForMold ('./login.php');

	$fmd->add('Login')->required()->placeholder('Email');
	$fmd->add('Password')->required()->label('Hasło')->value('treść');
	$fmd->add('Remember|checkbox')->checked();
	$fmd->submit('Login');
	
	echo htmlentities($fmd->html());
	echo '<br /><br />';
	echo $fmd->html();
	
	
	
	//Example 2, form to add comment

	echo '<h4>Example 2</h4>';
	$fmd = new ForMold ('./comment.php');
	
	$fmd->add('Name',['required',true]);
	$fmd->add('Email')->required(); //required() works same as ,['required'=>true]
	$fmd->add('Comment|textarea');
	$fmd->add('Comment|nonexistent');
	$fmd->submit('Add');
	
	echo htmlentities($fmd->html());
	echo '<br /><br />';
	echo $fmd->html();
	
	
	
	//Example 3, simple settings form with labels
	
	echo '<h4>Example 3</h4>';
	$fmd = new ForMold (['action'=>'./action.php','label'=>true]);
	
	$fmd->add('SiteName');
	
	echo htmlentities($fmd->html());
	echo '<br /><br />';
	echo $fmd->html();
	
	
	
	//Example 4, simple login form but You can get each element
	
	echo '<h4>Example 4</h4>';
	$fmd = new ForMold ('./login.php');

	$fmd->add('Login')->required()->placeholder('Email');
	$fmd->add('Password')->required();
	$fmd->add('Remember|checkbox')->checked();
	$fmd->submit('Login');
	
	echo htmlentities($fmd->formOpen());
	echo htmlentities($fmd->get('Remember')->html());
	echo htmlentities($fmd->get('Password')->html());
	echo htmlentities($fmd->get('Login')->html());
	echo htmlentities($fmd->formClose());
	echo '<br /><br />';
	echo $fmd->formOpen();
	echo $fmd->get('Remember')->html();
	echo $fmd->get('Password')->html();
	echo $fmd->get('Login')->html();
	echo $fmd->formClose();
	
	
	
	//Example 5, simple login with value for fast test
	
	echo '<h4>Example 5</h4>';
	$fmd = new ForMold (['method'=>'get']);
	
	$fmd->add(['email'=>'email, Email'])->value('user@example.com')->required();
	$fmd->add(['password'=>'pass, Hasło'])->value('test')->required();
	
	$fmd->add(['checkbox'=>'remember, Zapamiętaj'])->checked();
	
	$fmd->add(['type'=>'submit'])->value('Zaloguj')->checked();
	
	echo htmlentities($fmd->html());
	echo '<br /><br />';
	echo $fmd->html();
	
	$fmdValidate = new Validate();
	
?>