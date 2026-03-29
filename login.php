<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Redireta se tama ona
if (isLoggedIn()) {
    if (isAdmin())       redirect('admin/dashboard.php');
    elseif (isAuthor())  redirect('author/dashboard.php');
    else                 redirect('index.php');
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $result = loginUser($username, $password);

    if (isset($result['success'])) {
        $_SESSION['login_success'] = $result['success'];
        if ($result['role'] == 'admin')        redirect('admin/dashboard.php');
        elseif ($result['role'] == 'author')   redirect('author/dashboard.php');
        else                                   redirect('index.php');
    } else {
        $error = $result['error'];
    }
}

include 'includes/navbar.php';
?>

<div class="login-wrapper">
    <div class="login-card fade-in">

        <!-- ── Header ── -->
        <div class="login-card-header">
            <div class="login-header-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h4>Tama ba Sistema</h4>
            <p>Repositóriu Jornál Timor-Leste</p>
        </div>

        <!-- ── Body ── -->
        <div class="login-card-body">

            <!-- Alert sala -->
            <?php if ($error): ?>
                <div class="login-alert error" id="alertError">
                    <i class="fas fa-circle-exclamation"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                    <button class="btn-close-alert" onclick="this.closest('.login-alert').remove()">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Alert suksesu rejistu -->
            <?php if (isset($_SESSION['register_success'])): ?>
                <div class="login-alert success" id="alertSuccess">
                    <i class="fas fa-circle-check"></i>
                    <span><?= $_SESSION['register_success'] ?></span>
                    <button class="btn-close-alert" onclick="this.closest('.login-alert').remove()">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
                <?php unset($_SESSION['register_success']); ?>
            <?php endif; ?>

            <!-- Formu -->
            <form method="POST" action="">

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="login-label">
                        <i class="fas fa-user"></i> Naran Utilizadór
                    </label>
                    <input type="text"
                           class="login-input"
                           id="username"
                           name="username"
                           placeholder="Hakerek naran utilizadór..."
                           required autofocus
                           autocomplete="username">
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <label for="password" class="login-label">
                        <i class="fas fa-lock"></i> Liafuan Segredu
                    </label>
                    <div style="position:relative;">
                        <input type="password"
                               class="login-input"
                               id="password"
                               name="password"
                               placeholder="Hakerek liafuan segredu..."
                               required
                               autocomplete="current-password"
                               style="padding-right: 46px;">
                        <button type="button"
                                onclick="togglePassword()"
                                style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                                       background:none;border:none;cursor:pointer;color:var(--tl-muted);
                                       font-size:14px;padding:0;" id="toggleBtn">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login-submit">
                    <i class="fas fa-arrow-right-to-bracket"></i> Tama
                </button>
            </form>

            <!-- Divider -->
            <div class="login-divider">ka</div>

            <!-- Link rejistu -->
            <div class="login-register-link">
                Seidauk iha konta?
                <a href="register.php">Rejistu Agora</a>
            </div>

        </div><!-- /.login-card-body -->
    </div><!-- /.login-card -->

    <!-- Demo konta -->

</div><!-- /.login-wrapper -->

<script>
function togglePassword() {
    const input   = document.getElementById('password');
    const icon    = document.getElementById('eyeIcon');
    const visible = input.type === 'text';
    input.type    = visible ? 'password' : 'text';
    icon.className = visible ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>

<?php include 'includes/footer.php'; ?>