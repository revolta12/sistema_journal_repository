<?php
require_once 'config/database.php';

// Verifika se admin eziste ona ka lae
$stmt = $pdo->query("SELECT COUNT(*) FROM admin");
$adminCount = $stmt->fetchColumn();

if ($adminCount == 0) {
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $naran_kompletu = 'Administradór';
    
    $stmt = $pdo->prepare("INSERT INTO admin (username, password, naran_kompletu) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $naran_kompletu])) {
        echo "Konta Admin kria ho susesu!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Falla wainhira kria konta admin";
    }
} else {
    echo "Konta admin eziste ona iha sistema";
}
?>

<a href="index.php">Fila ba Pájina Inisiál</a>
