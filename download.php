<?php
// Atu hatudu de'it erro bainhira iha dezenvolvimentu
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Ligasaun ba baze de dadus (database)
$host = 'localhost';
$dbname = 'sistem_repositori_jurnal';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ligasaun ba baze de dadus la susesu: " . $e->getMessage());
}

// Haree se uzuáriu tama ona (login) ka lae
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_required'] = 'Ita-boot tenke tama (login) uluk mak foin bele download jornal.';
    header('Location: login.php');
    exit();
}

// Foti ID jornal husi URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['error'] = 'ID jornal nian la válidu.';
    header('Location: index.php');
    exit();
}

// Buka dalan arkivu (file path) no títulu jornal nian
$stmt = $pdo->prepare("SELECT file_path, topiku FROM journals WHERE id = ?");
$stmt->execute([$id]);
$journal = $stmt->fetch();

if (!$journal) {
    $_SESSION['error'] = 'Jornal ne\'e la hetan iha sistema.';
    header('Location: index.php');
    exit();
}

// HADIA: Dalan (path) arkivu ne'ebé loos
$file_path = __DIR__ . '/' . $journal['file_path'];

// Verifika se arkivu ne'e iha duni iha server
if (!file_exists($file_path)) {
    // Koko dalan seluk (alternative path)
    $alt_path = dirname(__DIR__) . '/' . $journal['file_path'];
    if (file_exists($alt_path)) {
        $file_path = $alt_path;
    } else {
        $_SESSION['error'] = 'Arkivu jornal la hetan iha server. Favór kontaktu administradór.';
        header('Location: index.php');
        exit();
    }
}

// Rejista estatístika download (log download)
try {
    $stmt = $pdo->prepare("INSERT INTO journal_stats (journal_id, user_id, type, ip_address) VALUES (?, ?, 'download', ?)");
    $stmt->execute([$id, $_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
} catch(Exception $e) {
    // Nonok de'it se falla, atu la hanetik prosesu download
}

// Prosesu Download Arkivu
if (file_exists($file_path)) {
    // Hamoos buffer atu labele iha kódigu HTML ne'ebé kahur iha laran
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Header ba download
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($journal['topiku']) . '.pdf"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    
    // Lee no entrega arkivu
    readfile($file_path);
    exit();
} else {
    $_SESSION['error'] = 'Erro: Arkivu la bele download.';
    header('Location: index.php');
    exit();
}
?>
