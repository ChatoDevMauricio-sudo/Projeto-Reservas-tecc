<?php
/**
 * INSTALADOR - roda UMA vez.
 * Cria o usuário admin e um usuário comum de demonstração com a senha
 * corretamente criptografada (password_hash).
 * Acesse:  http://localhost/reservas/instalar.php
 */
require __DIR__ . '/config/db.php';

$usuarios = [
    ['Administrador', 'admin@escola.com', '123456', 'admin'],
    ['Aluno Demo',    'aluno@escola.com', '123456', 'user'],
];

$sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE senha = VALUES(senha), tipo = VALUES(tipo), nome = VALUES(nome)";
$stmt = $pdo->prepare($sql);

foreach ($usuarios as $u) {
    $hash = password_hash($u[2], PASSWORD_DEFAULT);
    $stmt->execute([$u[0], $u[1], $hash, $u[3]]);
}

echo "<h2>Instalação concluída!</h2>";
echo "<p>Usuários criados/atualizados:</p><ul>";
echo "<li><b>Admin:</b> admin@escola.com / 123456</li>";
echo "<li><b>Usuário:</b> aluno@escola.com / 123456</li>";
echo "</ul>";
echo '<p><a href="login.php">Ir para o login</a></p>';
echo "<p style='color:#b00'>Depois de testar, você pode apagar este arquivo (instalar.php).</p>";
