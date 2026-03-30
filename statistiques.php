<?php
session_start();
include_once("bd.php");

// Sécurité
if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['RH', 'ADMIN'])) {
    header("Location: connexion.php");
    exit();
}

// Requête stats
$stmt = $pdo->query("
    SELECT MONTH(date_demande) as mois,
           COUNT(*) as total,
           SUM(CASE WHEN statut = 'ACCEPTEE' THEN 1 ELSE 0 END) as acceptees,
           SUM(CASE WHEN statut = 'REFUSEE' THEN 1 ELSE 0 END) as refusees
    FROM demande
    GROUP BY MONTH(date_demande)
    ORDER BY mois
");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer données
$mois = [];
$total = [];
$acceptees = [];
$refusees = [];

$moisLabels = ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin",
               "Juil", "Août", "Sep", "Oct", "Nov", "Déc"];

foreach ($data as $d) {
    $mois[] = $moisLabels[$d['mois'] - 1];
    $total[] = $d['total'];
    $acceptees[] = $d['acceptees'];
    $refusees[] = $d['refusees'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Statistiques RH</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body { background: #f4f6f9; }
.card { border-radius: 12px; }
</style>
</head>

<body>

<div class="container mt-4">

    <h3 class="mb-4">📊 Statistiques des demandes</h3>

    <!-- Graphique bar -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <!-- Graphique camembert -->
    <div class="card shadow">
        <div class="card-body">
            <canvas id="pieChart"></canvas>
        </div>
    </div>

</div>

<script>
const mois = <?= json_encode($mois) ?>;
const total = <?= json_encode($total) ?>;
const acceptees = <?= json_encode($acceptees) ?>;
const refusees = <?= json_encode($refusees) ?>;

// Graphique bar
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: mois,
        datasets: [
            {
                label: 'Total',
                data: total
            },
            {
                label: 'Acceptées',
                data: acceptees
            },
            {
                label: 'Refusées',
                data: refusees
            }
        ]
    }
});

// Graphique camembert
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Acceptées', 'Refusées'],
        datasets: [{
            data: [
                acceptees.reduce((a,b)=>a+b,0),
                refusees.reduce((a,b)=>a+b,0)
            ]
        }]
    }
});
</script>

</body>
</html>