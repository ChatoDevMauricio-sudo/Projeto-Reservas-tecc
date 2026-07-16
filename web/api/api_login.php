<?php
require_once __DIR__ . '/_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['status' => 'erro', 'mensagem' => 'Use POST.'], 405);
}
$in    = corpoJson();
$email = trim($in['email'] ?? '');
$senha = $in['senha'] ?? '';

$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
$u = $stmt->fetch();

if ($u && password_verify($senha, $u['senha'])) {
    responder([
        'status'  => 'ok',
        'usuario' => [
            'id'   => (int)$u['id'],
            'nome' => $u['nome'],
            'email'=> $u['email'],
            'tipo' => $u['tipo'],
        ],
    ]);
}
responder(['status' => 'erro', 'mensagem' => 'E-mail ou senha inválidos.'], 401);
