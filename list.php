<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Meus Arquivos</title>
  <style>
    :root {
      --bg: #f5f7fa;
      --card-bg: #fff;
      --accent: #007bff;
      --text: #333;
      --shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    * {
      box-sizing: border-box;
    }

    body {
      background: var(--bg);
      font-family: "Segoe UI", sans-serif;
      color: var(--text);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .container {
      background: var(--card-bg);
      padding: 30px;
      border-radius: 16px;
      box-shadow: var(--shadow);
      margin-top: 50px;
      width: 90%;
      max-width: 600px;
    }

    h1 {
      text-align: center;
      color: var(--accent);
      margin-bottom: 20px;
    }

    .file-list a {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #f9f9f9;
      margin: 8px 0;
      padding: 12px 16px;
      border-radius: 10px;
      text-decoration: none;
      color: var(--text);
      transition: all 0.2s ease-in-out;
    }

    .file-list a:hover {
      background: var(--accent);
      color: white;
      transform: translateY(-2px);
    }

    .file-list span {
      font-size: 0.9em;
      color: #666;
    }

    .footer {
      text-align: center;
      margin-top: 20px;
    }

    .footer a {
      color: var(--accent);
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>📁 Meus Arquivos</h1>

    <div class="file-list">
      <?php
      $dir = __DIR__ . '/uploads/';
      if (!is_dir($dir)) {
        echo "<p style='color:red;'>A pasta de uploads não existe!</p>";
        exit;
      }

      $files = array_diff(scandir($dir), ['.', '..']);

      if (empty($files)) {
        echo "<p style='text-align:center;'>Nenhum arquivo enviado ainda.</p>";
      } else {
        foreach ($files as $file) {
          $path = 'uploads/' . rawurlencode($file);
          $safeName = htmlspecialchars($file, ENT_QUOTES, 'UTF-8');
          $size = filesize($dir . $file);
          $sizeMB = round($size / 1048576, 2);
          echo "<a href='$path' download>
                  <strong>$safeName</strong>
                  <span>{$sizeMB} MB</span>
                </a>";
        }
      }
      ?>
    </div>

    <div class="footer">
      <a href="index.html">⬅ Voltar</a>
    </div>
  </div>
</body>
</html>
