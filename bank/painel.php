<!DOCTYPE html>
<html>

<head>
	<title>Painel Bank</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/painel-style.css">
</head>

<body>
	<?php require_once '../vendor/autoload.php'; ?>
	<div>
		<a href="index.php" class="btn-danger p-1" >
			< Sair</a>
	</div>

	<div class="menu mb-2">
		<h5>Bem vindo(a), <span id='name'></span></h5>
		<h3 class='display-money-green'><small id='money'></small></h3>
	</div>
	<div id='notifications'>

	</div>

	<div class=" container">
		<div class="row">
			<div class="col-md mb-2">
				<div class="card p-3">
					<form action="painel.php" method="post">
						<label>Transferir</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text">R$</div>
							</div>
							<input type="number" id='moneyTransfer' name="qtd" class="form-control" id="inlineFormInputGroup" placeholder="Valor">
						</div>
						<label>Para</label>
						<select id='transfer-to' name='name' class="form-control">

						</select>
						<input type="submit" id='transferirBtn' name="submit" value="Confirmar" class="btn btn-primary mt-2">
					</form>
				</div>
			</div>

			<div class="col">
				<div class="card form-group p-3">
					<div>
						<label>Banco:</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text">R$</div>
							</div>
							<input type="number" name="qtd" class="form-control" id="bancoValor" placeholder="Valor" required>
						</div>
						<div class="bancoBTN mt-2">
							<input type="submit" id='sacarBtn' name="sacar" value="Sacar" class="form-control btn btn-success">
							<input type="submit" id='pagarBtn' name="pagar" value="Pagar" class="form-control btn btn-danger">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<hr>
	<div class="container">
		<h6>Transações:</h6>
		<div id='news'>

		</div>
	</div>



	<script type="text/javascript" src="assets/js/functions.js"></script>
	<script type="text/javascript">
		let conn = new WebSocket('ws://<?= BASEURL ?>:8080');
		conn.onopen = function(e) {
			console.log("Connection established!");
			//conn.send(JSON.stringify({name,type:"connected"}));
		};

		conn.onmessage = function(e) {
			updateMyMoney();
			updateNews();
		};


		// get the name of current user
		const name = localStorage.getItem('name')
		let currentMoney = '';

		document.getElementById('name').innerHTML = name;

		/* Transferir para outro usuário */
		document.getElementById('transferirBtn').addEventListener('click', (e) => {
			e.preventDefault();
			let qtd = Number(document.getElementById('moneyTransfer').value)
			if (qtd == 0)
				return
			//always positive value
			qtd = Math.abs(qtd)

			document.getElementById('moneyTransfer').value = ''

			let to = document.getElementById('transfer-to').value

			conn.send(JSON.stringify({
				name,
				type: "tranfer",
				msg: to+'|'+qtd
			}));
		});

		/* Pagar para o banco */
		document.getElementById('pagarBtn').addEventListener('click', () => {

			let qtd = Number(document.getElementById('bancoValor').value)
			if (qtd == 0)
				return
			//always positive value
			qtd = Math.abs(qtd)
			document.getElementById('bancoValor').value = ''

			console.log('Pagar o banco:' + qtd);
			conn.send(JSON.stringify({
				name,
				type: "payBank",
				msg: qtd
			}));
		});

		/* Sacar banco */
		document.getElementById('sacarBtn').addEventListener('click', () => {
			let qtd = Number(document.getElementById('bancoValor').value)
			if (qtd == 0)
				return
			//always positive value
			qtd = Math.abs(qtd)
			document.getElementById('bancoValor').value = ''

			console.log('Sacar do banco:' + qtd);
			conn.send(JSON.stringify({
				name,
				type: "receiveBank",
				msg: qtd
			}));
		});

		document.getElementById('moneyTransfer').addEventListener('change',(e) =>{
			
			let transferirBtn = document.getElementById('transferirBtn');

			let qtd = Number(document.getElementById('moneyTransfer').value)
			if (qtd > currentMoney) {
				moneyTransfer.className = 'form-control border-danger'
				//gerarNotification('Saldo insuficiente.')
				transferirBtn.disabled = true;
			} else{
				
				moneyTransfer.classList.remove('border-danger')
				transferirBtn.disabled = false;
			}
		})
	
		/* Get Money when user enter at the application */
		updateMyMoney()

		/* Update the news */
		updateNews();

		/* filter all user to transfer money */
		let otherUsers = <?php require '../vendor/autoload.php';
							echo json_encode($users = (new App\AccountModel)->getAllUsers()); ?>;
		otherUsers = otherUsers.filter((user) => user.name != name)
		let objSelect = document.getElementById('transfer-to')
		otherUsers.forEach((user) => {
			let option = document.createElement('option');
			option.value = user.name
			option.label = user.name
			objSelect.appendChild(option)
		})
	</script>
</body>

</html>