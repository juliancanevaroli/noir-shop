document.addEventListener('DOMContentLoaded', function () {

    atualizarBadge();

    const botoesAdd = document.querySelectorAll('.btn-add-carrinho');
    botoesAdd.forEach(function (botao) {
        botao.addEventListener('click', function () {
            adicionarAoCarrinho(this.dataset.id, this);
        });
    });

    const inputsQtd = document.querySelectorAll('.input-quantidade');
    inputsQtd.forEach(function (input) {
        input.addEventListener('input', function () {
            const produtoId  = this.dataset.id;
            const quantidade = parseInt(this.value);
            const preco      = parseFloat(this.dataset.preco); 

            if (quantidade < 1) {
                this.value = 1;
                return;
            }

            atualizarQuantidade(produtoId, quantidade, preco);
        });
    });

    const botoesRemover = document.querySelectorAll('.btn-remover');
    botoesRemover.forEach(function (botao) {
        botao.addEventListener('click', function () {
            removerItem(this.dataset.id);
        });
    });
});

function adicionarAoCarrinho(produtoId, botao) {

    botao.disabled    = true;
    botao.textContent = '...';

    fetch('actions/adicionar.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    'produto_id=' + produtoId
    })
    .then(r => r.json())
    .then(function (dados) {
        if (dados.sucesso) {
            botao.textContent            = '✓';
            botao.style.backgroundColor  = '#5a9a5a';

            setTimeout(function () {
                botao.textContent           = '+ Carrinho';
                botao.style.backgroundColor = '';
                botao.disabled              = false;
            }, 1500);

            atualizarBadge();
        } else {
            botao.textContent = 'Sem estoque';
            setTimeout(function () {
                botao.textContent = '+ Carrinho';
                botao.disabled    = false;
            }, 2000);
        }
    })
    .catch(function (erro) {
        console.error('Erro ao adicionar:', erro);
        botao.textContent = '+ Carrinho';
        botao.disabled    = false;
    });
}


function atualizarQuantidade(produtoId, quantidade, preco) {
    

    fetch('actions/atualizar.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    'produto_id=' + produtoId + '&quantidade=' + quantidade
    })
    .then(r => r.json())
    .then(function (dados) {
        if (dados.sucesso) {

            var novoSubtotal = preco * quantidade;

            var subtotalFormatado = novoSubtotal.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            var elementoSubtotal = document.getElementById('subtotal-' + produtoId);
            if (elementoSubtotal) {
                elementoSubtotal.textContent = 'R$ ' + subtotalFormatado;
            }

            recalcularTotalGeral();

            atualizarBadge();
        }
    });
}


function removerItem(produtoId) {

    fetch('actions/remover.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    'produto_id=' + produtoId
    })
    .then(r => r.json())
    .then(function (dados) {
        if (dados.sucesso) {

            var linha = document.getElementById('linha-' + produtoId);

            if (linha) {
               
                linha.classList.add('removendo');

                setTimeout(function () {
                    linha.remove(); 

                    recalcularTotalGeral();

                    atualizarBadge();

                    verificarCarrinhoVazio();

                }, 400);
            }
        }
    })
    .catch(function (erro) {
        console.error('Erro ao remover item:', erro);
    });
}


function recalcularTotalGeral() {

    var total = 0;

    var subtotais = document.querySelectorAll('[id^="subtotal-"]');

    subtotais.forEach(function (el) {
        
        var texto = el.textContent
            .replace('R$', '')   
            .replace(/\./g, '')  
            .replace(',', '.')   
            .trim();           

        total += parseFloat(texto) || 0;
    });

    var totalFormatado = total.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    var elementoTotal = document.getElementById('carrinho-total-valor');
    if (elementoTotal) {
        elementoTotal.textContent = 'R$ ' + totalFormatado;
    }
}

function verificarCarrinhoVazio() {

   
    var linhasRestantes = document.querySelectorAll('#tabela-carrinho tbody tr');

    if (linhasRestantes.length === 0) {

        var tabela = document.getElementById('tabela-carrinho');
        if (tabela) tabela.style.display = 'none';

        var resumo = document.getElementById('carrinho-resumo');
        if (resumo) resumo.style.display = 'none';

        var vazio = document.getElementById('carrinho-vazio');
        if (vazio) vazio.style.display = 'block';
    }
}

function atualizarBadge() {
    fetch('actions/contar_carrinho.php')
    .then(r => r.json())
    .then(function (dados) {
        var badge = document.querySelector('.carrinho-badge');
        if (badge) {
            badge.textContent = dados.total;
        }
    })
    .catch(function () {

    });
}
