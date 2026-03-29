<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

echo "<h1>Konfigurasaun Baze de Dadus - Sistema Repozitóriu Jornál</h1>";
echo "<hr>";

// 1. Verifika tabela admin
echo "<h3>1. Verifika Tabela Admin</h3>";
$stmt = $pdo->query("SELECT COUNT(*) FROM admin");
$adminCount = $stmt->fetchColumn();

if ($adminCount == 0) {
    echo "Laiha admin, kria hela admin foun...<br>";
    
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $naran_kompletu = 'Administradór';
    
    $stmt = $pdo->prepare("INSERT INTO admin (username, password, naran_kompletu) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $naran_kompletu])) {
        echo "✅ Konta Admin kria ho susesu!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "❌ Falla uainhira kria admin<br>";
    }
} else {
    echo "✅ Admin eziste ona (rejistu " . $adminCount . ")<br>";
}

// 2. Verifika tabela users (uzuáriu)
echo "<h3>2. Verifika Tabela Uzuáriu (Users)</h3>";
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$userCount = $stmt->fetchColumn();

if ($userCount == 0) {
    echo "Laiha uzuáriu, kria hela uzuáriu demo...<br>";
    
    // Kria reader (lee-na'in)
    $readerPass = password_hash('reader123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, naran_kompletu, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['reader', $readerPass, 'Uzuáriu Lee-na\'in', 'reader']);
    echo "✅ Konta Reader kria ho susesu!<br>";
    
    // Kria author (autór)
    $authorPass = password_hash('author123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, naran_kompletu, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['author', $authorPass, 'Uzuáriu Autór', 'author']);
    echo "✅ Konta Author kria ho susesu!<br>";
    
} else {
    echo "✅ Uzuáriu eziste ona (rejistu " . $userCount . ")<br>";
}

// 3. Hatudu dadus hotu
echo "<h3>3. Dadus Admin</h3>";
$stmt = $pdo->query("SELECT * FROM admin");
$admins = $stmt->fetchAll();
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background-color: #f2f2f2;'><th>ID</th><th>Username</th><th>Naran Kompletu</th><th>Kria iha</th></tr>";
foreach ($admins as $admin) {
    echo "<tr>";
    echo "<td>{$admin['id']}</td>";
    echo "<td>{$admin['username']}</td>";
    echo "<td>{$admin['naran_kompletu']}</td>";
    echo "<td>{$admin['created_at']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>4. Dadus Uzuáriu (Users)</h3>";
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background-color: #f2f2f2;'><th>ID</th><th>Username</th><th>Naran Kompletu</th><th>Papél (Role)</th><th>Kria iha</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>{$user['id']}</td>";
    echo "<td>{$user['username']}</td>";
    echo "<td>{$user['naran_kompletu']}</td>";
    echo "<td>{$user['role']}</td>";
    echo "<td>{$user['created_at']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<hr>";
echo "<h2>✅ Konfigurasaun Remata!</h2>";
echo "<p>Agora Ita-boot bele tama (login) ho konta sira tuir mai ne'e:</p>";
echo "<ul>";
echo "<li><strong>Admin:</strong> username: admin, password: admin123</li>";
echo "<li><strong>Author:</strong> username: author, password: author123</li>";
echo "<li><strong>Reader:</strong> username: reader, password: reader123</li>";
echo "</ul>";
echo "<a href='login.php' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Bá Pájina Login</a>";
?>
