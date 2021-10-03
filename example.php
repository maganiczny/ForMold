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

	//Example 1, simple login form
	
	$fmd = new \ForMold\ForMold ('./login.php');

	$fmd->add('Login')->required();
	$fmd->add('Password')->required();
	$fmd->add('Remember|checkbox')->checked();
	$fmd->submit('Login');
	
	echo htmlentities($fmd->html());
	echo $fmd->html();
	
	
	
	//Example 2, form to add comment

	$fmd = new \ForMold\ForMold ('./comment.php');
	
	$fmd->add('Name',['required',true]);
	$fmd->add('Email')->required(); //required() works same as ,['required'=>true]
	$fmd->add('Comment|textarea');
	$fmd->submit('Add');
	
	echo htmlentities($fmd->html());
	echo $fmd->html();
	
	
	
	//Example 3, simple settings form with labels
	
	$fmd = new \ForMold\ForMold (['action'=>'./action.php','label'=>true]);
	
	$fmd->add('SiteName');
	
	echo htmlentities($fmd->html());
	echo $fmd->html();
	
?>