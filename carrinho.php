<?php

session_start();
require_once 'db/conexao.php';

$itens = [];
$total = 0;

if (!empty($_SESSION['carrinho'])) {

    $ids = array_keys($_SESSION['carrinho']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("
        SELECT id, nome, categoria, preco, imagem
        FROM produtos
        WHERE id IN ($placeholders)
    ");
    $stmt->execute($ids);
    $produtos_db = $stmt->fetchAll();

    foreach ($produtos_db as $produto) {
        $qtd = $_SESSION['carrinho'][$produto['id']];
        $subtotal = $produto['preco'] * $qtd;
        $total   += $subtotal;

        $itens[] = [
            'id' => $produto['id'],
            'nome'      => $produto['nome'],
            'categoria' => $produto['categoria'],
            'preco'     => $produto['preco'],
            'imagem'    => $produto['imagem'],
            'quantidade'=> $qtd,
            'subtotal'  => $subtotal,
        ];
    }
}

$total_badge = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho — NOIR</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        
        .removendo {
            transition: opacity 0.4s, transform 0.4s;
            opacity: 0;
            transform: translateX(20px);
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="navbar__logo">NOIR</a>
        <ul class="navbar__links">
            <li><a href="index.php">Coleção</a></li>
            <li><a href="#">Novidades</a></li>
            <li><a href="#">Sobre</a></li>
        </ul>
        <div class="navbar__acoes">
            <a href="#">Buscar</a>
            <a href="carrinho.php">
                Carrinho
                <span class="carrinho-badge"><?= $total_badge ?></span>
            </a>
        </div>
    </nav>

    <main class="pagina-carrinho">

        <h1 class="carrinho-titulo">Seu Carrinho</h1>

        <?php if (empty($itens)): ?>

            <div id="carrinho-vazio" style="text-align:center; padding:60px 0; color:var(--cinza);">
                <p style="font-size:14px; letter-spacing:2px; margin-bottom:24px;">
                    Seu carrinho está vazio.
                </p>
                <a href="index.php" class="btn-primario">Ver Coleção</a>
            </div>

        <?php else: ?>

            <div id="carrinho-vazio" style="display:none; text-align:center; padding:60px 0; color:var(--cinza);">
                <p style="font-size:14px; letter-spacing:2px; margin-bottom:24px;">
                    Seu carrinho está vazio.
                </p>
                <a href="index.php" class="btn-primario">Ver Coleção</a>
            </div>

            <table class="carrinho-tabela" id="tabela-carrinho">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>

                    <tr id="linha-<?= $item['id'] ?>">

                        <td>
                            <p class="carrinho-item__nome">
                                <?= htmlspecialchars($item['nome']) ?>
                            </p>
                            <p class="carrinho-item__cat">
                                <?= htmlspecialchars($item['categoria']) ?>
                            </p>
                        </td>

                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>

                        <td>
                            <input type="number"
                                   class="input-quantidade"
                                   value="<?= $item['quantidade'] ?>"
                                   min="1"
                                   data-id="<?= $item['id'] ?>"
                                   data-preco="<?= $item['preco'] ?>">
                        </td>

                        <td id="subtotal-<?= $item['id'] ?>">
                            R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
                        </td>

                        <td>
                            <button class="btn-remover"
                                    data-id="<?= $item['id'] ?>">
                                Remover
                            </button>
                        </td>

                    </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="carrinho-resumo" id="carrinho-resumo">
                <span class="carrinho-total-label">Total</span>

                <span class="carrinho-total-valor" id="carrinho-total-valor">
                    R$ <?= number_format($total, 2, ',', '.') ?>
                </span>

                <a href="finalizar.php" class="btn-primario">Finalizar Pedido</a>
            </div>

        <?php endif; ?>

    </main>

    <script src="js/main.js"></script>
</body>
</html>
