<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$voltar = $_GET['voltar'] ?? '';
$destino = $voltar === 'listar' && ehAdmin() ? 'listar.php' : 'historico.php';

if ($id) {
    if (ehAdmin()) {
        $stmt = $pdo->prepare('DELETE FROM reservas WHERE id = ?');
        $stmt->execute([$id]);
    } else {
        $stmt = $pdo->prepare('DELETE FROM reservas WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $_SESSION['usuario_id']]);
    }
}

redirect($destino);
