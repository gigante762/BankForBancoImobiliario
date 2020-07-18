
<?php
	$db = new PDO('sqlite:sqlite.db');
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
	//storing the name into a variable
		
	if (isset($_GET['get']))
	{
		$data = array();

		//set header
		header('Content-Type: application/json');

		//get users 
		$res = $db->query('select name, cash from users');
		$users = $res->fetchAll();

		//this loop build this datastucture : 'name': 100, 'anotername': 120 
		foreach ($users as $valor) {
			$v =  $valor['cash'];
			$data['users'][$valor[0]] = (int) $v;
		} 

		//get news
		$res = $db->query('select msg from news order by id desc limit 5');
		$res = $res->fetchAll();
		
		$data['news'] = array();
		
		//insert the news 
		foreach ($res as $valor) {
			array_push($data['news'],$valor[0]);
		} 

		echo json_encode( $data ) ;
		
		exit();
	}

	$name = $_POST['name'];

	$sql = 	"select cash from users where name = '$name'";
	
	$res = $db->query($sql);
	
	$saldo = (int) $res->fetch()[0];

	if (isset($_POST['transferir']))
	{
		$qtd = $_POST['qtd'];
		$para = ucfirst($_POST['to']);

		$paraf=$_POST['to'];
			
		if ($saldo >= $qtd)
		{

			//Add money in your account
			$sql1 = "UPDATE users set cash = (select cash + $qtd from users where name = '$paraf')
					where name  = '$paraf'";
			$db->query($sql1);
			
			//subtract the money to bank
			$saldo -= $_POST['qtd'];
			$sql2 = "update users set cash = $saldo where name = '$name';";
			$db->query($sql2);

			//formar a number like a dot style 1.000.000 
			$qtd = number_format($_POST['qtd'], 0, '', '.');
				
			$d = date('H:i:s');

			//add a news message
			$nameF =  ucfirst($name);
			$msg = "$d - <b>$nameF</b> transferiu <spam  class='text-warning '>R\$$qtd</spam> para <b>$para</b>";
			$db->query("insert into news (msg) values (\"$msg\")");
				
			header('Content-Type: application/json');
			echo '{status:success}';
				
		}
		else
		{	//display an alert whose will be fadeOff by the id notification
			header('Content-Type: application/json');
			echo '{status:fail}';	  
			//echo "<div notification class='alert alert-danger' role='alert'>Saldo insuficiente!</div>";
		}
	}

	//process the sacar operation 
	if (isset($_POST['sacar']))
	{	
		//give the money from bank
		$saldo += $_POST['qtd'];

		//update in database 
		$db->query("update users set cash = $saldo where name = '$name'");

		//format to show as message 
		$qtd = number_format($_POST['qtd'], 0, '', '.');
						
					
		//insert the message
		$nameF =  ucfirst($name);
		$d = date('h:i:s');

		$msg =  "$d - <b>$nameF</b> sacou <spam style='color: green;'>R\$$qtd</spam> do <spam class='text-info'><b>Banco</b></spam>";
		
		//echo "insert into news (msg) values ('$msg')";
		$db->query("insert into news (msg) values (\"$msg\")");
		
	}
	//process the pagar operation
	else if (isset($_POST['pagar']))
	{
		//subtract the money to bank
		$saldo -= $_POST['qtd'];

		//update in database 
		$db->query("update users set cash = $saldo where name = '$name'");

		//format to show as message 
		$qtd = number_format($_POST['qtd'], 0, '', '.');
		$d = date("H:i:s");

		//insert a new message
		$nameF = ucfirst($name);
		$msg = "$d - <b>$nameF</b> pagou <spam style='color: red;'>R\$$qtd</spam> para o <spam class = 'text-info'><b>Banco</b></spam>";

		//query to database
		$db->query("insert into news (msg) values (\"$msg\")");
	}
?>