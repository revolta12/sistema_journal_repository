<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">

            <!-- Koluna 1 — Brand -->
            <div class="col-md-4 col-lg-4">
                <div class="footer-brand">
                    <div class="footer-brand-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <div class="footer-brand-title">Repositóriu Jornál</div>
                        <div class="footer-brand-sub">Timor-Leste</div>
                    </div>
                </div>
                <p class="footer-desc">
                    Sistema repositóriu jornál dijitál ne'ebé fasilita asesu no jestaun jornál sientífiku iha Timor-Leste.
                </p>
            </div>

            <!-- Koluna 2 — Link Lais -->
            <div class="col-md-4 col-lg-4">
                <h6 class="footer-heading">
                    <i class="fas fa-compass"></i> Link Lais
                </h6>
                <ul class="footer-links">
                    <li>
                        <a href="<?= BASE_URL ?>">
                            Pájina Prinsipál
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>journals.php">
                            Jornál Hotu
                        </a>
                    </li>
                    <?php if (!isLoggedIn()): ?>
                        <li>
                            <a href="<?= BASE_URL ?>register.php">
                                Rejistu hanesan Autór
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Koluna 3 — Kontaktu -->
            <div class="col-md-4 col-lg-4">
                <h6 class="footer-heading">
                    <i class="fas fa-satellite-dish"></i> Informasaun
                </h6>

                <div class="footer-contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <span>support@journalrepo.com</span>
                </div>

                <div class="footer-contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <span>+670 123 4567</span>
                </div>

                <div class="footer-contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    <span>Dili, Timor-Leste</span>
                </div>
            </div>

        </div><!-- /.row -->

        <hr class="footer-divider">

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <p class="footer-copyright mb-0">
                &copy; <?= date('Y') ?> <span>Repositóriu Jornál</span> — Direitu hotu-hotu reservadu.
            </p>
            <div class="footer-flag">
                <span>Halo ho</span>
                <span style="color:var(--tl-red);">♥</span>
                <span>ba Timor-Leste</span>
                <span style="font-size:15px;">🇹🇱</span>
            </div>
        </div>

    </div><!-- /.container -->
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Custom JS -->
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>