<?php
require_once 'includes/functions.php';

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Hola jurnal hotu ho estatístika
$query = "
    SELECT j.*, u.naran_kompletu as author_name,
           (SELECT COUNT(*) FROM journal_stats WHERE journal_id = j.id AND type = 'view') as views
    FROM journals j
    JOIN users u ON j.uploaded_by = u.id
    WHERE j.topiku LIKE :search
    ORDER BY j.created_at DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($query);
$searchTerm = "%$search%";
$stmt->bindParam(':search', $searchTerm);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$journals = $stmt->fetchAll();

// Konta totál ba pajinasaun
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM journals WHERE topiku LIKE :search");
$countStmt->bindParam(':search', $searchTerm);
$countStmt->execute();
$totalJournals = $countStmt->fetchColumn();
$totalPages = ceil($totalJournals / $limit);

require_once 'includes/navbar.php';
?>

<main class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-journal-whills me-2"></i>Jurnal Hotu</h2>
            <p>Hetan jurnal sientífiku foun husi área oioin</p>
        </div>
    </div>
    
    <!-- Formuláriu Buka (Search) -->
    <div class="search-section mb-4">
        <form action="journals.php" method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" 
                       placeholder="Buka títulu jurnal..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Buka
                </button>
            </div>
        </form>
    </div>
    
    <!-- Jurnal Grid -->
    <div class="row">
        <?php if (empty($journals)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                    <h5>Seidauk iha jurnal</h5>
                    <p>Favór fali mai check fali iha tempu seluk ka uza de'it liafuan xave seluk</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($journals as $journal): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 journal-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-primary">Jurnal</span>
                                <small class="text-muted">
                                    <i class="fas fa-eye"></i> <?= $journal['views'] ?? 0 ?> vizualizasaun
                                </small>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($journal['topiku']) ?></h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($journal['author_name']) ?><br>
                                <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($journal['created_at'])) ?>
                            </p>
                            <p class="card-text">
                                <?= htmlspecialchars(substr($journal['deskripsaun'], 0, 100)) ?>...
                            </p>
                            <div class="d-grid gap-2">
                                <a href="view_journal.php?id=<?= $journal['id'] ?>" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Haree Detallu
                                </a>
                                <?php if (isLoggedIn()): ?>
                                    <a href="download.php?id=<?= $journal['id'] ?>" class="btn btn-outline-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Pajinasaun (Pagination) -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>
