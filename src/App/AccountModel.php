<?php

namespace App;

class AccountModel {
    private $conn;

    public function __construct()
    {
        $this->conn = new \PDO('sqlite:'.DATABASEPATH); 
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    }

    public function getAllUsers(): array
    {
        return  $this->conn->query('select name from users')->fetchAll();
    }

    public function getAllUsersWithCash(): array
    {
        return  $this->conn->query('select name,cash from users')->fetchAll();
    }

    public function getNews(): array
    {
        $res = $this->conn->query('select msg from news order by id desc limit 15');
		return $res->fetchAll();
    }

    public function createUser($name, $initialMoney)
    {
        $this->conn->query("insert into users (name, cash) values('$name','$initialMoney')");
    }

    public function reset()
    {
        $this->conn->query("DELETE from users");
		$this->conn->query("DELETE from news");
    }

    public function find($name)
    {
        return $this->conn->query("select name,cash from users where name = '$name'")->fetch();
    }

    public function pagarBanco($name,  $valor)
    {
        $currentCash = (int) $this->find($name)['cash'];
        $updatedCash = $currentCash - (int) $valor;
        
        $this->conn->query("update users set cash = {$updatedCash} where name = '{$name}'");


        //format to show as message 
		$qtd = number_format($valor, 0, '', '.');
		$d = date("H:i:s");

		//insert a new message
		$nameF = ucfirst($name);
		$msg = "$d - <b>$nameF</b> pagou <spam style='color: red;'>R\$$qtd</spam> para o <spam class = 'text-info'><b>Banco</b></spam>";

		//query to database
		$this->conn->query("insert into news (msg) values (\"$msg\")");

    }

    public function receberBanco($name,  $valor)
    {
        $currentCash = (int) $this->find($name)['cash'];
        $updatedCash = $currentCash + (int) $valor;
        
        $this->conn->query("update users set cash = {$updatedCash} where name = '{$name}'");


        //format to show as message 
		$qtd = number_format($valor, 0, '', '.');
		$d = date("H:i:s");

		//insert a new message
		$nameF = ucfirst($name);
		$msg =  "$d - <b>$nameF</b> sacou <spam style='color: green;'>R\$$qtd</spam> do <spam class='text-info'><b>Banco</b></spam>";

		//query to database
		$this->conn->query("insert into news (msg) values (\"$msg\")");

    }

    public function transferir($name,  $msg)
    {
        $data = explode('|',$msg);
        $to = $data[0];
        $qtd = (int) $data[1];

        $para = ucfirst($to);


        $currentCash = (int) $this->find($name)['cash'];
        if ($currentCash >= $qtd)
        {
            //Add money in another user account
            $sql1 = "UPDATE users set cash = (select cash + $qtd from users where name = '$to')
            where name  = '$to'";
            $this->conn->query($sql1);

            //subtract the money from account
			$saldo = $currentCash - $qtd;
			$sql2 = "update users set cash = $saldo where name = '$name';";
			$this->conn->query($sql2);

            //formar a number like a dot style 1.000.000 
			$qtd = number_format($qtd, 0, '', '.');
			
			//date like 11:22:11
			$d = date('H:i:s');

			//add a news message
			$nameF =  ucfirst($name);
			$msg = "$d - <b>$nameF</b> transferiu <spam  class='text-warning '>R\${$qtd}</spam> para <b>$para</b>";
			$this->conn->query("insert into news (msg) values (\"$msg\")");
				
			/* header('Content-Type: application/json');
			echo '{status:success}'; */
        }
        else
		{	//display an alert whose will be fadeOff by the id notification
			/* header('Content-Type: application/json');
			echo '{status:fail}'; */	  
			//echo "<div notification class='alert alert-danger' role='alert'>Saldo insuficiente!</div>";
		}
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}