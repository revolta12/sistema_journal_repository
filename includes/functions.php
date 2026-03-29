<?php
// Hahú sesaun (session) se seidauk hahú
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// KETA TAU ECHO KA OUTPUT IHA NE'E
// Labele iha echo, print, ka output naran de'it molok funsaun sira-ne'e

// Tama ba baze de dadus (database)
require_once __DIR__ . '/../config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function isAuthor() {
    return isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'author' || $_SESSION['user_role'] === 'admin');
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function uploadFile($file, $targetDir) {
    $targetDir = rtrim($targetDir, '/') . '/';
    
    // Kria pasta (folder) se seidauk iha
    if (!file_exists($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            return ['error' => 'Gagal kria pasta upload nian'];
        }
    }
    
    // Haree se pasta ne'e bele hakerek (writable) ka lae
    if (!is_writable($targetDir)) {
        return ['error' => 'Pasta upload la bele hakerek ba laran'];
    }
    
    // Haree se arkivu (file) iha duni ka lae
    if (!isset($file) || $file['error'] != UPLOAD_ERR_OK) {
        $errorMsg = 'Laiha arkivu neebe foti (upload)';
        if (isset($file['error'])) {
            switch ($file['error']) {
                case UPLOAD_ERR_NO_FILE:
                    $errorMsg = 'Favór hili arkivu ida uluk';
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    $errorMsg = 'Arkivu nee boot liu (máksimu ' . ini_get('upload_max_filesize') . ')';
                    break;
                default:
                    $errorMsg = 'Error upload: ' . $file['error'];
            }
        }
        return ['error' => $errorMsg];
    }
    
    // Jenera naran arkivu ne'ebé úniku
    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
    $targetFile = $targetDir . $fileName;
    
    // Haree tipu arkivu (extensaun)
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['pdf', 'doc', 'docx'];
    
    if (!in_array($fileType, $allowedTypes)) {
        return ['error' => 'So arkivu PDF, DOC, ho DOCX deit mak bele'];
    }
    
    // Haree medida arkivu (10MB)
    if ($file['size'] > 10 * 1024 * 1024) {
        $userSize = round($file['size'] / 1024 / 1024, 2);
        return ['error' => "Arkivu máksimu 10MB. Ita nian: $userSize MB"];
    }
    
    // Hala'o upload
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => $targetFile];
    }
    
    return ['error' => 'Gagal foti (upload) arkivu'];
}

function logStats($journalId, $userId, $type, $ip) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO journal_stats (journal_id, user_id, type, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$journalId, $userId, $type, $ip]);
    } catch(Exception $e) {
        // Nonok de'it se fail
    }
}

function getJournalStats($journalId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN type = 'view' THEN 1 END) as views,
            COUNT(CASE WHEN type = 'download' THEN 1 END) as downloads
        FROM journal_stats 
        WHERE journal_id = ?
    ");
    $stmt->execute([$journalId]);
    return $stmt->fetch();
}

function getTotalJournals() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM journals");
    return $stmt->fetchColumn();
}

function getTotalUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    return $stmt->fetchColumn();
}

function getRecentJournals($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT j.*, u.naran_kompletu as author_name 
        FROM journals j 
        JOIN users u ON j.uploaded_by = u.id 
        ORDER BY j.created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}
?>
