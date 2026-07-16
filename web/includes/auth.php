<?php
/** Inclua no topo das páginas que exigem login. */
require_once __DIR__ . '/funcoes.php';

if (!logado()) {
    redirect('login.php');
}

/** Chame para exigir que o usuário seja admin. */
function exigirAdmin() {
    if (!ehAdmin()) {
        http_response_code(403);
        die('Acesso restrito ao administrador.');
    }
}
