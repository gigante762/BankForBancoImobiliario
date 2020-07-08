<!DOCTYPE html>
<html>
<head>
	<title>Painel Bank</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php
		session_start();	
		//storing the name into a variable
		$name = $_SESSION['name'];

		//load data from json to memory
		$data = json_decode(file_get_contents('acao/data.json'),true);
		//get the current money
		$saldo = $data['users'][$name]
	?>

	<div>
		<a href="index.php" class="btn-danger " style="padding: 5px;"> < Sair </a>
	</div>
	
	<div class="menu">			
			<h5>Bem vindo(a), <span id='name'></span></h5>
			<h3 class='display-money-green'>R$ <tt id='money'></tt></h3>	
	</div>
	<?php
		if (isset($_POST['qtd']) && isset($_POST['name']))
		{
			$qtd = $_POST['qtd'];
			$para = ucfirst($_POST['name']);
			
			if ($saldo >= $qtd)
			{
				//remove money
				$data['users'][$_POST['name']] += $qtd;

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
				file_put_contents('acao/data.json', json_encode($data));

				//refresh the page to prevent reload and resend of request again.
				header("Refresh:0");
				
			}
			else
			{	//display an alert whose will be fadeOff by the id notification  
				echo "<div notification class='alert alert-danger' role='alert'>Saldo insuficiente!</div>";
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
			file_put_contents('acao/data.json', json_encode($data));

			//refresh the page to prevent reload and resend of request again.
			header("Refresh:0");

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
			file_put_contents('acao/data.json', json_encode($data));

			//refresh the page to prevent reload and resend of request again.
			header("Refresh:0");
		}
	?>

	<div class=" container">
		<div class="row">
			<div class="col-md mb-2">								
				<div class="card">
					<form action="painel.php" method = "post">
						<label>Transferir</label>
						<div class="input-group mb-2">
					        <div class="input-group-prepend">
					          <div class="input-group-text">R$</div>
					        </div>
					        <input type="number" name="qtd" class="form-control" id="inlineFormInputGroup" placeholder="Valor">
					    </div>
						<label>Para</label>
						<select id='transfer-to' name ='name' class="form-control">
							
						</select>
						<input type="submit" name="submit" value="Confirmar" class="btn btn-primary mt-2">	
					</form>
				</div>
			</div>

			<div class="col">
				<div class="card form-group ">
					<form action="painel.php" method = "post">
						<label>Banco:</label>
						<div class="input-group mb-2">
					        <div class="input-group-prepend">
					          <div class="input-group-text">R$</div>
					        </div>
					        <input type="number" name="qtd" class="form-control" id="inlineFormInputGroup" placeholder="Valor">
					    </div>
						<div class="bancoBTN mt-2">
							<input type="submit" name="sacar" value="Sacar" class="form-control btn btn-success">
							<input type="submit" name="pagar" value="Pagar" class="form-control btn btn-danger">
						</div>						
					</form>
				</div>
			</div>			
		</div>
	</div>
	

	

	<hr>
	<div class="container">
		<h6>Transações:</h6>
		<div id ='news'>
			
		</div>
	</div>
	
	

	<script type="text/javascript" src="jquery.js">
		
	</script>
	<script type="text/javascript">

		//get the name of current user
		let name = '<?php echo $name; ?>'

		//just for debug 
		let attnomes= false;

		let lastNew = ''
		
		$('#name').html(name)

		function attTransferTo(users)
		{
			$('#transfer-to').html('')	
			for (let user of Object.keys(users))
			{
				if (name == user)
					continue

				let txt = document.createElement("option"); 
 				txt.innerText = user;

				$('#transfer-to').append(txt)
			} 
			
		}

		function insertNews(newsA)
		{

			//$('#news').html('')
			let txt = ''
			for (let i = 0; i < 5 && i < newsA.length ; i++) {
				txt += '<p>'+newsA[i]+'</p>'
			}
			$('#news').html(txt)
		}

		function att(func)
		{
			 $.ajax({url: "acao/data.json", success: function(result){

			 		//data = result
			 		//debug
			 		//console.log(result.users)
    				    				
    				//update the news section
    				insertNews(result.news)

    				//not render unnecessarily
    				if ( lastNew == result.news[0])
    					return

    				lastNew = result.news[0]

    				// update and format the money 1.000.000
    				$('#money').html(result.users[name].toLocaleString('en').replace(/,/g,'.') )

    				//elemento html com o dinheiro
    				let elemoney = document.querySelector('.menu h3')

    				if (result.users[name] >= 0)
    					elemoney.className = 'display-money-green'
    				else    				
    					elemoney.className = 'display-money-red'

    				//udate the transfer-to class
    				//attTransferTo(result.users) 
    				if (!attnomes)
    					attTransferTo(result.users)	
    					attnomes = true;		
  			}});

		}

		// hide the notification after some time
		window.setTimeout(function(){
            $('[notification]').fadeOut('slow')
            $( "[notification]" ).remove();
        }, 3000);
				
		//update at begin 
		att()

		//set the refresh time
		setInterval(att,3000)
	</script>
</body>
</html>
