<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('listar.php');

$id          = (int)($_POST['id'] ?? 0);
$nome        = trim($_POST['nome'] ?? '');
$responsavel = trim($_POST['responsavel'] ?? '');

if ($nome === '') { die('Nome é obrigatório.'); }

if ($id) {
    $stmt = $pdo->prepare('UPDATE setores SET nome = ?, responsavel = ? WHERE id = ?');
    $stmt->execute([$nome, $responsavel, $id]);
} else {
    $stmt = $pdo->prepare('INSERT INTO setores (nome, responsavel) VALUES (?, ?)');
    $stmt->execute([$nome, $responsavel]);
}
redirect('listar.php');
