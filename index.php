<?php

session_start();
require_once 'db/conexao.php';

$stmt = $pdo->prepare("
    SELECT id, nome, descricao, preco, categoria, imagem, estoque
    FROM produtos
    ORDER BY id ASC
");
$stmt->execute();

$produtos = $stmt->fetchAll();

$total_carrinho = isset($_SESSION['carrinho'])
    ? array_sum($_SESSION['carrinho'])
    : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOIR — Moda Atemporal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar">

        <a href="index.php" class="navbar__logo">NOIR</a>

        <ul class="navbar__links">
            <li><a href="index.php">Coleção</a></li>
            <li><a href="index.php?categoria=novidades">Novidades</a></li>
            <li><a href="sobre.php">Sobre</a></li>
        </ul>

        <div class="navbar__acoes">
            <a href="busca.php">Buscar</a>
            <a href="carrinho.php">
                Carrinho
        
                <span class="carrinho-badge"><?= $total_carrinho ?></span>
            </a>
        </div>
    </nav>

    <section class="hero">

    <video autoplay muted loop playsinline class="hero-video">
        <source src="video/video.mp4" type="video/mp4">
    </video>

    <div class="hero-conteudo">
        <p class="hero__tag">Nova Coleção — 2026</p>
        <h1 class="hero__titulo">NOIR</h1>
        <p class="hero__subtitulo">Elegância em cada detalhe</p>
        <a href="#colecao" class="btn-primario">Ver Coleção</a>
    </div>

</section>

    <section id="colecao">
        <p class="secao-titulo">— Destaques da Coleção —</p>

        <div class="produtos-grid">

            <?php
            
            foreach ($produtos as $produto):
            ?>

            <article class="card-produto">

                <div class="card-produto__imagem">

                    <?php
                   
                    if ($produto['id'] <= 3): ?>
                        <span class="card-produto__badge">Novo</span>
                    <?php elseif ($produto['estoque'] < 3): ?>
                        <span class="card-produto__badge">Último</span>
                    <?php endif; ?>

                    <?php
                    
                    $caminho_img = 'img/' . $produto['imagem'];
                    if (file_exists($caminho_img)): ?>
                        <img src="<?= htmlspecialchars($caminho_img) ?>"
                             alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <?php else: ?>
                
                        <svg width="80" height="100" viewBox="0 0 80 100" fill="none"
                             xmlns="http://www.w3.org/2000/svg" style="opacity:0.25">
                            <rect x="10" y="5" width="60" height="90" rx="2"
                                  stroke="#0e0e0e" stroke-width="2"/>
                            <line x1="10" y1="30" x2="70" y2="30"
                                  stroke="#0e0e0e" stroke-width="1.5"/>
                        </svg>
                    <?php endif; ?>
                </div>

                <div class="card-produto__info">
                    
                    <h2 class="card-produto__nome"><?= htmlspecialchars($produto['nome']) ?></h2>
                    <p class="card-produto__categoria"><?= htmlspecialchars($produto['categoria']) ?></p>

                    <div class="card-produto__rodape">
            
                        <span class="card-produto__preco">
                            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                        </span>

                        <?php if ($produto['estoque'] > 0): ?>
        
                            <button class="btn-add-carrinho"
                                    data-id="<?= $produto['id'] ?>">
                                + Carrinho
                            </button>
                        <?php else: ?>
                            <span style="font-size:10px; letter-spacing:1px; color:#bbb;">
                                Esgotado
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

            </article>

            <?php endforeach; ?>

        </div>
    </section>

    <div class="banner-info">
        <span class="banner-info__texto">
            Frete grátis acima de R$ 500 &nbsp;·&nbsp; Troca em até 30 dias
        </span>
        <a href="#colecao" class="banner-info__link">Ver todos os produtos →</a>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
