<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hahú sesaun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ligasaun ba baze de dadus (database)
$host = 'localhost';
$dbname = 'sistem_repositori_jurnal';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ligasaun falla: " . $e->getMessage());
}

// Verifika login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Verifika se uzuáriu ne'e Autór ka lae
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role != 'author' && $user_role != 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

// Prosesu formuláriu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $topiku = isset($_POST['topiku']) ? trim($_POST['topiku']) : '';
    $deskripsaun = isset($_POST['deskripsaun']) ? trim($_POST['deskripsaun']) : '';
    $uploaded_by = $_SESSION['user_id'];
    
    // Validasaun
    if (empty($topiku)) {
        $error = 'Títulu jornál tenke hatama';
    } elseif (empty($deskripsaun)) {
        $error = 'Deskripsaun tenke hatama';
    } elseif (!isset($_FILES['file']) || empty($_FILES['file']['name'])) {
        $error = 'Favór hili arkivu ida uluk';
    } elseif ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $error = 'Arkivu boot liu (másimu ' . ini_get('upload_max_filesize') . ')';
                break;
            case UPLOAD_ERR_NO_FILE:
                $error = 'Laiha arkivu ne\'ebé hili';
                break;
            default:
                $error = 'Error karga (upload): ' . $_FILES['file']['error'];
        }
    } else {
        $file = $_FILES['file'];
        $uploadDir = __DIR__ . '/../uploads/journals/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        if (!is_writable($uploadDir)) {
            $error = 'Pasta uploads la bele hakerek ba laran. Favór set permission 777';
        } else {
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', pathinfo($file['name'], PATHINFO_FILENAME)) . '.' . $fileExt;
            $targetFile = $uploadDir . $fileName;
            
            $allowed = ['pdf', 'doc', 'docx'];
            if (!in_array($fileExt, $allowed)) {
                $error = 'Só arkivu PDF, DOC, DOCX de\'it mak bele. Ita-nian mak: ' . $fileExt;
            } elseif ($file['size'] > 10 * 1024 * 1024) {
                $error = 'Arkivu másimu 10MB. Ita-nian: ' . round($file['size'] / 1024 / 1024, 2) . 'MB';
            } elseif (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $file_path = 'uploads/journals/' . $fileName;
                $stmt = $pdo->prepare("INSERT INTO journals (topiku, deskripsaun, file_path, uploaded_by) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$topiku, $deskripsaun, $file_path, $uploaded_by])) {
                    $_SESSION['upload_success'] = 'Jornál karga (upload) ho susesu!';
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = 'Falla uainhira rai ba baze de dadus';
                    if (file_exists($targetFile)) unlink($targetFile);
                }
            } else {
                $error = 'Falla uainhira upload arkivu';
            }
        }
    }
}

include '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2>
                <i class="fas fa-upload me-2 text-primary"></i>
                Karga (Upload) Jornál Foun
            </h2>
            <p class="text-muted">Karga Ita-nia jornál sientífiku atu fahe ba lee-na'in sira</p>
        </div>
        <div class="col-auto">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Fila ba Dashboard
            </a>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if ($error): ?>
                <div class="alert alert-danger shadow-sm border-0">
                    <i class="fas fa-exclamation-circle me-2"></i> 
                    <strong>Falla!</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['upload_success'])): ?>
                <div class="alert alert-success shadow-sm border-0">
                    <i class="fas fa-check-circle me-2"></i> 
                    <strong>Susesu!</strong> <?= $_SESSION['upload_success'] ?>
                    <?php unset($_SESSION['upload_success']); ?>
                </div>
            <?php endif; ?>
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-upload me-2"></i>
                        Formuláriu Karga Jornál
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Títulu Jornál <span class="text-danger">*</span></label>
                            <input type="text" name="topiku" class="form-control" placeholder="Hatama títulu jornál nian" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsaun <span class="text-danger">*</span></label>
                            <textarea name="deskripsaun" class="form-control" rows="5" placeholder="Hatama deskripsaun badak..." required></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Arkivu Jornál <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Formatu: PDF, DOC, DOCX | Másimu: 10MB</small>
                            <div id="fileInfo" class="mt-2"></div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Upload Agora
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Kansela
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-3 bg-light border-0">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="fas fa-info-circle me-2 text-info"></i>Informasaun:</h6>
                    <ul class="mb-0 small text-muted">
                        <li>Verifika katak arkivu iha formatu PDF, DOC, ka DOCX</li>
                        <li>Medida arkivu labele liu 10MB</li>
                        <li>Títulu jornál tenke moos no klaru</li>
                        <li>Deskripsaun jornál mínimu karater 50</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.querySelector('input[name="file"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const info = document.getElementById('fileInfo');
    
    if (file) {
        const sizeMB = (file.size / 1024 / 1024).toFixed(2);
        let html = `<div class="alert alert-info py-2 mb-0 mt-2 small shadow-sm">`;
        html += `<i class="fas fa-file me-2"></i><strong>Arkivu:</strong> ${file.name} (${sizeMB} MB)`;
        html += `</div>`;
        info.innerHTML = html;
    } else {
        info.innerHTML = '';
    }
});
</script>
