<?php

session_start();
header('Content-Type: application/json');

$total = isset($_SESSION['carrinho'])
    ? array_sum($_SESSION['carrinho'])
    : 0;

echo json_encode(['total' => $total]);