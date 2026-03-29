<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Hola dadus uza-na'in (user) husi baze de dadus
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Atualiza perfil (naran kompletu)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $naran_kompletu = sanitize($_POST['naran_kompletu']);
    
    $stmt = $pdo->prepare("UPDATE users SET naran_kompletu = ? WHERE id = ?");
    if ($stmt->execute([$naran_kompletu, $user_id])) {
        $_SESSION['user_naran'] = $naran_kompletu;
        $success = 'Perfil atualiza ho susesu!';
        
        // Foti fali dadus foun atu hatudu iha formuláriu
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    } else {
        $error = 'Falla uainhira atualiza perfil';
    }
}

// Troka parola (password)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verifika parola agora nian (atuál)
    if (password_verify($current_password, $user['password'])) {
        if (strlen($new_password) < 6) {
            $error = 'Parola foun tenke mínimu iha karater 6';
        } elseif ($new_password != $confirm_password) {
            $error = 'Konfirmasaun parola la hanesan ho parola foun';
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$hashed_password, $user_id])) {
                $success = 'Parola troka ho susesu!';
            } else {
                $error = 'Falla uainhira troka parola';
            }
        }
    } else {
        $error = 'Parola atuál nian sala';
    }
}

require_once 'includes/navbar.php';
?>

<main class="container py-4">
    <div class="row">
        <!-- Kartu Informasaun Uzuáriu -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-white py-4">
                    <i class="fas fa-user-circle fa-4x text-primary"></i>
                    <h5 class="mt-3 mb-1"><?= htmlspecialchars($_SESSION['user_naran']) ?></h5>
                    <span class="badge bg-primary text-uppercase"><?= $_SESSION['user_role'] ?></span>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong><i class="fas fa-at me-2"></i>Naran Uzuáriu:</strong><br> <?= htmlspecialchars($user['username']) ?></p>
                    <p class="mb-0"><strong><i class="fas fa-calendar-alt me-2"></i>Membru dezde:</strong><br> <?= date('d F Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>
        
        <!-- Formuláriu Edita Perfil no Troka Parola -->
        <div class="col-md-8">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i><?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Edita Perfil -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2 text-primary"></i>Edita Perfil</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Naran Kompletu</label>
                            <input type="text" name="naran_kompletu" class="form-control" 
                                   value="<?= htmlspecialchars($user['naran_kompletu']) ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Atualiza Perfil
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Troka Parola -->
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Troka Parola</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Parola Atuál (Agora nian)</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Parola Foun</label>
                            <input type="password" name="new_password" class="form-control" required>
                            <div class="form-text">Mínimu karater 6.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirma Parola Foun</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-warning">
                            <i class="fas fa-sync-alt me-1"></i> Troka Parola
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
