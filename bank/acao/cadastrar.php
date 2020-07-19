<?php


if (isset($_POST['name'])  && isset($_POST['money']))
{
	$db = new PDO('sqlite:sqlite.db');
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
	//apaga todos os dados, users e news 
	if ($_POST['name'] == 'reset')
	{
		$db->query("DELETE from users");
		$db->query("DELETE from news");
	}
	//cria um novo usuario.
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