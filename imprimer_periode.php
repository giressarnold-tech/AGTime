<?php
include_once("bd.php");

$type = $_GET['type'] ?? '';
$date = $_GET['date'] ?? '';

if (!$type || !$date) {
    die("Paramètres invalides");
}

// Construire la condition SQL
switch ($type) {
    case 'jour':
        $condition = "DATE(d.date_demande) = ?";
        $param = [$date];
        break;

    case 'mois':
        $condition = "MONTH(d.date_demande) = MONTH(?) AND YEAR(d.date_demande) = YEAR(?)";
        $param = [$date, $date];
        break;

    case 'annee':
        $condition = "YEAR(d.date_demande) = YEAR(?)";
        $param = [$date];
        break;

    default:
        die("Type invalide");
}

// Requête
$stmt = $pdo->prepare("
    SELECT d.*, u.nom, u.prenom
    FROM demande d
    JOIN employe e ON d.id_employe = e.id_employe
    JOIN utilisateur u ON e.id_user = u.id_user
    WHERE $condition
    ORDER BY d.date_demande DESC
");

$stmt->execute($param);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Export des demandes</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        h2 { text-align: center; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 13px;
        }

        th {
            background: #f2f2f2;
        }

        @media print {
            button { display: none; }
        }
    </style>
</head>
<body>

<h2>📊 Liste des demandes (<?= htmlspecialchars($type) ?>)</h2>
<p>Date sélectionnée : <?= htmlspecialchars($date) ?></p>

<table>
    <thead>
        <tr>
            <th>Employé</th>
            <th>Type</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="5" align="center">Aucune donnée</td></tr>
        <?php else: ?>
            <?php foreach ($data as $d): ?>
                <tr>
                    <td><?= $d['prenom'] . ' ' . $d['nom'] ?></td>
                    <td><?= $d['type_demande'] ?></td>
                    <td><?= $d['date_debut'] ?></td>
                    <td><?= $d['date_fin'] ?></td>
                    <td><?= $d['statut'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<br>
<button onclick="window.print()">🖨️ Imprimer / Télécharger PDF</button>

<script>
window.onload = function() {
    window.download();
}
</script>

</body>
</html>