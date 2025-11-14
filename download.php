<?php
$uploads = realpath(__DIR__ . '/uploads/') . DIRECTORY_SEPARATOR;

function safe_path($uploads, $requested) {
    // basename + evita traversal
    $name = basename($requested);
    $full = realpath($uploads . $name);
    // verifica se o realpath existe e começa com a pasta uploads
    if ($full === false) return false;
    if (strpos($full, $uploads) !== 0) return false;
    if (!is_file($full)) return false;
    return $full;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método não permitido";
    exit;
}

// 1) botão de 'baixar um' (single) -> força download do arquivo
if (!empty($_POST['single'])) {
    $requested = $_POST['single'];
    $file = safe_path($uploads, $requested);
    if (!$file) {
        echo "Arquivo inválido.";
        exit;
    }
    // serve forçando download
    $filename = basename($file);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

// 2) baixar selecionados -> cria ZIP temporário
if (!empty($_POST['action']) && $_POST['action'] === 'download_selected') {
    if (empty($_POST['files']) || !is_array($_POST['files'])) {
        echo "Nenhum arquivo selecionado.";
        exit;
    }
    $files = $_POST['files'];
    $validFiles = [];
    foreach ($files as $f) {
        $p = safe_path($uploads, $f);
        if ($p) $validFiles[$f] = $p;
    }
    if (count($validFiles) === 0) {
        echo "Nenhum arquivo válido selecionado.";
        exit;
    }

    // cria zip temporário
    $tmpZip = tempnam(sys_get_temp_dir(), 'zip_') . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($tmpZip, ZipArchive::CREATE) !== true) {
        echo "Falha ao criar ZIP.";
        exit;
    }
    foreach ($validFiles as $name => $path) {
        $zip->addFile($path, $name);
    }
    $zip->close();

    // envia zip
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="arquivos_selecionados.zip"');
    header('Content-Length: ' . filesize($tmpZip));
    readfile($tmpZip);

    // limpa
    @unlink($tmpZip);
    exit;
}

echo "Ação inválida.";
exit;
