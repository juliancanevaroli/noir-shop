<?php

session_start();
require_once 'db/conexao.php';

if (empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit;
}

$erro    = '';  
$sucesso = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($nome) || empty($email)) {
        $erro = 'Por favor, preencha nome e e-mail.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     
        $erro = 'E-mail inválido. Verifique e tente novamente.';

    } else {
        $total = 0;
        $ids   = array_keys($_SESSION['carrinho']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $pdo->prepare("
            SELECT id, preco, estoque FROM produtos WHERE id IN ($placeholders)
        ");
        $stmt->execute($ids);
        $produtos = $stmt->fetchAll();

        foreach ($produtos as $p) {
            $qtd    = $_SESSION['carrinho'][$p['id']];
            $total += $p['preco'] * $qtd;
        }

        try {
            $pdo->beginTransaction(); 
            $stmt = $pdo->prepare("
                INSERT INTO pedidos (nome_cliente, email_cliente, total)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$nome, $email, $total]);
            $pedido_id = $pdo->lastInsertId();

            $stmt_item = $pdo->prepare("
                INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unit)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($produtos as $p) {
                $qtd = $_SESSION['carrinho'][$p['id']];
                $stmt_item->execute([$pedido_id, $p['id'], $qtd, $p['preco']]);
                $pdo->prepare("
                    UPDATE produtos SET estoque = estoque - ? WHERE id = ?
                ")->execute([$qtd, $p['id']]);
            }

            $pdo->commit();
            unset($_SESSION['carrinho']);

            $sucesso = true;

        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = 'Ocorreu um erro ao processar seu pedido. Tente novamente.';
        }
    }
}

$total_badge = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido — NOIR</title>
    <link rel="stylesheet" href="css/style.css">
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


    <main class="pagina-checkout">

        <?php if ($sucesso): ?>
            <div class="confirmacao">
                <div class="confirmacao__icone">✓</div>
                <h1 class="confirmacao__titulo">Pedido Confirmado</h1>
                <p class="confirmacao__texto">
                    Obrigado, <?= htmlspecialchars($nome) ?>!<br>
                    Você receberá uma confirmação em <?= htmlspecialchars($email) ?>.
                </p>
                <a href="index.php" class="btn-primario">Continuar Comprando</a>
            </div>

        <?php else: ?>
            <h1 class="checkout-titulo">Finalizar Pedido</h1>

            <?php if ($erro): ?>
                <div class="alerta alerta--erro"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form action="" method="POST">

                <div class="form-grupo">
                    <label for="nome">Nome completo</label>
                    <input type="text"
                           id="nome"
                           name="nome"
                           placeholder="Seu nome"
                           value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                           required>
                </div>

                <div class="form-grupo">
                    <label for="email">E-mail</label>
                    <input type="email"
                           id="email"
                           name="email"
                           placeholder="seu@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required>
                </div>

                <div style="margin-top: 32px;">
                    <button type="submit" class="btn-primario" style="width:100%;">
                        Confirmar Pedido
                    </button>
                </div>

                <div style="margin-top:16px; text-align:center;">
                    <a href="carrinho.php" class="btn-secundario">
                        ← Voltar ao Carrinho
                    </a>
                </div>

            </form>

        <?php endif; ?>

    </main>

    <script src="js/main.js"></script>
</body>
</html>
