<?php


if (isset($_POST['name'])  && isset($_POST['money']))
{
	$db = new PDO('sqlite:sqlite.db');
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	if ($_POST['name'] == 'reset')
	{
		$db->query("DELETE from users");
		$db->query("DELETE from news");
		  
	}
	else
	{
		$name =  $_POST['name'];
		$money =  $_POST['money'];

		$db->query("insert into users (name, cash) values('$name','$money')");
	}
	//redirect to home page
	header('location: ../index.php'); 
}

?>