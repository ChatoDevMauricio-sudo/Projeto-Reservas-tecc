<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';

$tituloPagina = 'Dashboard';


$totRecursos   = $pdo->query('SELECT COUNT(*) FROM recursos')->fetchColumn();
$totCategorias = $pdo->query('SELECT COUNT(*) FROM categorias')->fetchColumn();
$totSetores    = $pdo->query('SELECT COUNT(*) FROM setores')->fetchColumn();
$totReservas   = $pdo->query("SELECT COUNT(*) FROM reservas WHERE status='ativa'")->fetchColumn();


$ultimas = $pdo->query(
    "SELECT r.data_reserva, r.turno, r.origem, rec.nome AS recurso, u.nome AS usuario
     FROM reservas r
     JOIN recursos rec ON rec.id = r.recurso_id
     JOIN usuarios u   ON u.id   = r.usuario_id
     WHERE r.status='ativa'
     ORDER BY r.criado_em DESC LIMIT 5"
)->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<h1>Painel Administrativo</h1>

<div class="cards">
    <div class="card"><div class="numero"><?= $totRecursos ?></div><div class="rotulo">Recursos catalogados</div></div>
    <div class="card"><div class="numero"><?= $totReservas ?></div><div class="rotulo">Reservas ativas</div></div>
    <div class="card"><div class="numero"><?= $totCategorias ?></div><div class="rotulo">Categorias</div></div>
    <div class="card"><div class="numero"><?= $totSetores ?></div><div class="rotulo">Setores</div></div>
</div>

<h2 style="margin-top:32px">Últimas reservas</h2>
<table>
    <thead><tr><th>Recurso</th><th>Usuário</th><th>Data</th><th>Turno</th><th>Origem</th></tr></thead>
    <tbody>
    <?php if (!$ultimas): ?>
        <tr><td colspan="5">Nenhuma reserva ainda.</td></tr>
    <?php else: foreach ($ultimas as $r): ?>
        <tr>
            <td><?= e($r['recurso']) ?></td>
            <td><?= e($r['usuario']) ?></td>
            <td><?= date('d/m/Y', strtotime($r['data_reserva'])) ?></td>
            <td><?= e(nomeTurno($r['turno'])) ?></td>
            <td><span class="badge"><?= e($r['origem']) ?></span></td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
