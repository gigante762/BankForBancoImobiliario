function attTransferTo(users) {
    $('#transfer-to').html('')
    for (let user of Object.keys(users)) {
        if (name == user)
            continue

        let txt = document.createElement("option");
        txt.innerText = user;

        $('#transfer-to').append(txt)
    }
    att()
}

function insertNews(newsA) {

    //$('#news').html('')
    let txt = ''
    for (let i = 0; i < 5 && i < newsA.length; i++) {
        txt += '<p>' + newsA[i] + '</p>'
    }
    $('#news').html(txt)
}

function att() {
    $.ajax({
        url: "acao/data.json",
        success: function(result) {

            //data = result
            //debug
            //console.log(result.users)

            //update the news section
            insertNews(result.news)

            //not render unnecessarily
            if (lastNew == result.news[0])
                return

            lastNew = result.news[0]

            // update and format the money 1.000.000
            $('#money').html(result.users[name].toLocaleString('en').replace(/,/g, '.'))

            currentmoney = result.users[name]

            //elemento html com o dinheiro
            let elemoney = document.querySelector('.menu h3')

            if (result.users[name] >= 0)
                elemoney.className = 'display-money-green'
            else
                elemoney.className = 'display-money-red'

            //udate the transfer-to class
            //attTransferTo(result.users) 
            if (!attnomes)
                attTransferTo(result.users)
            attnomes = true;
        }
    });

}

function pagarBanco() {
    let qtd = Number($('#bancoValor').val())
    if (qtd == 0)
        return
    $('#bancoValor').val('')
    $.post("acao/requests.php", {
        pagar: 'true',
        qtd: qtd,
        name: name
    });
    //console.log(qtd)
    //att()
}

function sacarBanco() {
    let qtd = Number($('#bancoValor').val())
    if (qtd == 0)
        return
    $('#bancoValor').val('')
    $.post("acao/requests.php", {
        sacar: 'true',
        qtd: qtd,
        name: name
    });
    //console.log(qtd)
    //att()
}

function gerarNotification(msg) {
    let txt = document.createElement("div");
    txt.innerHTML = "<div notification class='alert alert-danger' role='alert'>" + msg + "</div>"
    $('#notifications').append(txt)

    window.setTimeout(function() {
        $('[notification]').fadeOut('slow')
        $("[notification]").remove();
    }, 3000);

}

function transferir() {
    let qtd = Number($('#moneyTransfer').val())
    if (qtd == 0)
        return
    $('#moneyTransfer').val('')
    let to = $('#transfer-to').val()

    let status = $.post("acao/requests.php", {
            transferir: 'true',
            qtd: qtd,
            name: name,
            to: to
        },
        function(msg) {
            console.log(msg)
        })


    status.done(
        function(msg) {
            console.log(msg)
        })
    //console.log(status.)
    //att()
}
