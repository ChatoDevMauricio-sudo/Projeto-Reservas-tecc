<?php
require_once __DIR__ . '/db_config.php';

$pdo = conectarBanco();

// Busca todos os recursos, já trazendo o nome do setor responsável
$sql = "
    SELECT
        r.id_recurso,
        r.nome,
        r.status,
        r.localizacao,
        s.nome AS setor_nome
    FROM recurso r
    LEFT JOIN setor s ON s.id_setor = r.id_setor
    ORDER BY r.id_recurso ASC
";

$stmt = $pdo->query($sql);
$recursos = $stmt->fetchAll();

/**
 * Converte o status vindo do banco (enum) para:
 * - classe CSS usada no card
 * - texto exibido no painel
 */
function statusParaClasse(string $status): string
{
    switch ($status) {
        case 'Disponível':
            return 'livre';
        case 'Em uso':
            return 'ocupado';
        case 'Manutenção':
            return 'manutencao';
        case 'Inativo':
        default:
            return 'inativo';
    }
}

function statusParaTexto(string $status): string
{
    switch ($status) {
        case 'Disponível':
            return 'DISPONÍVEL';
        case 'Em uso':
            return 'EM USO';
        case 'Manutenção':
            return 'MANUTENÇÃO';
        case 'Inativo':
        default:
            return 'INATIVO';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Auto-refresh da página inteira a cada 15 segundos -->
    <meta http-equiv="refresh" content="15">
    <title>Painel de Equipamentos</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="header-painel">
        <h1>Painel de reservas</h1>
    </header>

    <div class="painel">

        <?php if (empty($recursos)): ?>
            <p style="text-align:center; width:100%;">Nenhum recurso cadastrado.</p>
        <?php else: ?>
            <?php foreach ($recursos as $recurso): ?>
                <?php
                    $classe = statusParaClasse($recurso['status']);
                    $texto  = statusParaTexto($recurso['status']);
                    $numero = str_pad((string) $recurso['id_recurso'], 2, '0', STR_PAD_LEFT);
                ?>
                <div class="card-painel <?= $classe ?>">
                    <div class="numero"><?= htmlspecialchars($numero) ?></div>
                    <div class="nome"><?= htmlspecialchars($recurso['nome']) ?></div>
                    <?php if (!empty($recurso['setor_nome'])): ?>
                        <div class="setor"><?= htmlspecialchars($recurso['setor_nome']) ?></div>
                    <?php endif; ?>
                    <div class="status"><?= $texto ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <footer class="footer-painel">
        Atualização automática a cada 15 segundos • Painel Informativo
    </footer>

</body>

</html>