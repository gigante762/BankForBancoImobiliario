<?php
session_start();
if (isset($_POST['name'])  && isset($_POST['money']))
{
	$name =  $_POST['name'];
	//load data in the memory 
	$data = json_decode(file_get_contents('data.json'),true);

	//create new users
	$data['users'][$name] = (int) $_POST['money'];
	
	//insert into array of users
	//array_push($data['users'], $user);
	
	//check the reset command
	if ($_POST['name'] == 'reset')
	{
		$data['users'] = array();
		$data['news'] = array();  
	}

	//save data file  
	file_put_contents('data.json', json_encode($data));

	//redirect to home page
	header('location: ../index.php'); 
}
?>