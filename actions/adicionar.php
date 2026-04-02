<?php

session_start();
require_once '../db/conexao.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['produto_id'])){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida']);
    exit;
}

$produto_id = intval($_POST['produto_id']);  

$stmt = $pdo -> prepare("SELECT id, estoque FROM produtos WHERE id = ?");
$stmt -> execute([$produto_id]);
$produto = $stmt -> fetch();

if(!$produto) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Produto não encontrado']);
    exit;
}

$qtd_no_carrinho = isset($_SESSION['carrinho'][$produto_id])
    ? $_SESSION['carrinho'][$produto_id]
    : 0;

if ($qtd_no_carrinho>= $produto['estoque']){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Estoque insuficiente']);
    exit;
}

if(!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if(isset($_SESSION['carrinho'][$produto_id])){
    $_SESSION['carrinho'][$produto_id]++;
} else {
    $_SESSION['carrinho'][$produto_id] = 1;
}

echo json_encode([
    'sucesso' => true,
    'mensagem' => 'Produto adicionado ao carrinho',
    'total' => array_sum($_SESSION['carrinho'])
]);