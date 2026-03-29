<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hahú sesaun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hatama baze de dadus ULUK
require_once __DIR__ . '/../config/database.php';

// Hatama funsun sira
require_once __DIR__ . '/../includes/functions.php';

// Define funsun sira se seidauk iha
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}

// Verifika se login ona no nu'udar admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Foti Estatístika sira
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'author'");
$totalAuthors = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'reader'");
$totalReaders = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM journals");
$totalJournals = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM journal_stats WHERE type = 'view'");
$totalViews = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM journal_stats WHERE type = 'download'");
$totalDownloads = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM admin");
$totalAdmins = $stmt->fetch()['total'];

$stmt = $pdo->query("
    SELECT j.topiku, COUNT(js.id) as total_views
    FROM journals j
    LEFT JOIN journal_stats js ON j.id = js.journal_id AND js.type = 'view'
    GROUP BY j.id
    ORDER BY total_views DESC
    LIMIT 5
");
$popularJournals = $stmt->fetchAll();

// Hatama Navbar
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fade-in">
                <i class="fas fa-user-shield me-2 text-primary"></i>
                Dashboard Administradór
            </h2>
            <p class="text-muted">Benvindu fali, <strong><?= htmlspecialchars($_SESSION['user_naran'] ?? 'Admin') ?></strong></p>
        </div>
    </div>
    
    <!-- Kartu Estatístika sira -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <div class="h3 fw-bold mb-0"><?= $totalAuthors + $totalReaders ?></div>
                <div class="text-muted small">Totál Uzuáriu</div>
                <hr class="my-2">
                <small class="text-muted"><?= $totalAuthors ?> Autór, <?= $totalReaders ?> Lee-na'in</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <i class="fas fa-user-shield fa-2x text-danger mb-2"></i>
                <div class="h3 fw-bold mb-0"><?= $totalAdmins ?></div>
                <div class="text-muted small">Totál Administradór</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <i class="fas fa-book-open fa-2x text-success mb-2"></i>
                <div class="h3 fw-bold mb-0"><?= $totalJournals ?></div>
                <div class="text-muted small">Totál Jornál</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                <div class="h3 fw-bold mb-0"><?= number_format($totalViews + $totalDownloads) ?></div>
                <div class="text-muted small">Totál Atividade</div>
                <hr class="my-2">
                <small class="text-muted"><?= number_format($totalViews) ?> View, <?= number_format($totalDownloads) ?> Download</small>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Jornál Populár -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-line me-2 text-danger"></i>Jornál Populár</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($popularJournals)): ?>
                        <p class="text-muted text-center py-4">Seidauk iha dadus</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($popularJournals as $journal): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <div class="text-truncate me-2" style="max-width: 70%;">
                                        <i class="fas fa-file-alt text-muted me-2"></i>
                                        <strong><?= htmlspecialchars($journal['topiku']) ?></strong>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill">
                                        <i class="fas fa-eye me-1 text-primary"></i> <?= $journal['total_views'] ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Menu Admin -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-cog me-2 text-secondary"></i>Menu Administrasaun</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <!-- Button Kelola Admin - BARU! -->
                        <a href="manage_admin.php" class="btn btn-danger py-3 text-start fw-bold">
                            <i class="fas fa-user-shield me-3 fa-lg"></i> 
                            <span>Jere Administradór Sira</span>
                            <span class="badge bg-light text-dark float-end mt-1"><?= $totalAdmins ?> Admin</span>
                        </a>
                        <a href="manage_users.php" class="btn btn-outline-primary py-3 text-start">
                            <i class="fas fa-users me-3"></i> Jere Uzuáriu Sira
                        </a>
                        <a href="manage_journals.php" class="btn btn-outline-success py-3 text-start">
                            <i class="fas fa-book-open me-3"></i> Jere Jornál Sira
                        </a>
                        <a href="statistics.php" class="btn btn-outline-info py-3 text-start">
                            <i class="fas fa-chart-bar me-3"></i> Estatístika Kompletu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    transition: all 0.3s ease;
}
.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220,53,69,0.3);
}
.btn-outline-primary, .btn-outline-success, .btn-outline-info {
    transition: all 0.3s ease;
}
.btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-info:hover {
    transform: translateX(5px);
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>