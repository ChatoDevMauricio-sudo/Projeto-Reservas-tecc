<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? 0);
$rec = ['nome' => '', 'descricao' => '', 'foto' => '', 'categoria_id' => '', 'setor_id' => ''];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM recursos WHERE id = ?');
    $stmt->execute([$id]);
    $rec = $stmt->fetch() ?: $rec;
}
$categorias = $pdo->query('SELECT * FROM categorias ORDER BY nome')->fetchAll();
$setores    = $pdo->query('SELECT * FROM setores ORDER BY nome')->fetchAll();

$tituloPagina = $id ? 'Editar recurso' : 'Novo recurso';
require __DIR__ . '/../includes/header.php';
?>
<h1><?= e($tituloPagina) ?></h1>
<form class="form-card" method="post" action="salvar.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <input type="hidden" name="foto_atual" value="<?= e($rec['foto']) ?>">
    <div class="form-group">
        <label>Nome do recurso *</label>
        <input type="text" name="nome" required value="<?= e($rec['nome']) ?>">
    </div>
    <div class="form-group">
        <label>Descrição</label>
        <textarea name="descricao"><?= e($rec['descricao']) ?></textarea>
    </div>
    <div class="form-group">
        <label>Categoria *</label>
        <select name="categoria_id" required>
            <option value="">-- selecione --</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $rec['categoria_id'] ? 'selected' : '' ?>>
                    <?= e($c['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Setor responsável *</label>
        <select name="setor_id" required>
            <option value="">-- selecione --</option>
            <?php foreach ($setores as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $s['id'] == $rec['setor_id'] ? 'selected' : '' ?>>
                    <?= e($s['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Foto do recurso <?= $id ? '(deixe vazio para manter a atual)' : '' ?></label>
        <input type="file" name="foto" accept="image/*">
        <?php if (!empty($rec['foto'])): ?>
            <div style="margin-top:8px">
                <img class="thumb" style="width:90px;height:70px" src="<?= BASE_URL ?>/uploads/<?= e($rec['foto']) ?>">
            </div>
        <?php endif; ?>
    </div>
    <button class="btn">Salvar</button>
    <a class="btn btn-cinza" href="listar.php">Cancelar</a>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
