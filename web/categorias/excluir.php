<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? 0);

if ($id) {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('SELECT foto FROM recursos WHERE categoria_id = ? AND foto IS NOT NULL');
        $stmt->execute([$id]);
        $fotos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt = $pdo->prepare('DELETE FROM reservas WHERE recurso_id IN (SELECT id FROM recursos WHERE categoria_id = ?)');
        $stmt->execute([$id]);

        $stmt = $pdo->prepare('DELETE FROM recursos WHERE categoria_id = ?');
        $stmt->execute([$id]);

        $stmt = $pdo->prepare('DELETE FROM categorias WHERE id = ?');
        $stmt->execute([$id]);

        $pdo->commit();

        foreach ($fotos as $foto) {
            $caminhoFoto = __DIR__ . '/../uploads/' . $foto;
            if ($foto && file_exists($caminhoFoto)) {
                @unlink($caminhoFoto);
            }
        }

        redirect('listar.php?msg=excluida');
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        redirect('listar.php?erro=excluir');
    }
}

redirect('listar.php');
