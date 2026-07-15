<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/funcoes.php';

if (logado()) redirect('index.php');

$erro    = '';
$sucesso = '';

$nome  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome           = trim($_POST['nome'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $senha          = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um e-mail válido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmarSenha) {
        $erro = 'As senhas não coincidem.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erro = 'Já existe uma conta cadastrada com este e-mail.';
        } else {
           
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, "user")');
            $stmt->execute([$nome, $email, $hash]);

            $sucesso = 'Conta criada com sucesso! Você já pode entrar.';
            $nome  = '';
            $email = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta - Sistema de Reservas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="login-wrap">
    <form class="login-card" method="post">
        <h1>📦 Criar conta</h1>

        <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>
        <?php if ($sucesso): ?><div class="alerta ok"><?= e($sucesso) ?></div><?php endif; ?>

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" required autofocus placeholder="Seu nome completo" value="<?= e($nome) ?>">
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" required placeholder="voce@escola.com" value="<?= e($email) ?>">
        </div>
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required placeholder="Mínimo 6 caracteres">
        </div>
        <div class="form-group">
            <label>Confirmar senha</label>
            <input type="password" name="confirmar_senha" required placeholder="Repita a senha">
        </div>

        <button class="btn" style="width:100%">Cadastrar</button>

        <p style="text-align:center;font-size:13px;color:#94a3b8;margin-top:16px">
            Já tem conta? <a href="<?= BASE_URL ?>/login.php">Entrar</a>
        </p>
    </form>
</div>
</body>
</html>
