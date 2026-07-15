<?php
require_once __DIR__ . '/funcoes.php';
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tituloPagina) ? e($tituloPagina) : 'Sistema de Reservas' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="brand">
        <span class="logo">R</span>
        <span>Reservas &amp; Emprestimos</span>
    </div>
    <?php if (logado()): ?>
    <nav class="menu">
        <a href="<?= BASE_URL ?>/index.php">Dashboard</a>
        <?php if (ehAdmin()): ?>
            <a href="<?= BASE_URL ?>/recursos/listar.php">Recursos</a>
            <a href="<?= BASE_URL ?>/categorias/listar.php">Categorias</a>
            <a href="<?= BASE_URL ?>/setores/listar.php">Setores</a>
            <a href="<?= BASE_URL ?>/reservas/listar.php">Reservas</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/reservas/reservar.php">Reservar</a>
            <a href="<?= BASE_URL ?>/reservas/historico.php">Meu Historico</a>
        <?php endif; ?>
    </nav>
    <div class="userbox">
        <span><?= e($_SESSION['usuario_nome']) ?> (<?= e($_SESSION['usuario_tipo']) ?>)</span>
        <?php if (ehAdmin()): ?>
        <button type="button" class="btn-painel" onclick="abrirPainelTV()" title="Abrir Painel de Recursos">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:17px;height:17px;flex-shrink:0;display:block;">
                <rect x="2" y="7" width="20" height="13" rx="2"></rect>
                <polyline points="17 2 12 7 7 2"></polyline>
            </svg>
            <span>Painel</span>
        </button>
        <?php endif; ?>
        <a class="btn-sair" href="<?= BASE_URL ?>/logout.php">Sair</a>
    </div>
    <?php endif; ?>
</header>
<script>
    /**
     * Abre o Painel de Recursos (painel.php) numa janela flutuante
     * separada (sem barra de endereço/menu), sempre centralizada.
     */
    function abrirPainelTV() {
        const largura = 1000;
        const altura = 700;
        const esquerda = Math.round((screen.width - largura) / 2);
        const topo = Math.round((screen.height - altura) / 2);

        window.open(
            '<?= BASE_URL ?>/painel.php',
            'painelTV',
            `width=${largura},height=${altura},left=${esquerda},top=${topo}` +
            ',resizable=yes,scrollbars=yes,status=no,toolbar=no,menubar=no,location=no'
        );
    }
</script>
<main class="container">