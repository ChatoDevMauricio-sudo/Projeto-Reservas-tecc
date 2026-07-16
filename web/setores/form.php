<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? 0);
$setor = ['nome' => '', 'responsavel' => ''];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM setores WHERE id = ?');
    $stmt->execute([$id]);
    $setor = $stmt->fetch() ?: $setor;
}
$tituloPagina = $id ? 'Editar setor' : 'Novo setor';
require __DIR__ . '/../includes/header.php';
?>
<h1><?= e($tituloPagina) ?></h1>
<form class="form-card" method="post" action="salvar.php">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <div class="form-group">
        <label>Nome *</label>
        <input type="text" name="nome" required value="<?= e($setor['nome']) ?>">
    </div>
    <div class="form-group">
        <label>Responsável</label>
        <input type="text" name="responsavel" value="<?= e($setor['responsavel']) ?>">
    </div>
    <button class="btn">Salvar</button>
    <a class="btn btn-cinza" href="listar.php">Cancelar</a>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
