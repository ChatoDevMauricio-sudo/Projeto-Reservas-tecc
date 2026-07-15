<?php
/**
 * Configuração de conexão com o banco de dados sistema_reservas
 * Ajuste os dados abaixo conforme o seu ambiente (XAMPP, hospedagem, etc).
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_reservas');
define('DB_USER', 'root');     // troque pelo usuário do seu MySQL/MariaDB
define('DB_PASS', '');         // troque pela senha do seu MySQL/MariaDB
define('DB_CHARSET', 'utf8mb4');

function conectarBanco(): PDO
{
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $opcoes = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $opcoes);
    } catch (PDOException $e) {
        // Em produção, evite mostrar o erro completo. Aqui deixamos simples para debug.
        die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
    }
}