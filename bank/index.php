<!DOCTYPE html>
<html>
<head>
	<title>Loggin Banco Virtual</title>
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

	<h4>Logar</h4>	
	<form action="acao/logar.php" method = "post">
		Escolha a conta: 
		<select name ='name'>
			<?php
				$data = json_decode(file_get_contents('acao/data.json'),true);
				//var_dump($data['users']);

				foreach ($data['users'] as $key => $value) {
					echo  "<option value='$key'>".$key."</option>"	;
				} 
				

			?>
		</select>
		<input type="submit" name="submit" value="Entrar">	
	</form>

	<h4>Cadastrar</h4>
	<form action="acao/cadastrar.php" method = "post">
		<label>Coloque o seu nome: </label>
		<p>Ou coloque <i>reset</i> no nome para apagar todos os usuários. </p>	
		<input type="text" name="name" autocomplete="off">
		<label>Coloque a quantidade de dinheiro: </label>	
		<input type="number" name="money">
		<input type="submit" name="submit" value="Cadastrar">	
	</form>
</body>
</html>