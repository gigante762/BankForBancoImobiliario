<!DOCTYPE html>
<html>
<head>
	<title>Loggin Banco Virtual</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/index-style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
	<div class="form-index">
		<h4>Logar</h4>	
		<div class="form-group">
			<form>
				Escolha a conta: 
				<select name ='name' id ='select' class="form-control">

				<?php require '../vendor/autoload.php';
						$users = (new App\AccountModel)->getAllUsers();

						foreach ($users as $user): ?>
							<option value='<?= $user['name'] ?>'>
							 	<?= $user['name'] ?>
							 </option>;
						<?php endforeach; ?>

				</select>
				<a href="painel.php" class="form-control btn btn-info mt-2">Entrar</a>		
			</form>
		</div>
		
		<hr>

		<h4>Cadastrar</h4>
		<form action="router.php?route=cadastrar" method = "post">
			<div class="form-group">
				<label>Coloque o seu nome: </label>
				<input type="text" name="name" autocomplete="off" class="form-control" placeholder="lucas">
			</div>
			<div class="form-group">
				<label>Coloque a quantidade de dinheiro: </label>	
				<input type="number" name="money" class="form-control" placeholder='1000'>
				<input type="submit" name="submit" value="Cadastrar" class="form-control btn btn-success mt-2">	
			</div>
		</form>
		<form action="router.php?route=reset" method="post">
				<button class="btn btn-sm btn-warning mt-5">Resetar</button>
				<small class="text-muted form-text">Clique aqui para apagar todos os usuários</small>					
		</form>
	</div>

	
	<script type="text/javascript">
		let ele = document.getElementById('select')
		let name = document.getElementById('select').value

		//set the localstore to the name of current user 
		ele.onchange = function () {
			let name = ele.value
			localStorage.setItem('name',name)
			//console.log(name)
		}
		localStorage.setItem('name',name)
		//console.log(name)
		
	</script>
</body>
</html>
