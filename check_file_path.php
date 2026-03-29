<?php
session_start();
require_once 'config/database.php';

// URL Baze (ajusta tuir ita-nian)
$base_url = '/journal-repository/';
?>
<!DOCTYPE html>
<html lang="tp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifika Dalan Arkivu (File Path)</title>
    <link href="https://cdn.jsdelivr.net" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white p-3">
            <h4 class="mb-0"><i class="fas fa-search-location me-2"></i> Verifika Dalan Arkivu Jornal nian</h4>
        </div>
        <div class="card-body">
            <?php
            // Hola jornal hotu husi baze de dadus
            $stmt = $pdo->query("SELECT id, topiku, file_path FROM journals");
            $journals = $stmt->fetchAll();

            if (empty($journals)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Laiha jornal ne'ebé hetan. Favór upload jornal ida uluk.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-table">
                        <thead>
                            <tr class="table-secondary">
                                <th>ID</th>
                                <th>Tópiku / Títulu</th>
                                <th>Dalan (Baze de Dadus)</th>
                                <th>Status Arkivu</th>
                                <th>Dalan Kompletu (Server)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($journals as $journal): 
                                $paths = [
                                    'path1' => __DIR__ . '/' . $journal['file_path'],
                                    'path2' => dirname(__DIR__) . '/' . $journal['file_path'],
                                    'path3' => __DIR__ . '/../' . $journal['file_path'],
                                ];
                                
                                $found = false;
                                $found_path = '';
                                foreach ($paths as $path) {
                                    if (file_exists($path)) {
                                        $found = true;
                                        $found_path = $path;
                                        break;
                                    }
                                }
                            ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= $journal['id'] ?></span></td>
                                <td><strong><?= htmlspecialchars($journal['topiku']) ?></strong></td>
                                <td><code><?= htmlspecialchars($journal['file_path']) ?></code></td>
                                <td>
                                    <?php if ($found): ?>
                                        <span class="text-success fw-bold"><i class="fas fa-check-circle"></i> IHA</span>
                                    <?php else: ?>
                                        <span class="text-danger fw-bold"><i class="fas fa-times-circle"></i> LAIHA</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted text-break"><code><?= $found ? htmlspecialchars($found_path) : 'Arkivu la hetan iha sistema' ?></code></small></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="mt-4 p-3 bg-white border rounded">
                <h5 class="border-bottom pb-2"><i class="fas fa-info-circle"></i> Informasaun Debug:</h5>
                <ul class="list-unstyled mb-0">
                    <li><strong>Diretóriu agora:</strong> <code><?= __DIR__ ?></code></li>
                    <li><strong>Document Root:</strong> <code><?= $_SERVER['DOCUMENT_ROOT'] ?></code></li>
                </ul>
            </div>
        </div>
        <div class="card-footer bg-white border-0 p-3">
            <a href="index.php" class="btn btn-outline-primary me-2"><i class="fas fa-home"></i> Fila ba Oin</a>
            <a href="login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Tama (Login)</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net"></script>
</body>
</html>
