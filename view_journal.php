<?php
require_once 'includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Hetan detallu jornál husi baze de dadus
$stmt = $pdo->prepare("
    SELECT j.*, u.naran_kompletu AS author_name, u.username
    FROM journals j
    JOIN users u ON j.uploaded_by = u.id
    WHERE j.id = ?
");
$stmt->execute([$id]);
$journal = $stmt->fetch();

if (!$journal) {
    $_SESSION['error'] = 'Jornál la hetan iha sistema';
    redirect('index.php');
}

// Rejista vizualizasaun (view)
$user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
$ip = $_SERVER['REMOTE_ADDR'];
logStats($id, $user_id, 'view', $ip);

// Hetan estatístika vizualizasaun no download
$stats = getJournalStats($id);

require_once 'includes/navbar.php';
?>

<main class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="journal-detail-card fade-in">

                <!-- ── Kabesallu (Header) ── -->
                <div class="journal-detail-header">
                    <div class="jdh-badge">
                        <i class="fas fa-file-lines"></i> Jornál Sientífiku
                    </div>
                    <h3><?= htmlspecialchars($journal['topiku']) ?></h3>
                </div>

                <!-- ── Informasaun Meta ── -->
                <div class="journal-meta-grid">
                    <div class="meta-item">
                        <div class="meta-icon red">
                            <i class="fas fa-user-pen"></i>
                        </div>
                        <div class="meta-content">
                            <span class="meta-label">Autór</span>
                            <span class="meta-value"><?= htmlspecialchars($journal['author_name']) ?></span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon gold">
                            <i class="fas fa-calendar-days"></i>
                        </div>
                        <div class="meta-content">
                            <span class="meta-label">Data Upload</span>
                            <span class="meta-value"><?= date('d F Y', strtotime($journal['created_at'])) ?></span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon dark">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="meta-content">
                            <span class="meta-label">Haree ona</span>
                            <span class="meta-value"><?= number_format($stats['views']) ?> vizualizasaun</span>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon green">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="meta-content">
                            <span class="meta-label">Download</span>
                            <span class="meta-value"><?= number_format($stats['downloads']) ?> kópia</span>
                        </div>
                    </div>
                </div>

                <!-- ── Deskripsaun ── -->
                <div class="journal-description-section">
                    <h5 class="desc-title">
                        <i class="fas fa-align-left"></i> Deskripsaun / Rezumu
                    </h5>
                    <p class="desc-text">
                        <?= nl2br(htmlspecialchars($journal['deskripsaun'])) ?>
                    </p>
                </div>

                <!-- ── Aksaun sira ── -->
                <div class="journal-action-section">
                    <div class="d-grid gap-3">
                        <?php if (isLoggedIn()): ?>
                            <a href="download.php?id=<?= $journal['id'] ?>" class="btn-download-lg">
                                <i class="fas fa-download"></i> Download Jornál (PDF)
                            </a>
                        <?php else: ?>
                            <div class="login-prompt">
                                <div class="login-prompt-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="login-prompt-text">
                                    Ita-boot tenke <a href="login.php" class="fw-bold">tama (login)</a> uluk mak foin bele download jornál ne'e.
                                </div>
                            </div>
                            <a href="login.php" class="btn-download-lg">
                                <i class="fas fa-sign-in-alt"></i> Tama atu Download
                            </a>
                        <?php endif; ?>

                        <a href="index.php" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Fila ba Pájina Inisiál
                        </a>
                    </div>
                </div>

            </div><!-- /.journal-detail-card -->
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
