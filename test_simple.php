<?php
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_naran'] = 'Admin Test';
$_SESSION['user_role'] = 'admin';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Dropdown</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Test Navbar</a>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> Admin Test <span class="badge bg-info">admin</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Test Dropdown</h2>
        <p>Klik pada <strong>"Admin Test"</strong> untuk melihat dropdown</p>
        <p>Jika dropdown muncul, maka navbar Anda harusnya berfungsi.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>