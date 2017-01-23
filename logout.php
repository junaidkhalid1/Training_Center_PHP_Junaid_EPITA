<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 12/28/2016
 * Time: 09:12 AM
 */

session_start();
require_once 'class.user.php';
$user = new USER();

if(!$user->is_logged_in())
{
	$user->redirect('index.php');
}

if($user->is_logged_in()!="")
{
	$user->logout();
	$user->redirect('index.php');
}
?>