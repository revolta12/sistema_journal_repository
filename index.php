<?php
require_once 'includes/functions.php';
require_once 'includes/navbar.php';

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page   = isset($_GET['page'])   ? (int)$_GET['page'] : 1;
$limit  = 9;
$offset = ($page - 1) * $limit;

// Hetan jornál hotu
$query = "SELECT j.*, u.naran_kompletu AS author_name,
          (SELECT COUNT(*) FROM journal_stats WHERE journal_id = j.id AND type = 'view') AS views
          FROM journals j
          JOIN users u ON j.uploaded_by = u.id
          WHERE j.topiku LIKE :search
          ORDER BY j.created_at DESC
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
$searchTerm = "%$search%";
$stmt->bindParam(':search', $searchTerm);
$stmt->bindParam(':limit',  $limit,  PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$journals = $stmt->fetchAll();

// Konta totál
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM journals WHERE topiku LIKE :search");
$countStmt->bindParam(':search', $searchTerm);
$countStmt->execute();
$totalJournals = $countStmt->fetchColumn();
$totalPages    = ceil($totalJournals / $limit);
?>

<main>

    <!-- ── HERO ── -->
    <section class="hero-section">
        <div class="container text-center">

            <h1 class="fade-in">
                Repositóriu Jornál<br>
                <span class="accent">Dijitál</span>
            </h1>
            <p class="lead fade-in">
                Asesu jornál sientífiku ho fasil, lais, no livre iha Timor-Leste
            </p>

            <!-- Search -->
            <div class="search-box fade-in">
                <form action="index.php" method="GET">
                    <div class="search-input-group">
                        <input type="text" name="search" class="form-control"
                               placeholder="Buka jornál tuir títulu..."
                               value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-magnifying-glass"></i> Buka
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <!-- ── JORNÁL LIST ── -->
    <div class="container py-5">

        <!-- Section header -->
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-scroll"></i>Jornál Foun
                </h2>
                <p class="section-count">
                    Totál <span><?= number_format($totalJournals) ?></span> jornál iha sistema
                </p>
            </div>
            <?php if ($search): ?>
                <a href="index.php" class="btn-download" style="width:auto; padding:8px 16px;">
                    <i class="fas fa-xmark"></i> Hamos buka
                </a>
            <?php endif; ?>
        </div>

        <!-- Cards -->
        <div class="row g-4">
            <?php if (empty($journals)): ?>
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h5>Jornál seidauk iha</h5>
                        <p>Favor hetan fila fali ka uza liafuan seluk ba buka.</p>
                    </div>
                </div>

            <?php else: ?>
                <?php foreach ($journals as $journal): ?>
                    <div class="col-md-6 col-lg-4 fade-in">
                        <div class="journal-card h-100">
                            <div class="card-body d-flex flex-column">

                                <!-- Badge + views -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="journal-badge">
                                        <i class="fas fa-file-lines"></i> Jornál
                                    </span>
                                    <span class="journal-views">
                                        <i class="fas fa-eye"></i>
                                        <?= number_format($journal['views'] ?? 0) ?>
                                    </span>
                                </div>

                                <!-- Títulu -->
                                <h5 class="journal-title">
                                    <?= htmlspecialchars($journal['topiku']) ?>
                                </h5>

                                <!-- Meta -->
                                <div class="journal-meta">
                                    <span>
                                        <i class="fas fa-user-pen"></i>
                                        <?= htmlspecialchars($journal['author_name']) ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-calendar-days"></i>
                                        <?= date('d M Y', strtotime($journal['created_at'])) ?>
                                    </span>
                                </div>

                                <!-- Deskripsaun -->
                                <p class="journal-desc">
                                    <?= htmlspecialchars(substr($journal['deskripsaun'], 0, 120)) ?>...
                                </p>

                                <!-- Botaun -->
                                <div class="mt-auto d-grid gap-2">
                                    <a href="view_journal.php?id=<?= $journal['id'] ?>" class="btn-read">
                                        <i class="fas fa-book-open"></i> Lee Kompletu
                                    </a>
                                    <?php if (isLoggedIn()): ?>
                                        <a href="download.php?id=<?= $journal['id'] ?>" class="btn-download">
                                            <i class="fas fa-download"></i> Download Jornál
                                        </a>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Navigasaun pájina" class="mt-5">
                <ul class="pagination justify-content-center">

                    <!-- Anterior -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Tuir mai -->
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>

                </ul>
            </nav>
        <?php endif; ?>

    </div><!-- /.container -->

</main>

<?php require_once 'includes/footer.php'; ?>