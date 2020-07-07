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
		$name = $_SESSION['name'];
		$data = json_decode(file_get_contents('acao/data.json'),true);

		$saldo = $data['users'][$name]

	?>

	<div>
		<a href="index.php" class="btn-danger " style="
    padding: 5px;"> < Sair </a>
	</div>
	<div class="menu">			
			<h5>Bem vindo(a), <?php echo ucfirst($name) ?></h5>
			<h3 style= 'color: green;'>R$ <tt id='money'></tt></h3>	
	</div>
	<?php
		if (isset($_POST['qtd']) && isset($_POST['name']))
		{
			$qtd = $_POST['qtd'];
			$para = ucfirst($_POST['name']);
			
			//echo "Transferencia solicitada para {$para} no valor de R$ {$qtd}.";

			if ($saldo >= $qtd)
			{
				$data['users'][$_POST['name']] += $qtd;
				$data['users'][$name] -= $qtd;
				$qtd = number_format($_POST['qtd'], 0, '', '.');
				
				$d = date('H:i:s');
				array_unshift($data['news'], "$d - <b>$name</b> transferiu <spam  class='text-warning '>R\$$qtd</spam> para <b>$para</b>");
				if (count($data['news']) > 6)
					array_pop($data['news']);
				
				file_put_contents('acao/data.json', json_encode($data));
				header("Refresh:0");
				//header('location: painel.php');
			}
			else
			{
				echo "<div id = 'notificacao'class='alert alert-danger' role='alert'>Saldo insuficiente!</div>";
			}
		}

		if (isset($_POST['sacar']))
		{
			$qtd = number_format($_POST['qtd'], 0, '', '.');
			//echo('sacar');
			$data['users'][$name] += $qtd;
			$d = date("H:i:s");
			array_unshift($data['news'], "$d - <b>$name</b> sacou <spam style='color: green;'>R\$$qtd</spam> do <spam class='text-info'><b>Banco</b></spam>");
			file_put_contents('acao/data.json', json_encode($data));
			header("Refresh:0");

		}
		else if (isset($_POST['pagar']))
		{
			$qtd = number_format($_POST['qtd'], 0, '', '.');
			//echo('pagar');
			$data['users'][$name] -= $qtd;
			$d = date("H:i:s");
			array_unshift($data['news'], "$d - <b>$name</b> pagou <spam style='color: red;'>R\$$qtd</spam> para o <spam class = 'text-info'><b>Banco</b></spam>");
			file_put_contents('acao/data.json', json_encode($data));
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
						<select name ='name' class="form-control">
							<?php
								foreach ($data['users'] as $key => $value) {
									if ($name != $key) {
										echo  "<option value='$key'>".$key."</option>";
									}
								} 
							?>
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

		let name = '<?php echo $name; ?>'

		function insertNews(newsA)
		{

			//$('#news').html('')
			let txt = ''
			for (let i = 0; i < 5 && i < newsA.length ; i++) {
				txt += '<p>'+newsA[i]+'</p>'
			}
			$('#news').html(txt)
		}
		function att()
		{
			 $.ajax({url: "acao/data.json", success: function(result){
    				//$("#div1").html(result);
    				//console.log(result)
    				insertNews(result.news)
    				$('#money').html(result.users[name].toLocaleString('en').replace(/,/g,'.') )

  			}});
		}
		window.setTimeout(function(){
            $('#notificacao').fadeOut('slow')
        }, 3000);
				
		att()
		setInterval(att,3000)
	</script>
</body>
</html>
