<?php

session_start();

$total_badge = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre — NOIR<</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="navbar__logo">NOIR</a>
        <ul class="navbar__links">
            <li><a href="index.php">Coleção</a></li>
            <li><a href="index.php">Novidades</a></li>
            <li><a href="sobre.php">Sobre</a></li>
        </ul>
        <div class="navbar__acoes">
            <a href="#">Buscar</a>
            <a href="carrinho.php">
                Carrinho
                <span class="carrinho-badge"><?= $total_badge ?></span>
            </a>
        </div>
    </nav>

     <div class="sobre-hero">
        <p class="sobre-hero__tag">Nossa história</p>
        <h1 class="sobre-hero__titulo">NOIR</h1>
    </div>

     <div class="sobre-conteudo">

        <div class="sobre-secao">
            <p class="sobre-secao__label">História</p>
            <h2 class="sobre-secao__titulo">Uma marca nascida do silêncio</h2>
            <p class="sobre-secao__texto">
                A NOIR nasceu em 2018 a partir de uma ideia simples: criar roupas que
                existissem além das tendências. Fundada em Lisboa, a marca surgiu da
                insatisfação com um mercado de moda acelerado, que produzia peças
                descartáveis e sem identidade.
            </p>

            <div class="sobre-citacao">
                <p class="sobre-citacao__texto">
                    "Acreditamos que a verdadeira elegância não grita.<br>
                    Ela simplesmente existe."
                </p>
            </div>

            <p class="sobre-secao__texto">
                Cada coleção da NOIR é desenvolvida com atenção obsessiva ao caimento,
                ao toque e à durabilidade. Não lançamos novidades todo mês — lançamos
                peças que duram anos. Roupas que você herda, não descarta.
            </p>
        </div>

        <div class="sobre-secao">
            <p class="sobre-secao__label">O que nos guia</p>

            <div class="valores-grid">

                <div class="valor-item">
                    <p class="valor-item__numero">01</p>
                    <p class="valor-item__titulo">Atemporalidade</p>
                    <p class="valor-item__texto">
                        Criamos peças que atravessam estações e anos sem perder relevância.
                    </p>
                </div>

                <div class="valor-item">
                    <p class="valor-item__numero">02</p>
                    <p class="valor-item__titulo">Qualidade</p>
                    <p class="valor-item__texto">
                        Cada tecido é escolhido à mão. Cada costura é revisada antes de sair da oficina.
                    </p>
                </div>

                <div class="valor-item">
                    <p class="valor-item__numero">03</p>
                    <p class="valor-item__titulo">Consciência</p>
                    <p class="valor-item__texto">
                        Produção limitada, fornecedores responsáveis e embalagens 100% recicláveis.
                    </p>
                </div>

            </div>
        </div>

         <div class="sobre-secao">
            <p class="sobre-secao__label">Manifesto</p>
            <h2 class="sobre-secao__titulo">Menos, mas melhor</h2>
            <p class="sobre-secao__texto">
                Vivemos em uma era de excesso. Excesso de escolhas, excesso de novidades,
                excesso de ruído. A NOIR é uma resposta a tudo isso. Acreditamos que um
                guarda-roupa menor e mais intencional liberta — em vez de limitar.
            </p>
            <p class="sobre-secao__texto" style="margin-top: 16px;">
                Não vendemos peças. Vendemos a sensação de estar exatamente como você quer,
                todos os dias, sem esforço.
            </p>
        </div>

    </div>

    <div class="sobre-banner">
        <p class="sobre-banner__texto">Conheça a coleção</p>
        <h2 class="sobre-banner__titulo">NOIR — 2026</h2>
        <a href="index.php" class="btn-primario">Ver Coleção</a>
    </div>

    <script src="js/main.js"></script>
    
</body>
</html>