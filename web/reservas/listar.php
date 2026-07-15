<?php
// Admin: ver TODAS as reservas (web e mobile)
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();
$tituloPagina = 'Todas as Reservas';

$busca = trim($_GET['busca'] ?? '');
$sql = "SELECT r.*, rec.nome AS recurso, u.nome AS usuario
        FROM reservas r
        JOIN recursos rec ON rec.id = r.recurso_id
        JOIN usuarios u   ON u.id   = r.usuario_id";
if ($busca !== '') {
    $sql .= ' WHERE rec.nome LIKE ? OR u.nome LIKE ? ORDER BY r.data_reserva DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['%' . $busca . '%', '%' . $busca . '%']);
    $lista = $stmt->fetchAll();
} else {
    $lista = $pdo->query($sql . ' ORDER BY r.data_reserva DESC, r.criado_em DESC')->fetchAll();
}
require __DIR__ . '/../includes/header.php';
?>
<h1>Todas as Reservas</h1>
<form class="busca" method="get" style="margin-bottom:16px">
    <input type="text" name="busca" placeholder="Buscar por recurso ou usuario..." value="<?= e($busca) ?>">
    <button class="btn btn-cinza">Buscar</button>
</form>
<table>
    <thead><tr><th>Recurso</th><th>Usuario</th><th>Data</th><th>Turno</th><th>Origem</th><th>Status</th><th style="width:120px">Acao</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?>
        <tr><td colspan="7">Nenhuma reserva encontrada.</td></tr>
    <?php else: foreach ($lista as $r): ?>
        <tr>
            <td><?= e($r['recurso']) ?></td>
            <td><?= e($r['usuario']) ?></td>
            <td><?= date('d/m/Y', strtotime($r['data_reserva'])) ?></td>
            <td><?= e(nomeTurno($r['turno'])) ?></td>
            <td><span class="badge"><?= e($r['origem']) ?></span></td>
            <td><?= $r['status']==='ativa' ? 'Ativa' : 'Cancelada' ?></td>
            <td>
                <a class="btn btn-peq btn-excluir" href="excluir.php?id=<?= $r['id'] ?>&voltar=listar"
                   onclick="return confirm('Excluir esta reserva?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../includes/footer.php'; ?>
