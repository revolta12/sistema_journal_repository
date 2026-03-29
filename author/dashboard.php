<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Verifika fali funsaun báziku sira
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() { return isset($_SESSION['user_id']); }
}
if (!function_exists('isAuthor')) {
    function isAuthor() { 
        return isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'author' || $_SESSION['user_role'] === 'admin'); 
    }
}

// Tenke login nu'udar Autór
if (!isLoggedIn() || !isAuthor()) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Hola jornal sira ne'ebé autór ne'e upload
$stmt = $pdo->prepare("
    SELECT j.*, 
           (SELECT COUNT(*) FROM journal_stats WHERE journal_id = j.id AND type = 'view') as views,
           (SELECT COUNT(*) FROM journal_stats WHERE journal_id = j.id AND type = 'download') as downloads
    FROM journals j 
    WHERE j.uploaded_by = ? 
    ORDER BY j.created_at DESC
");
$stmt->execute([$user_id]);
$journals = $stmt->fetchAll();

// Hola estatístika ba card sira
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_journals,
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_uploads
    FROM journals 
    WHERE uploaded_by = ?
");
$stmt->execute([$user_id]);
$stats = $stmt->fetch();

require_once __DIR__ . '/../includes/navbar.php';
?>

<!-- Hatama CSS espesiál ba Dashboard -->
<link rel="stylesheet" href="../assets/css/dashboard.css">

<main class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h2 class="fw-bold mb-1">
                <i class="fas fa-chalkboard-user text-danger me-2"></i>
                Painel Autór
            </h2>
            <p class="text-muted">Benvindu fali, <strong><?= htmlspecialchars($_SESSION['user_naran']) ?></strong>. Kontrola Ita-nia jornál iha ne'e.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="upload_journal.php" class="btn btn-danger btn-lg shadow-sm rounded-pill">
                <i class="fas fa-plus-circle me-2"></i>Upload Jornal Foun
            </a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-5 g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm stat-card-custom bg-white p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-light-primary text-primary me-3">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['total_journals'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Total Jornal</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm stat-card-custom bg-white p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-light-success text-success me-3">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $stats['today_uploads'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Upload Ohin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Table Section -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="fw-bold mb-0"><i class="fas fa-list-ul text-danger me-2"></i>Lista Jornal Ha'u Nian</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($journals)): ?>
                <div class="text-center py-5">
                    <img src="../assets/img/empty.svg" alt="Empty" style="width: 150px; opacity: 0.5;">
                    <h5 class="mt-3 text-muted">Seidauk iha jornal</h5>
                    <p class="text-muted small">Komeza upload Ita-nia kbiit sientífiku ohin kedas!</p>
                    <a href="upload_journal.php" class="btn btn-outline-danger btn-sm">Upload Agora</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Tópiku & Deskripsaun</th>
                                <th>Data Upload</th>
                                <th>Estatístika</th>
                                <th class="text-center">Aksaun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($journals as $journal): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($journal['topiku']) ?></div>
                                    <div class="small text-muted text-truncate" style="max-width: 250px;">
                                        <?= htmlspecialchars($journal['deskripsaun'] ?? '') ?>
                                    </div>
                                </td>
                                <td class="small"><?= date('d M Y', strtotime($journal['created_at'])) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-soft-info text-info border-0">
                                            <i class="fas fa-eye me-1"></i> <?= $journal['views'] ?>
                                        </span>
                                        <span class="badge bg-soft-success text-success border-0">
                                            <i class="fas fa-download me-1"></i> <?= $journal['downloads'] ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                        <a href="../view_journal.php?id=<?= $journal['id'] ?>" class="btn btn-white btn-sm" title="Haree">
                                            <i class="fas fa-external-link-alt text-primary"></i>
                                        </a>
                                        <a href="edit_journal.php?id=<?= $journal['id'] ?>" class="btn btn-white btn-sm" title="Edit">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        <a href="delete_journal.php?id=<?= $journal['id'] ?>" 
                                           class="btn btn-white btn-sm" 
                                           onclick="return confirm('Ita boot hakarak hamoos jornal ne\'e?')" title="Hamoos">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
