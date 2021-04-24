

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
    fetch('acao/router.php?route=getnews' )
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
    fetch('acao/router.php?route=find&name=' + name)
    .then(response => response.json())
    .then(result => {
        //console.log(result)
        // update and format the money 1.000.000
        document.getElementById('money').innerHTML = result.cash.toLocaleString('en').replace(/,/g, '.');
        currentMoney = result.cash;
    })
}
