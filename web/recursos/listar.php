<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();
$tituloPagina = 'Recursos';

$busca = trim($_GET['busca'] ?? '');
$sql = "SELECT r.*, c.nome AS categoria, s.nome AS setor
        FROM recursos r
        JOIN categorias c ON c.id = r.categoria_id
        JOIN setores s    ON s.id = r.setor_id";
if ($busca !== '') {
    $sql .= ' WHERE r.nome LIKE ? ORDER BY r.nome';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['%' . $busca . '%']);
    $lista = $stmt->fetchAll();
} else {
    $lista = $pdo->query($sql . ' ORDER BY r.nome')->fetchAll();
}
require __DIR__ . '/../includes/header.php';
?>
<div class="toolbar">
    <h1>Recursos</h1>
    <a class="btn btn-verde" href="form.php">+ Novo recurso</a>
</div>
<form class="busca" method="get" style="margin-bottom:16px">
    <input type="text" name="busca" placeholder="Buscar recurso pelo nome..." value="<?= e($busca) ?>">
    <button class="btn btn-cinza">Buscar</button>
</form>
<table>
    <thead><tr><th>Foto</th><th>Nome</th><th>Categoria</th><th>Setor</th><th style="width:160px">Ações</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?>
        <tr><td colspan="5">Nenhum recurso encontrado.</td></tr>
    <?php else: foreach ($lista as $r): ?>
        <tr>
            <td>
                <?php if ($r['foto']): ?>
                    <img class="thumb" src="<?= BASE_URL ?>/uploads/<?= e($r['foto']) ?>" alt="">
                <?php else: ?>
                    <span style="color:#94a3b8">sem foto</span>
                <?php endif; ?>
            </td>
            <td><?= e($r['nome']) ?></td>
            <td><?= e($r['categoria']) ?></td>
            <td><?= e($r['setor']) ?></td>
            <td>
                <a class="btn btn-peq" href="form.php?id=<?= $r['id'] ?>">Editar</a>
                <a class="btn btn-peq btn-excluir" href="excluir.php?id=<?= $r['id'] ?>"
                   onclick="return confirm('Excluir este recurso?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../includes/footer.php'; ?>
