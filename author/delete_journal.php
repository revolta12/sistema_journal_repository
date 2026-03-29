<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAuthor()) {
    redirect('login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verifika se jornál ne'e pertense duni ba autór ne'e
$stmt = $pdo->prepare("SELECT file_path FROM journals WHERE id = ? AND uploaded_by = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$journal = $stmt->fetch();

if ($journal) {
    // Hamoos arkivu (file) husi pasta server nian
    if (file_exists('../' . $journal['file_path'])) {
        unlink('../' . $journal['file_path']);
    }
    
    // Hamoos husi baze de dadus (estatístika sei lakon mós tanba cascade)
    $stmt = $pdo->prepare("DELETE FROM journals WHERE id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['delete_success'] = 'Jornál hamoos ona ho susesu';
} else {
    $_SESSION['delete_error'] = 'Jornál la hetan iha sistema';
}

redirect('author/dashboard.php');
?>
