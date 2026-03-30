<?php
include_once("bd.php");

if (!isset($_GET['id'])) {
    die("Demande introuvable");
}

$id = (int) $_GET['id'];

// Récupérer les infos
$stmt = $pdo->prepare("
    SELECT d.*, u.nom, u.prenom, u.email
    FROM demande d
    JOIN employe e ON d.id_employe = e.id_employe
    JOIN utilisateur u ON e.id_user = u.id_user
    WHERE d.id_demande = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Aucune donnée trouvée");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Impression demande</title>
    <style>
        body {
            font-family: Arial;
            padding: 30px;
        }
        .container {
            border: 1px solid #000;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        .info {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🧾 Détails de la demande</h2>

    <p class="info"><span class="label">Employé :</span> <?= $data['prenom'] . ' ' . $data['nom'] ?></p>
    <p class="info"><span class="label">Email :</span> <?= $data['email'] ?></p>
    <p class="info"><span class="label">Type :</span> <?= $data['type_demande'] ?></p>
    <p class="info"><span class="label">Date début :</span> <?= $data['date_debut'] ?></p>
    <p class="info"><span class="label">Date fin :</span> <?= $data['date_fin'] ?></p>
    <p class="info"><span class="label">Motif :</span> <?= $data['motif'] ?></p>
    <p class="info"><span class="label">Statut :</span> <?= $data['statut'] ?></p>

    <br>

    <button onclick="window.print()">🖨️ Imprimer</button>
</div>

<script>
window.onload = function() {
    window.print();
}
</script>

</body>
</html>