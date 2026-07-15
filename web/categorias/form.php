<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? 0);
$cat = ['nome' => '', 'descricao' => ''];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM categorias WHERE id = ?');
    $stmt->execute([$id]);
    $cat = $stmt->fetch() ?: $cat;
}
$tituloPagina = $id ? 'Editar categoria' : 'Nova categoria';
require __DIR__ . '/../includes/header.php';
?>
<h1><?= e($tituloPagina) ?></h1>
<form class="form-card" method="post" action="salvar.php">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <div class="form-group">
        <label>Nome *</label>
        <input type="text" name="nome" required value="<?= e($cat['nome']) ?>">
    </div>
    <div class="form-group">
        <label>Descrição</label>
        <textarea name="descricao"><?= e($cat['descricao']) ?></textarea>
    </div>
    <button class="btn">Salvar</button>
    <a class="btn btn-cinza" href="listar.php">Cancelar</a>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
