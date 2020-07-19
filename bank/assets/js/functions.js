

function attTransferTo(users) {
    let ele = document.getElementById('transfer-to')
    ele.innerHTML = '' 
    for (let user of users) {
        
        let txt = document.createElement("option");
        txt.innerText = user;

        ele.appendChild(txt)        
    }
    attFetch()
}

function insertNews(newsA) {

    //$('#news').html('')
    let txt = ''
    for (let i = 0;  i < newsA.length; i++) {
        txt += '<p>' + newsA[i] + '</p>'
    }
    document.getElementById('news').innerHTML = txt
   
}

function attFetch(){
    //http://localhost/BankForBancoImobiliario/bank/
    let url = 'acao/requests.php?get='+name
    return fetch(url)
    .then(response => response.json())
    .then(result => {
         insertNews(result.news)

            //not render unnecessarily
            if (lastNew == result.news[0])
                return

            lastNew = result.news[0]

            // update and format the money 1.000.000
            document.getElementById('money').innerHTML = result.cash.toLocaleString('en').replace(/,/g, '.')

            currentmoney = result.cash

            //elemento html com o dinheiro
            let elemoney = document.querySelector('.menu h3')

            if (result.cash >= 0)
                elemoney.className = 'display-money-green'
            else
                elemoney.className = 'display-money-red'

            //udate the transfer-to class            
            if (!attnomes)
                attTransferTo(result.users)
            attnomes = true;

    })
          
}

function pagarBanco() {
    let qtd = Number( document.getElementById('bancoValor').value )
    if (qtd == 0)
        return
    //always positive value
    qtd = Math.abs(qtd)   
    document.getElementById('bancoValor').value = '' 
    
    let data = new FormData();
   
    data.append('pagar','true')        
    data.append('qtd',qtd)        
    data.append('name',name)        

    return fetch("acao/requests.php",
     {  method:'POST',
        body:data
     })
    
    //console.log(qtd)
    //att()
}

function sacarBanco() {
    let qtd = Number(document.getElementById('bancoValor').value)
    if (qtd == 0)
        return
    //always positive value
    qtd = Math.abs(qtd)
    document.getElementById('bancoValor').value = ''

    let data = new FormData();
   
    data.append('sacar','true')        
    data.append('qtd',qtd)        
    data.append('name',name)        

    return fetch("acao/requests.php",
     {  method:'POST',
        body:data
     })
    
}

function gerarNotification(msg) {
    let txt = document.createElement("div");
    txt.innerHTML = "<div notification class='alert alert-danger' role='alert'>" + msg + "</div>"
    document.getElementById('notifications').appendChild(txt)

    window.setTimeout(function() {
        document.getElementById('notifications').remove()        
    }, 3000);

}

function transferir() {
    let qtd = Number( document.getElementById('moneyTransfer').value )
    if (qtd == 0)
        return
    //always positive value
    qtd = Math.abs(qtd)
    
    document.getElementById('moneyTransfer').value = '' 
    
    let to = document.getElementById('transfer-to').value
    
    let data = new FormData();
   
    data.append('transferir','true')        
    data.append('qtd',qtd)        
    data.append('name',name)        
    data.append('to',to)        

    return fetch("acao/requests.php",
     {  method:'POST',
        body:data
     })
}
