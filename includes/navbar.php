<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Funsaun sira
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() { return isset($_SESSION['user_id']); }
}
if (!function_exists('isAdmin')) {
    function isAdmin() { return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'; }
}
if (!function_exists('isAuthor')) {
    function isAuthor() { 
        return isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'author' || $_SESSION['user_role'] === 'admin'); 
    }
}

$base_url = '/journal-repository/';
?>
<!DOCTYPE html>
<html lang="tp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repozitóriu Jornal Timor-Leste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .nav-link i { margin-right: 5px; }
        .btn-logout-nav {
            background-color: #dc3545;
            color: white !important;
            border-radius: 20px;
            padding: 5px 15px !important;
            margin-left: 10px;
            font-weight: 500;
        }
        .btn-logout-nav:hover { background-color: #bb2d3b; }
        .user-name-nav { color: #ffc107 !important; font-weight: bold; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= $base_url ?>">
            <i class="fas fa-book-reader me-2 text-warning"></i>
            <span class="fw-bold">REPOZITÓRIU JORNAL</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>"><i class="fas fa-home"></i> Pájina Inisiál</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>journals.php"><i class="fas fa-layer-group"></i> Arkivu Jornal</a>
                </li>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link text-info" href="<?= $base_url ?>admin/dashboard.php">
                                <i class="fas fa-user-shield"></i> Painel Administrasaun
                            </a>
                        </li>
                    <?php elseif (isAuthor()): ?>
                        <li class="nav-item">
                            <a class="nav-link text-info" href="<?= $base_url ?>author/dashboard.php">
                                <i class="fas fa-feather-alt"></i> Espasu Autór
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav align-items-center">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link user-name-nav" href="<?= $base_url ?>profile.php">
                            <i class="fas fa-user-circle"></i>
                            <?= htmlspecialchars($_SESSION['user_naran'] ?? 'Uzuáriu') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-logout-nav" href="<?= $base_url ?>logout.php" 
                           onclick="return confirm('Ita-boot hakarak duni atu termina sesaun ne\'e?')">
                            <i class="fas fa-sign-out-alt"></i> Termina Sesaun
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="<?= $base_url ?>login.php">
                            <i class="fas fa-sign-in-alt"></i> Asesu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-warning btn-sm text-white px-3" href="<?= $base_url ?>register.php">
                            <i class="fas fa-user-plus"></i> Rejista
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
