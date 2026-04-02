<?php

session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['produto_id'])) {
    echo json_encode(['sucesso' => false]);
    exit;
}

$produto_id = intval($_POST['produto_id']);

if (isset($_SESSION['carrinho'][$produto_id])) {
    unset($_SESSION['carrinho'][$produto_id]);
}

echo json_encode(['sucesso' => true]);
