<?php
require_once __DIR__ . '/_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['status' => 'erro', 'mensagem' => 'Use POST.'], 405);
}
$in           = corpoJson();
$usuario_id   = (int)($in['usuario_id'] ?? 0);
$recurso_id   = (int)($in['recurso_id'] ?? 0);
$data_reserva = $in['data_reserva'] ?? '';
$turno        = $in['turno'] ?? '';

if (!$usuario_id || !$recurso_id || !$data_reserva || !in_array($turno, ['manha','tarde','noite'])) {
    responder(['status' => 'erro', 'mensagem' => 'Dados incompletos.'], 400);
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO reservas (recurso_id, usuario_id, data_reserva, turno, origem)
         VALUES (?, ?, ?, ?, "mobile")');
    $stmt->execute([$recurso_id, $usuario_id, $data_reserva, $turno]);
    responder(['status' => 'ok', 'mensagem' => 'Reserva confirmada!', 'id' => (int)$pdo->lastInsertId()]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        responder(['status' => 'erro', 'mensagem' => 'Recurso já reservado nesta data e turno.'], 409);
    }
    responder(['status' => 'erro', 'mensagem' => 'Erro ao reservar.'], 500);
}
