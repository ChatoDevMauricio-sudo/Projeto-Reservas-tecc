<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();
$tituloPagina = 'Setores';

$busca = trim($_GET['busca'] ?? '');
if ($busca !== '') {
    $stmt = $pdo->prepare('SELECT * FROM setores WHERE nome LIKE ? ORDER BY nome');
    $stmt->execute(['%' . $busca . '%']);
    $lista = $stmt->fetchAll();
} else {
    $lista = $pdo->query('SELECT * FROM setores ORDER BY nome')->fetchAll();
}
require __DIR__ . '/../includes/header.php';
?>
<div class="toolbar">
    <h1>Setores</h1>
    <a class="btn btn-verde" href="form.php">+ Novo setor</a>
</div>
<form class="busca" method="get" style="margin-bottom:16px">
    <input type="text" name="busca" placeholder="Buscar por nome..." value="<?= e($busca) ?>">
    <button class="btn btn-cinza">Buscar</button>
</form>
<table>
    <thead><tr><th>Nome</th><th>Responsável</th><th style="width:160px">Ações</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?>
        <tr><td colspan="3">Nenhum setor encontrado.</td></tr>
    <?php else: foreach ($lista as $s): ?>
        <tr>
            <td><?= e($s['nome']) ?></td>
            <td><?= e($s['responsavel']) ?></td>
            <td>
                <a class="btn btn-peq" href="form.php?id=<?= $s['id'] ?>">Editar</a>
                <a class="btn btn-peq btn-excluir" href="excluir.php?id=<?= $s['id'] ?>"
                   onclick="return confirm('Excluir este setor?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../includes/footer.php'; ?>
