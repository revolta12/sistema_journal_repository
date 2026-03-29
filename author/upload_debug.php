<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hahú sesaun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hatama baze de dadus uluk
require_once '../config/database.php';

// Define funsun sira
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAuthor() {
    return isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'author' || $_SESSION['user_role'] === 'admin');
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function uploadFile($file, $targetDir) {
    $targetDir = rtrim($targetDir, '/') . '/';
    
    // Kria pasta se seidauk iha
    if (!file_exists($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            return ['error' => 'Falla uainhira kria pasta uploads'];
        }
    }
    
    // Verifika se pasta bele hakerek (writable)
    if (!is_writable($targetDir)) {
        return ['error' => 'Pasta uploads la bele hakerek ba laran'];
    }
    
    // Verifika se iha arkivu ne'ebé upload
    if (!isset($file) || $file['error'] != UPLOAD_ERR_OK) {
        return ['error' => 'Laiha arkivu ne\'ebé upload ka iha error uainhira upload'];
    }
    
    // Jeru naran arkivu ne'ebé úniku
    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
    $targetFile = $targetDir . $fileName;
    
    // Verifika tipu arkivu
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['pdf', 'doc', 'docx'];
    
    if (!in_array($fileType, $allowedTypes)) {
        return ['error' => 'Só arkivu PDF, DOC, ka DOCX de\'it mak bele upload'];
    }
    
    // Verifika medida arkivu (10MB)
    if ($file['size'] > 10 * 1024 * 1024) {
        return ['error' => 'Medida arkivu boot liu, másimu 10MB'];
    }
    
    // Upload arkivu
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => $targetFile];
    }
    
    return ['error' => 'Falla uainhira upload arkivu'];
}

// Verifika login
if (!isLoggedIn() || !isAuthor()) {
    redirect('../login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $topiku = sanitize($_POST['topiku']);
    $deskripsaun = sanitize($_POST['deskripsaun']);
    $uploaded_by = $_SESSION['user_id'];
    
    // Validasaun
    if (empty($topiku)) {
        $error = 'Títulu jornál tenke prienxe';
    } elseif (empty($deskripsaun)) {
        $error = 'Deskripsaun tenke prienxe';
    } elseif (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
        $error = 'Arkivu jornál tenke upload';
    } else {
        // Prosesu upload
        $uploadResult = uploadFile($_FILES['file'], '../uploads/journals/');
        
        if (isset($uploadResult['error'])) {
            $error = $uploadResult['error'];
        } else {
            // Rai ba baze de dadus
            $file_path = str_replace('../', '', $uploadResult['success']);
            
            $stmt = $pdo->prepare("INSERT INTO journals (topiku, deskripsaun, file_path, uploaded_by) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$topiku, $deskripsaun, $file_path, $uploaded_by])) {
                $_SESSION['upload_success'] = 'Jornál karga (upload) ho susesu!';
                redirect('dashboard.php');
            } else {
                $error = 'Falla uainhira rai dadus jornál ba baze de dadus';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karga Jornál - Sistema Repozitóriu Jornál</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .upload-card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-book-open me-2"></i>Repozitóriu Jornál
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-light">
                            <i class="fas fa-user me-1"></i> <?= htmlspecialchars($_SESSION['user_naran'] ?? 'Autór') ?>
                            <span class="badge bg-info text-dark">Autór</span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Sai (Logout)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card upload-card border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-upload me-2"></i>Karga (Upload) Jornál Foun</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Títulu / Tópiku Jornál <span class="text-danger">*</span></label>
                                <input type="text" name="topiku" class="form-control" 
                                       placeholder="Hatama títulu jornál nian" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsaun <span class="text-danger">*</span></label>
                                <textarea name="deskripsaun" class="form-control" rows="5" 
                                          placeholder="Hatama deskripsaun badak kona-ba jornál ne'e" required></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Arkivu Jornál <span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control" 
                                       accept=".pdf,.doc,.docx" required>
                                <small class="text-muted">
                                    Format: PDF, DOC, DOCX | Medida másimu: 10MB
                                </small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-cloud-upload-alt me-2"></i>Karga Agora
                                </button>
                                <a href="dashboard.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Fila fali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
