<?php

session_start();
require_once '../db/conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_POST['produto_id'], $_POST['quantidade'])) {
    echo json_encode(['sucesso' => false]);
    exit;
}

$produto_id = intval($_POST['produto_id']);
$quantidade = intval($_POST['quantidade']);

$stmt = $pdo->prepare("SELECT estoque FROM produtos WHERE id = ?");
$stmt->execute([$produto_id]);
$produto = $stmt->fetch();

if (!$produto) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Produto não encontrado']);
    exit;
}

if ($quantidade > $produto['estoque']) {
    $quantidade = $produto['estoque'];
}

if ($quantidade <= 0) {
   
    unset($_SESSION['carrinho'][$produto_id]);
} else {
    $_SESSION['carrinho'][$produto_id] = $quantidade;
}

echo json_encode(['sucesso' => true]);