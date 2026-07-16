<?php
/**
 * PAINEL PÚBLICO DE RECURSOS
 * -------------------------------------------------------------------
 * Tela separada do sistema (não faz parte do painel administrativo).
 * Feita para ficar aberta num monitor/TV, sem precisar de login.
 *
 * Regra de "livre" / "em uso":
 *   - Descobre o turno atual (manhã / tarde / noite) pela hora do servidor.
 *   - Para cada recurso ativo, verifica se existe uma reserva com
 *     status = 'ativa' para HOJE nesse turno.
 *   - Se existir -> EM USO (vermelho). Se não -> DISPONÍVEL (verde).
 *
 * Atualiza sozinha a cada 15 segundos (meta refresh).
 * -------------------------------------------------------------------
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/funcoes.php';

/** Descobre o turno atual a partir da hora do servidor. */
function turnoAtual(): string
{
    $hora = (int) date('H');

    if ($hora >= 6 && $hora < 12) {
        return 'manha';
    }
    if ($hora >= 12 && $hora < 18) {
        return 'tarde';
    }
    return 'noite';
}

$turno = turnoAtual();
$hoje  = date('Y-m-d');

// Busca todos os recursos ativos e, junto, a reserva ativa de hoje/turno (se existir)
$sql = "
    SELECT
        r.id,
        r.nome,
        s.nome AS setor_nome,
        res.id AS reserva_id,
        u.nome AS reservado_por
    FROM recursos r
    JOIN setores s ON s.id = r.setor_id
    LEFT JOIN reservas res
           ON res.recurso_id   = r.id
          AND res.data_reserva = :hoje
          AND res.turno        = :turno
          AND res.status       = 'ativa'
    LEFT JOIN usuarios u ON u.id = res.usuario_id
    WHERE r.ativo = 1
    ORDER BY r.id ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['hoje' => $hoje, 'turno' => $turno]);
$recursos = $stmt->fetchAll();

$nomesTurno = ['manha' => 'Manhã', 'tarde' => 'Tarde', 'noite' => 'Noite'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Atualiza a página inteira a cada 15 segundos -->
    <meta http-equiv="refresh" content="15">
    <title>Painel de Recursos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/painel.css">
</head>

<body>

    <header class="header-painel">
        <h1>Painel de Recursos</h1>
        <div class="turno-atual">Turno atual: <strong><?= e($nomesTurno[$turno]) ?></strong> · <?= date('d/m/Y') ?></div>
    </header>

    <div class="painel">

        <?php if (!$recursos): ?>
            <p class="vazio">Nenhum recurso cadastrado.</p>
        <?php else: ?>
            <?php foreach ($recursos as $r): ?>
                <?php
                    $emUso  = !empty($r['reserva_id']);
                    $classe = $emUso ? 'ocupado' : 'livre';
                    $texto  = $emUso ? 'EM USO' : 'DISPONÍVEL';
                    $numero = str_pad((string) $r['id'], 2, '0', STR_PAD_LEFT);
                ?>
                <div class="card-painel <?= $classe ?>">
                    <div class="numero"><?= e($numero) ?></div>
                    <div class="nome"><?= e($r['nome']) ?></div>
                    <div class="setor"><?= e($r['setor_nome']) ?></div>
                    <div class="status"><?= $texto ?></div>
                    <?php if ($emUso && $r['reservado_por']): ?>
                        <div class="reservado-por">com <?= e($r['reservado_por']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <footer class="footer-painel">
        Atualização automática a cada 15 segundos • Painel Informativo
    </footer>

</body>

</html>