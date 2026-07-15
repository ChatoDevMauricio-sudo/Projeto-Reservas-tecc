<?php
require_once __DIR__ . '/_config.php';

$categoria_id = (int)($_GET['categoria_id'] ?? 0);
$busca        = trim($_GET['busca'] ?? '');

$sql = "SELECT r.id, r.nome, r.descricao, r.foto,
               c.id AS categoria_id, c.nome AS categoria,
               s.id AS setor_id, s.nome AS setor, s.responsavel
        FROM recursos r
        JOIN categorias c ON c.id = r.categoria_id
        JOIN setores s    ON s.id = r.setor_id
        WHERE r.ativo = 1";
$params = [];
if ($categoria_id) { $sql .= ' AND r.categoria_id = ?'; $params[] = $categoria_id; }
if ($busca !== '') { $sql .= ' AND r.nome LIKE ?';      $params[] = '%' . $busca . '%'; }
$sql .= ' ORDER BY r.nome';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

$base = urlBase();
foreach ($rows as &$r) {
    $r['id']           = (int)$r['id'];
    $r['categoria_id'] = (int)$r['categoria_id'];
    $r['setor_id']     = (int)$r['setor_id'];
    // URL completa da imagem (ou placeholder vazio)
    $r['foto_url'] = $r['foto'] ? $base . $r['foto'] : '';
}
responder(['status' => 'ok', 'total' => count($rows), 'recursos' => $rows]);
