<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
$tituloPagina = 'Fazer Reserva';

$msg = ''; $tipoMsg = 'ok';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recurso_id   = (int)($_POST['recurso_id'] ?? 0);
    $data_reserva = $_POST['data_reserva'] ?? '';
    $turno        = $_POST['turno'] ?? '';

    if (!$recurso_id || !$data_reserva || !in_array($turno, ['manha','tarde','noite'])) {
        $msg = 'Preencha recurso, data e turno.'; $tipoMsg = 'erro';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO reservas (recurso_id, usuario_id, data_reserva, turno, origem)
                 VALUES (?, ?, ?, ?, "web")');
            $stmt->execute([$recurso_id, $_SESSION['usuario_id'], $data_reserva, $turno]);
            $msg = 'Reserva realizada com sucesso!';
        } catch (PDOException $e) {
            // violação da UNIQUE (recurso já reservado nesse dia/turno)
            if ($e->getCode() == 23000) {
                $msg = 'Este recurso já está reservado nesta data e turno.'; $tipoMsg = 'erro';
            } else {
                $msg = 'Erro ao reservar: ' . $e->getMessage(); $tipoMsg = 'erro';
            }
        }
    }
}

$recursos = $pdo->query(
    "SELECT r.id, r.nome, c.nome AS categoria
     FROM recursos r JOIN categorias c ON c.id = r.categoria_id
     WHERE r.ativo = 1 ORDER BY r.nome")->fetchAll();

require __DIR__ . '/../includes/header.php';
?>
<h1>Fazer Reserva</h1>
<?php if ($msg): ?><div class="alerta <?= $tipoMsg ?>"><?= e($msg) ?></div><?php endif; ?>
<form class="form-card" method="post">
    <div class="form-group">
        <label>Recurso *</label>
        <select name="recurso_id" required>
            <option value="">-- selecione --</option>
            <?php foreach ($recursos as $r): ?>
                <option value="<?= $r['id'] ?>"><?= e($r['nome']) ?> (<?= e($r['categoria']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Data *</label>
        <input type="date" name="data_reserva" required value="<?= e(date('Y-m-d')) ?>">
    </div>
    <div class="form-group">
        <label>Turno *</label>
        <select name="turno" required>
            <option value="manha">Manhã</option>
            <option value="tarde">Tarde</option>
            <option value="noite">Noite</option>
        </select>
    </div>
    <button class="btn btn-verde">Reservar</button>
    <a class="btn btn-cinza" href="historico.php">Ver meu histórico</a>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
