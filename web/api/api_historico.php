<?php
require_once __DIR__ . '/_config.php';

$usuario_id = (int)($_GET['usuario_id'] ?? 0);
if (!$usuario_id) {
    responder(['status' => 'erro', 'mensagem' => 'Informe usuario_id.'], 400);
}

$stmt = $pdo->prepare(
    "SELECT r.id, r.data_reserva, r.turno, r.origem, r.status,
            rec.nome AS recurso, rec.foto
     FROM reservas r
     JOIN recursos rec ON rec.id = r.recurso_id
     WHERE r.usuario_id = ?
     ORDER BY r.data_reserva DESC, r.criado_em DESC");
$stmt->execute([$usuario_id]);
$rows = $stmt->fetchAll();

$base = urlBase();
foreach ($rows as &$r) {
    $r['id']       = (int)$r['id'];
    $r['foto_url'] = $r['foto'] ? $base . $r['foto'] : '';
}
responder(['status' => 'ok', 'total' => count($rows), 'reservas' => $rows]);
