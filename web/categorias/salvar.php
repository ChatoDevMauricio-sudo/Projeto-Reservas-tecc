<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('listar.php');

$id        = (int)($_POST['id'] ?? 0);
$nome      = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

if ($nome === '') { die('Nome é obrigatório.'); }

if ($id) {
    $stmt = $pdo->prepare('UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?');
    $stmt->execute([$nome, $descricao, $id]);
} else {
    $stmt = $pdo->prepare('INSERT INTO categorias (nome, descricao) VALUES (?, ?)');
    $stmt->execute([$nome, $descricao]);
}
redirect('listar.php');
