<?php
session_start();
include_once("bd.php");

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: connexion.php");
    exit();
}

//verifier l'id passe en GET
if (!isset($_GET['id_user']) || empty($_GET['id_user'])) {
    header("Location: listeRHIndex.php");
    exit();
}

$id     = (int) $_GET['id_user'];
$erreur = "";
$succes = "";

//── Récupérer les données actuelles ───────────────────────────────────────────
$st = $pdo->prepare("
    SELECT u.id_user, u.matricule, u.nom, u.prenom, u.tel, u.email, u.mot_de_passe, u.actif
    FROM utilisateur u
    JOIN rh r ON u.id_user = r.id_user
    WHERE u.id_user = ?
");
$st->execute([$id]);
$ep = $st->fetch(PDO::FETCH_ASSOC);

if (!$ep) {
    header("Location: listeRHIndex.php");
    exit();
}

// ── Traitement du formulaire de modification ───────────────────────────────────
if (isset($_POST['modif_rh'])) {
    $nom    = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $tel    = htmlspecialchars(trim($_POST['tel']));
    $email  = htmlspecialchars(trim($_POST['email']));
    $mdp    = htmlspecialchars(trim($_POST['mdp']));
    $actif  = isset($_POST['actif']) ? 1 : 0;

    if (empty($nom) || empty($prenom) || empty($tel) || empty($email) || empty($mdp)) {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse email n'est pas valide.";
    } else {
        $chk = $pdo->prepare("SELECT id_user FROM utilisateur WHERE email = ? AND id_user != ?");
        $chk->execute([$email, $id]);
        if ($chk->rowCount() > 0) {
            $erreur = "Cet email est déjà utilisé par un autre compte.";
        } else {
            $pdo->prepare("
                UPDATE utilisateur
                SET nom=?, prenom=?, tel=?, email=?, mot_de_passe=?, actif=?
                WHERE id_user=?
            ")->execute([$nom, $prenom, $tel, $email, $mdp, $actif, $id]);
            $succes = "Compte RH modifié avec succès.";
            $st->execute([$id]);
            $ep = $st->fetch(PDO::FETCH_ASSOC);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Modifier RH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    <style>
        * { font-family: 'Roboto', sans-serif; }
        body { background: #f0f2f5; display: flex; min-height: 100vh; }
        .sidebar { width: 230px; min-height: 100vh; background: #0f3460; padding: 24px 16px; position: fixed; top: 0; left: 0; }
        .sidebar h4 { color: #a8c7fa; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; }
        .sidebar a { display: flex; align-items: center; gap: 10px; color: #b2bec3; text-decoration: none; padding: 10px 12px; border-radius: 8px; font-size: 14px; margin-bottom: 4px; transition: background 0.2s, color 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: #fff; }
        .main-content { margin-left: 230px; padding: 32px; flex: 1; }
        .form-card { background: #fff; border-radius: 14px; padding: 32px; max-width: 650px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); }
        .form-card h4 { font-weight: 700; color: #0f3460; margin-bottom: 4px; }
        .form-label { font-size: 13px; font-weight: 500; color: #444; }
        .form-control { border-radius: 8px; border: 1.5px solid #dee2e6; padding: 11px 14px; font-size: 14px; transition: border-color 0.2s; }
        .form-control:focus { border-color: #0f3460; box-shadow: 0 0 0 3px rgba(15,52,96,0.1); }
        .btn-modifier { background: #e94560; color: white; border: none; border-radius: 8px; padding: 11px 28px; font-size: 14px; font-weight: 600; transition: background 0.2s; }
        .btn-modifier:hover { background: #d63031; color: white; }
        .divider { border-top: 1px solid #eee; margin: 20px 0; }
        .alerte-succes { background: #f0fff4; border: 1.5px solid #b2dfdb; border-radius: 8px; color: #1a7a4a; padding: 12px 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; animation: fadeIn 0.3s ease; }
        .alerte-erreur { background: #fff5f5; border: 1.5px solid #f5c6cb; border-radius: 8px; color: #c0392b; padding: 12px 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
        .matricule-badge { background: #fce4ec; color: #880e4f; border-radius: 8px; padding: 8px 14px; font-size: 13px; font-weight: 600; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="sidebar">
    <h4>Administration</h4>
    <a href="dashboardadmin2.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="listeEmployeIndex.php"><i class="bi bi-people"></i> Employés</a>
    <a href="listeRHIndex.php" class="active"><i class="bi bi-person-badge"></i> RH</a>
    <a href="listedesdemandes.php"><i class="bi bi-calendar2-check"></i> Demandes</a>
    <a href="ajouter_compte.php"><i class="bi bi-person-plus"></i> Ajouter compte</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
</div>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 style="font-weight:700; color:#0f3460; margin:0;">Modifier un RH</h4>
            <p style="font-size:13px; color:#888; margin:0;">👤 <?= htmlspecialchars($_SESSION['prenom'] . " " . $_SESSION['nom']) ?></p>
        </div>
        <a href="listeRHIndex.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    <div class="form-card">
        <div class="matricule-badge"><i class="bi bi-person-badge me-2"></i><?= htmlspecialchars($ep['matricule']) ?></div>
        <h4>Modifier les informations</h4>
        <p style="font-size:13px; color:#888; margin-bottom:20px;">Modifiez les informations du responsable RH.</p>
        <div class="divider"></div>

        <?php if (!empty($succes)): ?>
            <div class="alerte-succes"><i class="bi bi-check-circle-fill"></i> <?= $succes ?></div>
        <?php endif; ?>
        <?php if (!empty($erreur)): ?>
            <div class="alerte-erreur"><i class="bi bi-exclamation-circle-fill"></i> <?= $erreur ?></div>
        <?php endif; ?>

        <form method="POST" action="modifier_rh.php?id_user=<?= $id ?>">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($ep['nom']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($ep['prenom']) ?>" required>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                    <input type="tel" name="tel" class="form-control" value="<?= htmlspecialchars($ep['tel']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($ep['email']) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                <input type="password" name="mdp" class="form-control" value="<?= htmlspecialchars($ep['mot_de_passe']) ?>" required>
            </div>
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="actif" id="actif" <?= $ep['actif'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="actif">Compte actif</label>
                </div>
            </div>
            <div class="divider"></div>
            <div class="d-flex gap-3 justify-content-end">
                <a href="listeRHIndex.php" class="btn btn-outline-secondary"><i class="bi bi-x me-1"></i>Annuler</a>
                <button type="submit" name="modif_rh" class="btn-modifier">
                    <i class="bi bi-check2 me-1"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>