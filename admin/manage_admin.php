<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'sistem_repositori_jurnal';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksaun database falha: " . $e->getMessage());
}

// Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$message = '';
$error = '';
$edit_admin = null;

// CREATE
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $naran = trim($_POST['naran_kompletu']);
    
    $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        $error = 'Username admin já uza ona!';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin (username, password, naran_kompletu) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $hash, $naran])) {
            $message = 'Admin foun konsege aumenta!';
        } else {
            $error = 'Falha aumenta admin!';
        }
    }
}

// UPDATE
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['id'];
    $naran = trim($_POST['naran_kompletu']);
    
    $stmt = $pdo->prepare("UPDATE admin SET naran_kompletu = ? WHERE id = ?");
    if ($stmt->execute([$naran, $id])) {
        $message = 'Dadus admin konsege atualiza!';
        if ($_SESSION['user_id'] == $id) {
            $_SESSION['user_naran'] = $naran;
        }
        $edit_admin = null;
    } else {
        $error = 'Falha atualiza admin!';
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $total = $pdo->query("SELECT COUNT(*) FROM admin")->fetchColumn();
    
    if ($total <= 1) {
        $error = 'La bele hamos admin ikus!';
    } elseif ($id == $_SESSION['user_id']) {
        $error = 'La bele hamos ita boot nia an!';
    } else {
        $stmt = $pdo->prepare("DELETE FROM admin WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = 'Admin konsege hamos!';
        } else {
            $error = 'Falha hamos admin!';
        }
    }
}

// RESET PASSWORD
if (isset($_POST['action']) && $_POST['action'] == 'reset_password') {
    $id = $_POST['id'];
    $new_pass = $_POST['new_password'];
    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE id = ?");
    if ($stmt->execute([$hash, $id])) {
        $message = "Password reset! Password foun: $new_pass";
    } else {
        $error = 'Falha reset password!';
    }
}

// GET for edit
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_admin = $stmt->fetch();
}

// Get all admins
$admins = $pdo->query("SELECT * FROM admin ORDER BY created_at DESC")->fetchAll();

include '../includes/navbar.php';
?>

<style>
/* Simple styles */
.admin-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.2rem;
    margin-bottom: 1.2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.2s;
}
.admin-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}
.admin-avatar {
    width: 50px;
    height: 50px;
    background: #4361ee;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
    color: white;
    margin-right: 12px;
}
.btn-sm {
    padding: 4px 10px;
    font-size: 0.8rem;
}
.btn-group-custom {
    display: flex;
    gap: 6px;
    margin-top: 12px;
}
.badge-current {
    background: #4cc9f0;
    color: #1a2c3e;
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: 20px;
}
</style>

<div class="container-fluid py-3">
    <!-- Header Simple -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><i class="fas fa-user-shield me-2 text-primary"></i>Jere Administradór</h4>
            <small class="text-muted">Tambah, edita, hamos admin sira</small>
        </div>
        <div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-1"></i>Tambah
            </button>
            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Fila
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show py-2">
            <i class="fas fa-check-circle me-1"></i> <?= $message ?>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show py-2">
            <i class="fas fa-exclamation-circle me-1"></i> <?= $error ?>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-primary text-white py-2">
                        <h6 class="modal-title"><i class="fas fa-user-plus me-1"></i>Tambah Admin</h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-2">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-2">
                            <label class="form-label small">Username</label>
                            <input type="text" name="username" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Naran Kompletu</label>
                            <input type="text" name="naran_kompletu" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <?php if ($edit_admin): ?>
    <div class="modal fade show" id="editModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-warning text-dark py-2">
                        <h6 class="modal-title"><i class="fas fa-edit me-1"></i>Edita Admin</h6>
                        <a href="manage_admin.php" class="btn-close btn-sm"></a>
                    </div>
                    <div class="modal-body py-2">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= $edit_admin['id'] ?>">
                        <div class="mb-2">
                            <label class="form-label small">Username</label>
                            <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($edit_admin['username']) ?>" disabled>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Naran Kompletu</label>
                            <input type="text" name="naran_kompletu" class="form-control form-control-sm" value="<?= htmlspecialchars($edit_admin['naran_kompletu']) ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <a href="manage_admin.php" class="btn btn-secondary btn-sm">Batal</a>
                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-info text-white py-2">
                        <h6 class="modal-title"><i class="fas fa-key me-1"></i>Reset Password</h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-2">
                        <input type="hidden" name="action" value="reset_password">
                        <input type="hidden" name="id" id="reset_id">
                        <div class="mb-2">
                            <label class="form-label small">Password Foun</label>
                            <input type="text" name="new_password" class="form-control form-control-sm" value="<?= rand(100000, 999999) ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info btn-sm text-white">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Admin List -->
    <div class="row">
        <?php foreach ($admins as $admin): ?>
        <div class="col-md-6 col-lg-4">
            <div class="admin-card">
                <div class="d-flex align-items-center">
                    <div class="admin-avatar text-center">
                        <?= strtoupper(substr($admin['naran_kompletu'], 0, 1)) ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($admin['naran_kompletu']) ?></strong><br>
                                <small class="text-muted">@<?= htmlspecialchars($admin['username']) ?></small>
                            </div>
                            <?php if ($admin['id'] == $_SESSION['user_id']): ?>
                                <span class="badge-current"><i class="fas fa-user-check me-1"></i>Ita</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-calendar-alt me-1"></i> <?= date('d/m/Y', strtotime($admin['created_at'])) ?>
                        </small>
                    </div>
                </div>
                <div class="btn-group-custom">
                    <a href="?edit=<?= $admin['id'] ?>" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit"></i> Edita
                    </a>
                    <button onclick="resetPass(<?= $admin['id'] ?>)" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-key"></i> Reset
                    </button>
                    <?php if ($admin['id'] != $_SESSION['user_id'] && count($admins) > 1): ?>
                        <a href="?delete=<?= $admin['id'] ?>" class="btn btn-outline-danger btn-sm" 
                           onclick="return confirm('Hamos admin <?= htmlspecialchars($admin['naran_kompletu']) ?>?')">
                            <i class="fas fa-trash"></i> Hamos
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Info Simple -->
    <div class="alert alert-info py-2 mt-2 small">
        <i class="fas fa-info-circle me-1"></i> 
        <strong>Info:</strong> La bele hamos admin ikus ka akun rasik. Username labele troka.
    </div>
</div>

<script>
function resetPass(id) {
    document.getElementById('reset_id').value = id;
    new bootstrap.Modal(document.getElementById('resetModal')).show();
}
</script>

<?php include '../includes/footer.php'; ?>