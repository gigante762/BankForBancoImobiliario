

function gerarNotification(msg) {
    let txt = document.createElement("div");
    txt.innerHTML = "<div notification class='alert alert-danger' role='alert'>" + msg + "</div>"
    document.getElementById('notifications').appendChild(txt)

    window.setTimeout(function() {
        document.getElementById('notifications').remove()        
    }, 3000);
}


function updateNews()
{
    let news = document.getElementById('news')
    news.innerHTML = ''
    fetch('router.php?route=getnews' )
    .then(response => response.json())
    .then(result => {
        //console.log(result)
        result.forEach((singleNew)=>{
            let p = document.createElement('p')
            p.innerHTML = singleNew.msg
            news.appendChild(p)
        })
        //document.getElementById('money').innerHTML = result.cash.toLocaleString('en').replace(/,/g, '.')
    })
}

function updateMyMoney()
{
    fetch('router.php?route=find&name=' + name)
    .then(response => response.json())
    .then(result => {
        //console.log(result)
        // update and format the money 1.000.000
        //document.getElementById('money').innerHTML = result.cash.toLocaleString('en').replace(/,/g, '.');
        let moneyEle = document.getElementById('money') 
        moneyEle.innerHTML = Intl.NumberFormat("pt-BR", {
            style: "currency",
            currency: "BRL",
          }).format(result.cash);
        
        currentMoney = result.cash;
        if(currentMoney < 0)
            moneyEle.classList.add('text-danger');
        else
            moneyEle.classList.remove('text-danger');
       
        
        
    })
}
