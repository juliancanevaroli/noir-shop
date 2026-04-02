<?php

session_start();
require_once '../db/conexao.php';

header('Content-Type: application/json');

$total = 0;

if (!empty($_SESSION['carrinho'])) {

    $ids = array_keys($_SESSION['carrinho']);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT id, preco FROM produtos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $produtos = $stmt->fetchAll();

    foreach ($produtos as $produto){
        $qtd = $_SESSION['carrinho'][$produto['id']];
        $total += $produto['preco'] * $qtd;
    }
}

echo json_encode(['total' => number_format($total, 2, ',', '.')]);