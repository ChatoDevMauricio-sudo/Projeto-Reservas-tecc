<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/funcoes.php';

if (logado()) redirect('index.php');

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if ($u && password_verify($senha, $u['senha'])) {
        $_SESSION['usuario_id']   = $u['id'];
        $_SESSION['usuario_nome'] = $u['nome'];
        $_SESSION['usuario_tipo'] = $u['tipo'];
        redirect('index.php');
    } else {
        $erro = 'E-mail ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Reservas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="login-wrap">
    <form class="login-card" method="post">
        <h1>📦 Reservas</h1>
        <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" required autofocus placeholder="admin@escola.com">
        </div>
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required placeholder="123456">
        </div>
        <button class="btn" style="width:100%">Entrar</button>
        <p style="text-align:center;font-size:13px;margin-top:16px">
            Não tem conta? <a href="<?= BASE_URL ?>/cadastro.php">Cadastre-se</a>
        </p>
        <p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:8px">
            Admin: admin@escola.com &middot; senha 123456
        </p>
    </form>
</div>
</body>
</html>
