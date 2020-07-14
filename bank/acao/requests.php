<?php
		
		//storing the name into a variable
		
		if (isset($_GET['get']))
		{
			echo file_get_contents('data.json');
			exit();
		}

		$name = $_POST['name'];

		$data = json_decode(file_get_contents('data.json'),true);

		$saldo = $data['users'][$name];

		if (isset($_POST['transferir']))
		{
			$qtd = $_POST['qtd'];
			$para = ucfirst($_POST['to']);
			
			if ($saldo >= $qtd)
			{
				//remove money
				$data['users'][$_POST['to']] += $qtd;

				//transfer money to other acconunt 
				$data['users'][$name] -= $qtd; 

				//formar a number like a dot style 1.000.000 
				$qtd = number_format($_POST['qtd'], 0, '', '.');
				
				$d = date('H:i:s');

				//add a news message
				$nameF =  ucfirst($name);
				$msg = "$d - <b>$nameF</b> transferiu <spam  class='text-warning '>R\$$qtd</spam> para <b>$para</b>";
				array_unshift($data['news'], $msg);

				//this line keep just six messages in the json file 
				if (count($data['news']) > 6)
					array_pop($data['news']);

				//save json file modifided
				file_put_contents('data.json', json_encode($data));
				
				header('Content-Type: application/json');
				echo '{status:success}';

				//refresh the page to prevent reload and resend of request again.
				//header("Refresh:0");
				
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
			$data['users'][$name] += $_POST['qtd'];

			//format to show as message 
			$qtd = number_format($_POST['qtd'], 0, '', '.');
						
			$d = date("H:i:s");
			
			//insert the message
			$nameF =  ucfirst($name);
			$msg =  "$d - <b>$nameF</b> sacou <spam style='color: green;'>R\$$qtd</spam> do <spam class='text-info'><b>Banco</b></spam>";
			array_unshift($data['news'],$msg);

			//save json file
			file_put_contents('data.json', json_encode($data));

			//refresh the page to prevent reload and resend of request again.
			//header("Refresh:0");

		}
		//process the pagar operation
		else if (isset($_POST['pagar']))
		{
			//subtract the money to bank
			$data['users'][$name] -= $_POST['qtd'];

			//format to show as message 
			$qtd = number_format($_POST['qtd'], 0, '', '.');
						
			$d = date("H:i:s");

			//insert a new message
			$nameF = ucfirst($name);
			$msg = "$d - <b>$nameF</b> pagou <spam style='color: red;'>R\$$qtd</spam> para o <spam class = 'text-info'><b>Banco</b></spam>";
			array_unshift($data['news'], $msg);
			
			//save json file
			file_put_contents('data.json', json_encode($data));

			//refresh the page to prevent reload and resend of request again.
			//header("Refresh:0");
		}
	?>