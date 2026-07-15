<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('listar.php');

$id           = (int)($_POST['id'] ?? 0);
$nome         = trim($_POST['nome'] ?? '');
$descricao    = trim($_POST['descricao'] ?? '');
$categoria_id = (int)($_POST['categoria_id'] ?? 0);
$setor_id     = (int)($_POST['setor_id'] ?? 0);
$foto         = $_POST['foto_atual'] ?? '';   // mantém a atual por padrão

if ($nome === '' || !$categoria_id || !$setor_id) {
    die('Preencha nome, categoria e setor.');
}

// ---- Upload da foto (se enviaram um arquivo novo) ----
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $dirUploads = __DIR__ . '/../uploads';
    if (!is_dir($dirUploads)) mkdir($dirUploads, 0777, true);

    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $permitidas)) {
        die('Formato de imagem inválido. Use JPG, PNG, GIF ou WEBP.');
    }
    $novoNome = 'recurso_' . uniqid() . '.' . $ext;
    $destino  = $dirUploads . '/' . $novoNome;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        $foto = $novoNome;
    } else {
        die('Falha ao salvar a imagem.');
    }
}

if ($id) {
    $stmt = $pdo->prepare(
        'UPDATE recursos SET nome=?, descricao=?, foto=?, categoria_id=?, setor_id=? WHERE id=?');
    $stmt->execute([$nome, $descricao, $foto ?: null, $categoria_id, $setor_id, $id]);
} else {
    $stmt = $pdo->prepare(
        'INSERT INTO recursos (nome, descricao, foto, categoria_id, setor_id) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$nome, $descricao, $foto ?: null, $categoria_id, $setor_id]);
}
redirect('listar.php');
