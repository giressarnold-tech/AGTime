<?php
session_start();
include_once("bd.php");

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['id_user'])) {
    header("Location: listeEmployeIndex.php");
    exit();
}

$id = (int) $_GET['id_user'];

// Récupérer les infos pour afficher le nom dans la confirmation
$rq = $pdo->prepare("SELECT nom, prenom FROM utilisateur WHERE id_user = ?");
$rq->execute([$id]);
$lui = $rq->fetch(PDO::FETCH_ASSOC);

if (!$lui) {
    header("Location: listeEmployeIndex.php");
    exit();
}

// Traitement suppression
if (isset($_POST['sup_e'])) {
    try {
         // 1. Supprimer les notifications liées
         $stmt1 = $pdo->prepare("DELETE FROM notification WHERE id_user = ?")->execute([$id]);
        // 2. Supprimer l'utilisateur
        $stmt2 = $pdo->prepare("DELETE FROM utilisateur WHERE id_user = ?")->execute([$id]);
        header("Location: listeEmployeIndex.php?succes=supprime");
        exit();
    } catch (Exception $e) {
        $erreur = "Impossible de supprimer : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Supprimer employé</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    <style>
        * { font-family: 'Roboto', sans-serif; }
        body { background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .confirm-card { background: white; border-radius: 16px; padding: 40px; max-width: 460px; width: 100%; box-shadow: 0 8px 32px rgba(0,0,0,0.1); text-align: center; }
        .icon-danger { width: 72px; height: 72px; background: #fff5f5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .icon-danger i { font-size: 32px; color: #e94560; }
        .confirm-card h5 { font-weight: 700; color: #2d3436; margin-bottom: 10px; }
        .confirm-card p { color: #636e72; font-size: 14px; }
        .nom-employe { color: #0f3460; font-weight: 700; }
        .avertissement { background: #fff5f5; border: 1.5px solid #f5c6cb; border-radius: 8px; padding: 10px 16px; color: #c0392b; font-size: 13px; margin: 16px 0; }
        .btn-confirmer { background: #e94560; color: white; border: none; border-radius: 8px; padding: 12px 28px; font-size: 14px; font-weight: 600; transition: background 0.2s; }
        .btn-confirmer:hover { background: #d63031; color: white; }
        .btn-annuler { border-radius: 8px; padding: 12px 28px; font-size: 14px; }
    </style>
</head>
<body>
<div class="confirm-card">
    <div class="icon-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
    <h5>Confirmer la suppression</h5>
    <p>
        Voulez-vous vraiment supprimer l'employé :
        <span class="nom-employe"><?= htmlspecialchars($lui['prenom'] . ' ' . $lui['nom']) ?></span> ?
    </p>
    <div class="avertissement">
        <i class="bi bi-exclamation-circle me-1"></i>
        Cette action est <strong>définitive</strong>. Toutes les demandes liées seront également supprimées.
    </div>

    <?php if (isset($erreur)): ?>
        <div class="alert alert-danger"><?= $erreur ?></div>
    <?php endif; ?>

    <form method="POST" action="supprimer_employe.php?id_user=<?= $id ?>">
        <div class="d-flex gap-3 justify-content-center mt-3">
            <a href="listeEmployeIndex.php" class="btn btn-outline-secondary btn-annuler">
                <i class="bi bi-x me-1"></i>Annuler
            </a>
            <button type="submit" name="sup_e" class="btn-confirmer">
                <i class="bi bi-trash me-1"></i>Supprimer définitivement
            </button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>