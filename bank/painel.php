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
	<div>
		<a href="index.php" class="btn-danger " style="padding: 5px;"> < Sair </a>
	</div>
	
	<div class="menu">			
			<h5>Bem vindo(a), <span id='name'></span></h5>
			<h3 class='display-money-green'>R$ <tt id='money'></tt></h3>	
	</div>
	<div id ='notifications'>
		
	</div>
	
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
					        <input type="number" id ='moneyTransfer' name="qtd" class="form-control" id="inlineFormInputGroup" placeholder="Valor" required>
					    </div>
						<label>Para</label>
						<select id='transfer-to' name ='name' class="form-control">
							
						</select>
						<input type="submit" id ='transferirBtn'name="submit" value="Confirmar" class="btn btn-primary mt-2">	
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
					        <input type="number" name="qtd" class="form-control" id="bancoValor" placeholder="Valor" required>
					    </div>
						<div class="bancoBTN mt-2">
							<input type="submit" id = 'sacarBtn' name="sacar" value="Sacar" class="form-control btn btn-success">
							<input type="submit" id ='pagarBtn'name="pagar" value="Pagar" class="form-control btn btn-danger">
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
	
	

	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="functions.js"></script>
	<script type="text/javascript">

		//get the name of current user
		let name = localStorage.getItem('name')

		//just for debug 
		let attnomes= false;
		let currentmoney = 10

		let lastNew = ''
		
		$('#name').html(name.charAt(0).toUpperCase() + name.slice(1))

		// hide the notification after some time
		/*
		window.setTimeout(function(){
            $('[notification]').fadeOut('slow')
            $( "[notification]" ).remove();
        }, 3000);
        */
				

		$('#transferirBtn').on('click', function(ev){
			ev.preventDefault()
			transferir()
		})
		$('#pagarBtn').on('click', function(ev){
			ev.preventDefault()
			pagarBanco()
		})
		$('#sacarBtn').on('click', function(ev){
			ev.preventDefault()
			sacarBanco()
		})
		$('#moneyTransfer').on('change', function(ev){
			//ev.preventDefault()
			//sacarBanco()
			let qtd = Number($('#moneyTransfer').val())
			if (qtd > currentmoney)
			{
				moneyTransfer.className = 'form-control border-danger'
				gerarNotification('Saldo insuficiente.')
			}
			else
				moneyTransfer.classList.remove('border-danger')	
		})
		
			
		//update at begin 
		att()

		//set the refresh time
		setInterval(att,3000)
	</script>
</body>
</html>
