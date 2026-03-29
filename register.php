<?php
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Se login ona, labele rejista fali
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $naran_kompletu = sanitize($_POST['naran_kompletu']);
    $role = sanitize($_POST['role']);
    
    // Validasaun sira
    if (strlen($username) < 3) {
        $error = 'Naran uza-na\'in (username) tenke mínimu karater 3.';
    } elseif (strlen($password) < 6) {
        $error = 'Parola (password) tenke mínimu karater 6.';
    } elseif ($password != $confirm_password) {
        $error = 'Parola ho konfirmasaun la hanesan.';
    } elseif (empty($naran_kompletu)) {
        $error = 'Naran kompletu tenke hatama.';
    } else {
        $result = registerUser($username, $password, $naran_kompletu, $role);
        if (isset($result['success'])) {
            $_SESSION['register_success'] = 'Konta kria ho susesu! Favór tama (login) agora.';
            redirect('login.php');
        } else {
            $error = $result['error'];
        }
    }
}

require_once 'includes/navbar.php';
?>

<!-- Hatama CSS espesiál ba Login/Register -->
<link rel="stylesheet" href="assets/css/auth.css">

<main class="container py-5 auth-container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card shadow-lg border-0">
                <div class="card-header text-center py-4 bg-transparent border-0">
                    <div class="auth-icon-circle mb-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Rejista Konta Foun</h3>
                    <p class="text-muted small">Kria Ita-nia konta atu bele asesu ba jornál sira</p>
                </div>
                
                <div class="card-body px-4 pb-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="naran_kompletu" class="form-label small fw-bold">
                                <i class="fas fa-id-card me-2 text-danger"></i>Naran Kompletu
                            </label>
                            <input type="text" class="form-control auth-input" id="naran_kompletu" name="naran_kompletu" 
                                   placeholder="Hatama Ita-nia naran..." required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label small fw-bold">
                                <i class="fas fa-at me-2 text-danger"></i>Naran Uzuáriu (Username)
                            </label>
                            <input type="text" class="form-control auth-input" id="username" name="username" 
                                   placeholder="Ez: joao25" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label small fw-bold">
                                <i class="fas fa-user-tag me-2 text-danger"></i>Rejista nu'udar
                            </label>
                            <select class="form-select auth-input" id="role" name="role" required>
                                <option value="reader">Lee-na'in (Reader)</option>
                                <option value="author">Autór (Author/Upload Jornál)</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label small fw-bold">
                                    <i class="fas fa-lock me-2 text-danger"></i>Parola
                                </label>
                                <input type="password" class="form-control auth-input" id="password" name="password" 
                                       placeholder="******" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label small fw-bold">
                                    <i class="fas fa-shield-alt me-2 text-danger"></i>Konfirma Parola
                                </label>
                                <input type="password" class="form-control auth-input" id="confirm_password" name="confirm_password" 
                                       placeholder="******" required>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-danger btn-auth py-2 fw-bold shadow-sm text-white">
                                <i class="fas fa-user-plus me-2"></i>REJISTA AGORA
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">Iha ona konta? 
                            <a href="login.php" class="text-danger fw-bold text-decoration-none border-bottom border-danger">Tama Iha Ne'e</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
