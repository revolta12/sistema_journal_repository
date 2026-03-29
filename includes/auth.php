<?php
require_once 'config/database.php';

/**
 * Funsaun ba Rejista Uzuáriu Foun
 */
function registerUser($username, $password, $naranKompletu, $role = 'reader') {
    global $pdo;
    
    // Haree se username ne'e uza ona ka lae
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        return ['error' => 'Username ne\'e uza ona. Favór hili naran seluk.'];
    }
    
    // Halo kripitografia ba password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, naran_kompletu, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $hashedPassword, $naranKompletu, $role])) {
        return ['success' => 'Rejistu susesu! Favór tama (login) agora.'];
    }
    
    return ['error' => 'Rejistu la susesu. Favór koko fali.]'];
}

/**
 * Funsaun ba Login Uzuáriu no Admin
 */
function loginUser($username, $password) {
    global $pdo;
    
    // 1. Haree uluk iha tabela 'users'
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_naran'] = $user['naran_kompletu'];
        $_SESSION['user_role'] = $user['role'];
        return ['success' => 'Login susesu!', 'role' => $user['role']];
    }
    
    // 2. Se la iha, haree fali iha tabela 'admin'
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['user_naran'] = $admin['naran_kompletu'];
        $_SESSION['user_role'] = 'admin';
        return ['success' => 'Login susesu!', 'role' => 'admin'];
    }
    
    return ['error' => 'Username ka password sala.'];
}
?>
