
<?php
	$db = new PDO('sqlite:sqlite.db');
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
	function getInfo(){
		global $db;

		$name = $_GET['get'];
		$data = array();

		//set header
		header('Content-Type: application/json');

		//get cash 
		$res = $db->query("select cash from users where name = '$name'");
		$user = (int) $res->fetch()[0];
		$data['cash'] = $user;

		//get users
		$res = $db->query("select name from users where name != '$name'");
		$users = $res->fetchAll(PDO::FETCH_COLUMN, 0);
		$data['users'] = $users;

		//get news
		$res = $db->query('select msg from news order by id desc limit 5');
		$res = $res->fetchAll();
		
		$data['news'] = array();
		
		//insert the news 
		foreach ($res as $valor) {
			array_push($data['news'], $valor[0]);
		} 

		echo json_encode( $data ) ;
	}

	function getSaldo(){
		global $db;
		// ######### GET SOME USEFUL VARIABLES ############
		$name = $_POST['name'];

		$sql = 	"select cash from users where name = '$name'";
		$res = $db->query($sql);
		$saldo = (int) $res->fetch()[0];

		return array('name' => $name, 'saldo' => $saldo);

		// ######### ------------------------- ############
	}

	function sacar(){
		global $db;
		$data = getSaldo();
		$name = $data['name'];
		$saldo = $data['saldo'];

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

	function pagar(){
		global $db;
		$data = getSaldo();
		$name = $data['name'];
		$saldo = $data['saldo'];

		//pay money to the bank
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

	function transferir(){
		global $db;
		$data = getSaldo();
		$name = $data['name'];
		$saldo = $data['saldo'];		

		$qtd = $_POST['qtd'];
		$para = ucfirst($_POST['to']);

		$paraf=$_POST['to'];
			
		if ($saldo >= $qtd)
		{

			//Add money in another user account
			$sql1 = "UPDATE users set cash = (select cash + $qtd from users where name = '$paraf')
					where name  = '$paraf'";
			$db->query($sql1);
			
			//subtract the money from bank
			$saldo -= $_POST['qtd'];
			$sql2 = "update users set cash = $saldo where name = '$name';";
			$db->query($sql2);

			//formar a number like a dot style 1.000.000 
			$qtd = number_format($_POST['qtd'], 0, '', '.');
			
			//date like 11:22:11
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

	//main
	if (isset($_GET['get']))
		getInfo();
		
	//process the sacar operation 
	if (isset($_POST['sacar']))
		sacar();

	//process the pagar operation
	else if(isset($_POST['pagar'])) 
		pagar();

	else if (isset($_POST['transferir']))
		transferir();
				
	
?>