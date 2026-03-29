<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

// Prosesu hamoos uzuáriu
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Labele hamoos admin husi ne'e
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Uzuáriu hamoos ona ho susesu';
    redirect('admin/manage_users.php');
}

// Prosesu atualiza papél (role)
if (isset($_POST['update_role'])) {
    $user_id = (int)$_POST['user_id'];
    $role = sanitize($_POST['role']);
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$role, $user_id]);
    $_SESSION['success'] = 'Papél uzuáriu nian atualiza ona';
    redirect('admin/manage_users.php');
}

// Hola uzuáriu hotu
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

require_once '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold"><i class="fas fa-users me-2 text-primary"></i>Jere Uzuáriu Sira</h2>
            <p class="text-muted">Halo monitorizasaun ba membru hotu no troka sira-nia kategoria asesu.</p>
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
                            <th>Username</th>
                            <th>Naran Kompletu</th>
                            <th>Papél (Role)</th>
                            <th>Data Rejistu</th>
                            <th class="text-center">Aksaun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="ps-3"><span class="badge bg-secondary"><?= $user['id'] ?></span></td>
                            <td class="fw-bold"><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['naran_kompletu']) ?></td>
                            <td>
                                <?php if ($user['role'] == 'admin'): ?>
                                    <span class="badge bg-dark text-uppercase">Administradór</span>
                                <?php else: ?>
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <select name="role" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="reader" <?= $user['role'] == 'reader' ? 'selected' : '' ?>>Lee-na'in (Reader)</option>
                                            <option value="author" <?= $user['role'] == 'author' ? 'selected' : '' ?>>Autór (Author)</option>
                                        </select>
                                        <input type="hidden" name="update_role" value="1">
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            <td class="text-center">
                                <?php if ($user['role'] != 'admin'): ?>
                                    <a href="?delete=<?= $user['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Ita hakarak hamoos duni uzuáriu ne\'e?')">
                                        <i class="fas fa-user-times"></i> Hamoos
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Protejidu</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
