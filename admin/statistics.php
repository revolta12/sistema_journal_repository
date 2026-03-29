<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

// Foti estatístika ne'ebé kompletu
$stmt = $pdo->query("
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as total
    FROM journal_stats
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date DESC
");
$dailyStats = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT 
        MONTHNAME(created_at) as month,
        COUNT(*) as total
    FROM journals
    WHERE YEAR(created_at) = YEAR(CURDATE())
    GROUP BY MONTH(created_at)
");
$monthlyUploads = $stmt->fetchAll();

require_once '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i>Estatístika Sistema</h2>
            <p class="text-muted">Monitoriza atividade uzuáriu no karga jornál sira nian.</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Gráfiku Atividade Loron-loron -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Atividade Loron 30 Ikus nian</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyStatsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfiku Upload Fulan-fulan -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Karga Jornál per Fulan (Tinan <?= date('Y') ?>)</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyUploadsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfiku Estatístika Loron-loron (Daily Stats)
const dailyCtx = document.getElementById('dailyStatsChart').getContext('2d');
new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: [<?php foreach (array_reverse($dailyStats) as $stat) echo "'" . $stat['date'] . "', "; ?>],
        datasets: [{
            label: 'Atividade (View/Download)',
            data: [<?php foreach (array_reverse($dailyStats) as $stat) echo $stat['total'] . ", "; ?>],
            borderColor: 'rgb(52, 152, 219)',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Gráfiku Upload Fulan-fulan (Monthly Uploads)
const monthlyCtx = document.getElementById('monthlyUploadsChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: [<?php foreach ($monthlyUploads as $stat) echo "'" . $stat['month'] . "', "; ?>],
        datasets: [{
            label: 'Totál Jornál',
            data: [<?php foreach ($monthlyUploads as $stat) echo $stat['total'] . ", "; ?>],
            backgroundColor: 'rgba(46, 204, 113, 0.5)',
            borderColor: 'rgb(46, 204, 113)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
