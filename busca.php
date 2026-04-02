<?php

session_start();
mb_internal_encoding('UTF-8');
require_once 'db/conexao.php';

$busca    = trim($_GET['q']      ?? '');
$categoria= trim($_GET['categoria'] ?? '');
$preco_max= trim($_GET['preco_max']  ?? '');

$sql    = "SELECT * FROM produtos WHERE 1=1";
$params = [];

if (!empty($busca)) {
    
    $busca_lower = strtolower($busca);

    $sql     .= " AND (LOWER(nome) LIKE ? OR LOWER(categoria) LIKE ? OR LOWER(descricao) LIKE ?)";
    $params[] = '%' . $busca_lower . '%'; 
    $params[] = '%' . $busca_lower . '%'; 
    $params[] = '%' . $busca_lower . '%';
}

if (!empty($categoria)) {

    $sql     .= " AND LOWER(categoria) = LOWER(?)";
    $params[] = $categoria;
}

if (!empty($preco_max) && is_numeric($preco_max)) {
    $sql     .= " AND preco <= ?";
    $params[] = floatval($preco_max);
}


$sql .= " ORDER BY nome ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll();

$cats = $pdo->query("SELECT DISTINCT categoria FROM produtos ORDER BY categoria")->fetchAll();

$total_badge = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca — NOIR</title>
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
            <a href="busca.php">Buscar</a>
            <a href="carrinho.php">
                Carrinho
                <span class="carrinho-badge"><?= $total_badge ?></span>
            </a>
        </div>
    </nav>

      <div class="busca-topo">
        <form action="busca.php" method="GET" class="busca-form" accept-charset="UTF-8">

            <div class="busca-form__principal">
                <input type="text"
                       name="q"
                       class="busca-input"
                       placeholder="Buscar por nome ou categoria..."
                       value="<?= htmlspecialchars($busca) ?>">
                <button type="submit" class="btn-primario">Buscar</button>
            </div>

            <div class="busca-form__filtros">

                <select name="categoria" class="busca-select">
                    <option value="">Todas as categorias</option>
                    <?php foreach ($cats as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['categoria'], ENT_QUOTES, 'UTF-8') ?>"
                            <?= $categoria === $cat['categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="preco_max" class="busca-select">
                <option value="">Qualquer preço</option>
                <option value="200"  <?= $preco_max == '200'  ? 'selected' : '' ?>>Até R$ 200</option>
                <option value="500"  <?= $preco_max == '500'  ? 'selected' : '' ?>>Até R$ 500</option>
                <option value="800"  <?= $preco_max == '800'  ? 'selected' : '' ?>>Até R$ 800</option>
                <option value="1000" <?= $preco_max == '1000' ? 'selected' : '' ?>>Até R$ 1.000</option>
                <option value="9999" <?= $preco_max == '9999' ? 'selected' : '' ?>>Acima de R$ 1.000</option>
            </select>

                <?php if (!empty($busca) || !empty($categoria) || !empty($preco_max)): ?>
                    <a href="busca.php" class="busca-limpar">Limpar filtros</a>
                <?php endif; ?>

            </div>
        </form>
    </div>

      <div style="padding: 0 40px; margin-bottom: 8px;">
        <p class="secao-titulo" style="text-align:left; padding: 32px 0 8px;">
            <?php if (!empty($busca) || !empty($categoria) || !empty($preco_max)): ?>
                <?= count($produtos) ?> resultado<?= count($produtos) !== 1 ? 's' : '' ?> encontrado<?= count($produtos) !== 1 ? 's' : '' ?>
            <?php else: ?>
                — todos os produtos —
            <?php endif; ?>
        </p>
    </div>

     <?php if (empty($produtos)): ?>

        <div style="text-align:center; padding: 80px 40px; color: var(--cinza);">
            <p style="font-size:14px; letter-spacing:2px; margin-bottom:24px;">
                Nenhum produto encontrado para "<?= htmlspecialchars($busca) ?>".
            </p>
            <a href="busca.php" class="btn-secundario">Ver todos os produtos</a>
        </div>

    <?php else: ?>

        <div class="produtos-grid">
            <?php foreach ($produtos as $produto): ?>

            <article class="card-produto">
                <div class="card-produto__imagem">
                    <?php if ($produto['id'] <= 3): ?>
                        <span class="card-produto__badge">Novo</span>
                    <?php elseif ($produto['estoque'] < 3): ?>
                        <span class="card-produto__badge">Último</span>
                    <?php endif; ?>

                    <?php $caminho_img = 'img/' . $produto['imagem'];
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
                            <button class="btn-add-carrinho" data-id="<?= $produto['id'] ?>">
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

    <?php endif; ?>

    <script src="js/main.js"></script>

</body>
</html>