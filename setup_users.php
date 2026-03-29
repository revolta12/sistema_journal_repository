<?php
require_once 'config/database.php';

echo "<h1>Konfigurasaun Uzuáriu ba Repozitóriu Jornál</h1>";
echo "<hr>";

// Hash ba parola sira
$adminHash = password_hash('admin123', PASSWORD_DEFAULT);
$authorHash = password_hash('author123', PASSWORD_DEFAULT);
$readerHash = password_hash('reader123', PASSWORD_DEFAULT);

echo "<h3>Hash Parola nian:</h3>";
echo "admin123 -> $adminHash<br>";
echo "author123 -> $authorHash<br>";
echo "reader123 -> $readerHash<br>";
echo "<hr>";

// Hatama/Atualiza Admin
$stmt = $pdo->prepare("SELECT id FROM admin WHERE username = 'admin'");
$stmt->execute();
if ($stmt->rowCount() > 0) {
    $update = $pdo->prepare("UPDATE admin SET password = ?, naran_kompletu = ? WHERE username = 'admin'");
    $update->execute([$adminHash, 'Administradór']);
    echo "✅ Admin atualiza ona!<br>";
} else {
    $insert = $pdo->prepare("INSERT INTO admin (username, password, naran_kompletu) VALUES (?, ?, ?)");
    $insert->execute(['admin', $adminHash, 'Administradór']);
    echo "✅ Admin kria ona!<br>";
}

// Hatama/Atualiza Uzuáriu sira
$users = [
    ['author', $authorHash, 'Uzuáriu Autór', 'author'],
    ['reader', $readerHash, 'Uzuáriu Lee-na\'in', 'reader']
];

foreach ($users as $u) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$u[0]]);
    
    if ($stmt->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE users SET password = ?, naran_kompletu = ?, role = ? WHERE username = ?");
        $update->execute([$u[1], $u[2], $u[3], $u[0]]);
        echo "✅ Uzuáriu {$u[0]} atualiza ona!<br>";
    } else {
        $insert = $pdo->prepare("INSERT INTO users (username, password, naran_kompletu, role) VALUES (?, ?, ?, ?)");
        $insert->execute([$u[0], $u[1], $u[2], $u[3]]);
        echo "✅ Uzuáriu {$u[0]} kria ona!<br>";
    }
}

echo "<hr>";
echo "<h2 style='color: green;'>✅ Konfigurasaun Remata!</h2>";
echo "<p>Agora bele tama (login) ho:</p>";
echo "<ul>";
echo "<li><strong>Admin:</strong> admin / admin123</li>";
echo "<li><strong>Autór:</strong> author / author123</li>";
echo "<li><strong>Lee-na'in:</strong> reader / reader123</li>";
echo "</ul>";
echo "<a href='login.php' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Bá Pájina Login</a>";
?>
