<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();
$tituloPagina = 'Categorias';

$busca = trim($_GET['busca'] ?? '');
if ($busca !== '') {
    $stmt = $pdo->prepare('SELECT * FROM categorias WHERE nome LIKE ? ORDER BY nome');
    $stmt->execute(['%' . $busca . '%']);
    $lista = $stmt->fetchAll();
} else {
    $lista = $pdo->query('SELECT * FROM categorias ORDER BY nome')->fetchAll();
}
require __DIR__ . '/../includes/header.php';
?>
<div class="toolbar">
    <h1>Categorias</h1>
    <a class="btn btn-verde" href="form.php">+ Nova categoria</a>
</div>
<?php if (($_GET['msg'] ?? '') === 'excluida'): ?>
    <div class="alerta ok">Categoria excluida com sucesso.</div>
<?php elseif (($_GET['erro'] ?? '') === 'excluir'): ?>
    <div class="alerta erro">Nao foi possivel excluir esta categoria.</div>
<?php endif; ?>
<form class="busca" method="get" style="margin-bottom:16px">
    <input type="text" name="busca" placeholder="Buscar por nome..." value="<?= e($busca) ?>">
    <button class="btn btn-cinza">Buscar</button>
</form>
<table>
    <thead><tr><th>Nome</th><th>Descrição</th><th style="width:160px">Ações</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?>
        <tr><td colspan="3">Nenhuma categoria encontrada.</td></tr>
    <?php else: foreach ($lista as $c): ?>
        <tr>
            <td><?= e($c['nome']) ?></td>
            <td><?= e($c['descricao']) ?></td>
            <td>
                <a class="btn btn-peq" href="form.php?id=<?= $c['id'] ?>">Editar</a>
                <a class="btn btn-peq btn-excluir" href="excluir.php?id=<?= $c['id'] ?>"
                   onclick="return confirm('Excluir esta categoria? Os recursos e reservas ligados a ela tambem serao removidos.')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../includes/footer.php'; ?>
