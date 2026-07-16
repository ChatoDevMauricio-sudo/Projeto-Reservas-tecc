<?php
/** Funções auxiliares usadas em todo o sistema. */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * BASE_URL: caminho público (a partir da raiz do domínio) até a pasta "web".
 * É calculado automaticamente comparando o caminho físico desta pasta com o
 * DOCUMENT_ROOT do servidor, então funciona não importa o nome da pasta
 * onde o projeto for colocado (reservas, sistema, etc.) — resolve o
 * problema do CSS/links não carregarem quando a pasta tem outro nome ou
 * está em outro nível.
 */
if (!defined('BASE_URL')) {
    $webRoot = realpath(__DIR__ . '/..');
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;

    if ($webRoot && $docRoot && strpos($webRoot, $docRoot) === 0) {
        $base = substr($webRoot, strlen($docRoot));
        $base = str_replace('\\', '/', $base);
        $base = rtrim($base, '/');
    } else {
        $base = '';
    }

    define('BASE_URL', $base);
}

/** Escapa texto para exibir com segurança no HTML. */
function e($txt) {
    return htmlspecialchars((string)$txt, ENT_QUOTES, 'UTF-8');
}

/** Redireciona e encerra. */
function redirect($url) {
    header("Location: $url");
    exit;
}

/** Está logado? */
function logado() {
    return isset($_SESSION['usuario_id']);
}

/** É admin? */
function ehAdmin() {
    return logado() && ($_SESSION['usuario_tipo'] ?? '') === 'admin';
}

/** Nome amigável do turno. */
function nomeTurno($t) {
    $map = ['manha' => 'Manhã', 'tarde' => 'Tarde', 'noite' => 'Noite'];
    return $map[$t] ?? $t;
}
