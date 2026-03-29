<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

// Prosesu hamoos jornál
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $stmt = $pdo->prepare("SELECT file_path FROM journals WHERE id = ?");
    $stmt->execute([$id]);
    $journal = $stmt->fetch();
    
    // Hamoos arkivu husi server
    if ($journal && file_exists('../' . $journal['file_path'])) {
        unlink('../' . $journal['file_path']);
    }
    
    // Hamoos dadus husi baze de dadus
    $stmt = $pdo->prepare("DELETE FROM journals WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Jornál hamoos ona ho susesu';
    redirect('admin/manage_journals.php');
}

// Hola jornál hotu
$stmt = $pdo->query("
    SELECT j.*, u.naran_kompletu as author_name,
           (SELECT COUNT(*) FROM journal_stats WHERE journal_id = j.id AND type = 'view') as views,
           (SELECT COUNT(*) FROM journal_stats WHERE journal_id = j.id AND type = 'download') as downloads
    FROM journals j
    JOIN users u ON j.uploaded_by = u.id
    ORDER BY j.created_at DESC
");
$journals = $stmt->fetchAll();

require_once '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold"><i class="fas fa-book-open me-2 text-primary"></i>Jere Jornál Sira</h2>
            <p class="text-muted">Halo monitorizasaun no karga ka hamoos jornál iha sistema laran.</p>
             <a href="dashboard.php" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Fila ba Dashboard
                </a>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Títulu Jornál</th>
                            <th>Autór</th>
                            <th>Data Upload</th>
                            <th class="text-center">Haree</th>
                            <th class="text-center">Download</th>
                            <th class="text-center">Aksaun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($journals)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Laiha jornál ne'ebé rejista.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($journals as $journal): ?>
                            <tr>
                                <td class="ps-3"><span class="badge bg-secondary"><?= $journal['id'] ?></span></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($journal['topiku']) ?></td>
                                <td><i class="fas fa-user-edit me-1 text-muted"></i> <?= htmlspecialchars($journal['author_name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($journal['created_at'])) ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-info text-dark">
                                        <i class="fas fa-eye me-1"></i> <?= $journal['views'] ?? 0 ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-success">
                                        <i class="fas fa-download me-1"></i> <?= $journal['downloads'] ?? 0 ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="../view_journal.php?id=<?= $journal['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Haree Detallu" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="?delete=<?= $journal['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" title="Hamoos"
                                           onclick="return confirm('Ita hakarak hamoos duni jornál ne\'e?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
