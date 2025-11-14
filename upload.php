<?php
$target_dir = __DIR__ . "/uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

if (!isset($_FILES['file'])) {
    echo "Nenhum arquivo enviado.";
    exit;
}

$file = $_FILES['file'];
// fazem verificações básicas
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo "Erro no envio: " . $file['error'];
    exit;
}

// limita tamanho (ex: 50MB)
$maxBytes = 50 * 1024 * 1024;
if ($file['size'] > $maxBytes) {
    echo "Arquivo muito grande. Máx 50MB.";
    exit;
}

// sanitiza nome (remove barras, etc.)
$filename = basename($file['name']);
$filename = preg_replace('/[^A-Za-z0-9_\-\.çÇáÁéÉíÍóÓúÚãÃõÕ ]/', '_', $filename);

$target_file = $target_dir . $filename;

// se já existir, acrescenta sufixo
$base = pathinfo($filename, PATHINFO_FILENAME);
$ext  = pathinfo($filename, PATHINFO_EXTENSION);
$counter = 1;
while (file_exists($target_file)) {
    $filename = $base . '_' . $counter . ($ext ? '.' . $ext : '');
    $target_file = $target_dir . $filename;
    $counter++;
}

if (move_uploaded_file($file['tmp_name'], $target_file)) {
    echo "Arquivo enviado com sucesso!<br><a href='list.php'>Ir para lista</a>";
} else {
    echo "Falha ao mover arquivo.";
}
?>
