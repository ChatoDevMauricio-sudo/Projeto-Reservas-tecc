<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    // apaga a foto do disco, se houver
    $stmt = $pdo->prepare('SELECT foto FROM recursos WHERE id = ?');
    $stmt->execute([$id]);
    $foto = $stmt->fetchColumn();
    if ($foto && file_exists(__DIR__ . '/../uploads/' . $foto)) {
        @unlink(__DIR__ . '/../uploads/' . $foto);
    }
    $pdo->prepare('DELETE FROM recursos WHERE id = ?')->execute([$id]);
}
redirect('listar.php');
