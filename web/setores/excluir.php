<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    try {
        $stmt = $pdo->prepare('DELETE FROM setores WHERE id = ?');
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die('Não é possível excluir: existe recurso usando este setor.');
    }
}
redirect('listar.php');
