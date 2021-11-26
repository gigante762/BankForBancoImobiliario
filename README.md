# BankForBancoImobiliario
Um sistema para banco imobiliário. 

<p>Esse foi um sistema que criei para jogar banco imobiliário com a minha família.
Dentro da pasta bank há imagens mostrando como ficou o projetinho.</p>

<p>Na tela inicial, você coloca o nome dos jogadores e também o saldo inicial. Você pode acrescentar quantos jogadores desejar.
Caso queira resetar o jogo, basta ir onde cadastra outros jogadores e digitar <i>reset</i>  </p>

## Link da história
[Confira aqui](https://mundozeroum.blogspot.com/2021/11/projeto-bankforbancoimobiliario-banco.html)

## Como usar 
Baixe o projeto com `git clone https://github.com/gigante762/BankForBancoImobiliario.git`

vá até a pasta `cd BankForBancoImobiliario`

Baixe as dependencias do composer com `composer update`

Basta inciar o servidor do banco com o comando `php server.php`

Depois inicie o servidor web, por exemplo vá até a pasta bank e dê o seguinte comando `php -S localhost:8000`

Acesse o local do projeto no seu navegaro _baseurl_/bank,  o endereço localhost:8000.

Para outras pessoas se conectarem elas precisam acessar o ip do computardor que está servindo a aplicação web. Uma forma de fazer isso é no Windows abrir cmd.exe e digitar
`ipconfig` e usar o Endereço IPV4: Algo como 192.168.0.XXX.
Assim é só as pessoas colocarem esse endereço na barra do navegador: 192.168.0.XXX:8000.

~~Defina a baseurl em _src/app/config.php_~~

Crie os seus usuários e depois só cada um recarregar a página incial e então escolher a sua conta

# Para Devs
* O banco de dados do jogo é bem simles, eu usei um arquivo sqlite.db (_SQLite_) para guardar todos os dados.

Caso perca o schema use o código sql
```
    CREATE TABLE news(id integer PRIMARY KEY, msg text, hora datetime DEFAULT 'CURRENT_TIME');
    CREATE TABLE users (name varchar(200) PRIMARY KEY, cash bigint);
```

O jogo *não possui* tratamentos contra SQLinjection. 

Sinta-se a vontade para fazer as modificações que desejar.

#Tenham um bom jogo!
igantekevin@hotmail.com


