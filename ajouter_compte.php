<?php
session_start();
include_once("bd.php");

if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['RH', 'ADMIN'])) {
    header("Location: connexion.php");
    exit();
}

function genererMat($id, $poste) {
    $an  = date("y");
    return $poste . "-" . $an . "-" . sprintf('%03d', $id);
}

$erreur = "";
$succes = "";

if (isset($_POST["ajt"])) {
    $nom    = htmlspecialchars(trim($_POST["nom"]));
    $prenom = htmlspecialchars(trim($_POST["prenom"]));
    $tel    = htmlspecialchars(trim($_POST["tel"]));
    $email  = htmlspecialchars(trim($_POST["email"]));
    $mdp    = htmlspecialchars(trim($_POST["mdp"]));
    $ft     = htmlspecialchars(trim($_POST["ft"]));

    if (empty($nom) || empty($prenom) || empty($tel) || empty($email) || empty($mdp) || empty($ft) || $ft === 'roles') {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse email n'est pas valide.";
    } else {
        // Vérifier si l'email existe déjà
        $chk = $pdo->prepare("SELECT id_user FROM utilisateur WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->rowCount() > 0) {
            $erreur = "Cet email est déjà utilisé par un autre compte.";
        } else {
            try {
                $pdo->beginTransaction();

                $rq      = "INSERT INTO utilisateur(nom, prenom, tel, email, mot_de_passe, role) VALUES (?,?,?,?,?,?)";
                $prepare = $pdo->prepare($rq);
                $prepare->execute([$nom, $prenom, $tel, $email, $mdp, $ft]);
                $u_id = $pdo->lastInsertId();

                switch ($ft) {
                    case 'ADMIN':
                        $pdo->prepare("INSERT INTO administrateur(id_user) VALUES (?)")->execute([$u_id]);
                        $mat = genererMat($u_id, "ADM");
                        break;
                    case 'EMPLOYE':
                        $pdo->prepare("INSERT INTO employe(id_user) VALUES (?)")->execute([$u_id]);
                        $mat = genererMat($u_id, "EMP");
                        break;
                    case 'RH':
                        $pdo->prepare("INSERT INTO rh(id_user) VALUES (?)")->execute([$u_id]);
                        $mat = genererMat($u_id, "RH");
                        break;
                    default:
                        throw new Exception("Rôle invalide.");
                }

                $pdo->prepare("UPDATE utilisateur SET matricule = ? WHERE id_user = ?")->execute([$mat, $u_id]);
                $pdo->commit();

                $succes = "Compte créé avec succès ! Matricule : <strong>" . $mat . "</strong>";

            } catch (Exception $e) {
                $pdo->rollback();
                $erreur = "Erreur : " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Ajouter un compte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    <style>
        * { font-family: 'Roboto', sans-serif; }
        body { background: #f0f2f5; display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 230px; min-height: 100vh;
            background: #0f3460; padding: 24px 16px;
            position: fixed; top: 0; left: 0;
        }
        .sidebar h4 { color: #a8c7fa; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; }
        .sidebar a {
            display: flex; align-items: center; gap: 10px;
            color: #b2bec3; text-decoration: none;
            padding: 10px 12px; border-radius: 8px;
            font-size: 14px; margin-bottom: 4px;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.1); color: #fff;
        }
        .sidebar a i { font-size: 16px; }

        .main-content { margin-left: 230px; padding: 32px; flex: 1; }

        /* CARTE FORMULAIRE */
        .form-card {
            background: #fff; border-radius: 14px;
            padding: 32px; max-width: 700px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        }
        .form-card h4 { font-weight: 700; color: #0f3460; margin-bottom: 4px; }
        .form-card p.sous-titre { font-size: 13px; color: #888; margin-bottom: 24px; }

        .form-label { font-size: 13px; font-weight: 500; color: #444; }
        .form-control, .form-select {
            border-radius: 8px; border: 1.5px solid #dee2e6;
            padding: 11px 14px; font-size: 14px;
            transition: border-color 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0f3460;
            box-shadow: 0 0 0 3px rgba(15,52,96,0.1);
        }

        .btn-ajouter {
            background: #0f3460; color: white; border: none;
            border-radius: 8px; padding: 11px 28px;
            font-size: 14px; font-weight: 600;
            transition: background 0.2s;
        }
        .btn-ajouter:hover { background: #16213e; color: white; }

        .divider { border-top: 1px solid #eee; margin: 20px 0; }

        .alerte-erreur {
            background: #fff5f5; border: 1.5px solid #f5c6cb;
            border-radius: 8px; color: #c0392b; padding: 12px 16px;
            font-size: 13px; display: flex; align-items: center; gap: 8px;
            margin-bottom: 20px; animation: fadeIn 0.3s ease;
        }
        .alerte-succes {
            background: #f0fff4; border: 1.5px solid #b2dfdb;
            border-radius: 8px; color: #1a7a4a; padding: 12px 16px;
            font-size: 13px; display: flex; align-items: center; gap: 8px;
            margin-bottom: 20px; animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* BADGE ROLE */
        .role-badge {
            display: inline-block; background: #e8f0fe; color: #0f3460;
            border-radius: 20px; padding: 3px 12px; font-size: 12px; font-weight: 600;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>Administration</h4>
    <a href="dashboardadmin2.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="listeEmployeIndex.php"><i class="bi bi-people"></i> Employés</a>
    <a href="listeRHIndex.php"><i class="bi bi-person-badge"></i> RH</a>
    <a href="listedesdemandes.php"><i class="bi bi-calendar2-check"></i> Demandes</a>
    <a href="ajouter_compte.php" class="active"><i class="bi bi-person-plus"></i> Ajouter compte</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
</div>

<!-- CONTENU -->
<div class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 style="font-weight:700; color:#0f3460; margin:0;">Ajouter un compte</h4>
            <p style="font-size:13px; color:#888; margin:0;">
                Connecté en tant que 👤 <?= htmlspecialchars($_SESSION['prenom'] . " " . $_SESSION['nom']) ?>
            </p>
        </div>
        <a href="dashboardadmin2.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    <div class="form-card">
        <span class="role-badge mb-3 d-inline-block"><i class="bi bi-person-plus me-1"></i>Nouveau compte</span>
        <h4>Informations du compte</h4>
        <p class="sous-titre">Remplissez tous les champs. Le matricule sera généré automatiquement.</p>

        <div class="divider"></div>

        <?php if (!empty($erreur)): ?>
            <div class="alerte-erreur">
                <i class="bi bi-exclamation-circle-fill"></i> <?= $erreur ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($succes)): ?>
            <div class="alerte-succes">
                <i class="bi bi-check-circle-fill"></i> <?= $succes ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="ajouter_compte.php">

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control" placeholder="Ex: Kamga"
                           value="<?= isset($_POST['nom']) && empty($succes) ? htmlspecialchars($_POST['nom']) : '' ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" name="prenom" class="form-control" placeholder="Ex: Jean"
                           value="<?= isset($_POST['prenom']) && empty($succes) ? htmlspecialchars($_POST['prenom']) : '' ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                    <input type="tel" name="tel" class="form-control" placeholder="Ex: 699000000"
                           value="<?= isset($_POST['tel']) && empty($succes) ? htmlspecialchars($_POST['tel']) : '' ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="exemple@email.com"
                           value="<?= isset($_POST['email']) && empty($succes) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="mdp" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rôle <span class="text-danger">*</span></label>
                    <select name="ft" class="form-select" required>
                        <option value="roles">-- Sélectionner un rôle --</option>
                        <option value="EMPLOYE" <?= (isset($_POST['ft']) && $_POST['ft'] === 'EMPLOYE') ? 'selected' : '' ?>>Employé</option>
                        <option value="RH"      <?= (isset($_POST['ft']) && $_POST['ft'] === 'RH')      ? 'selected' : '' ?>>RH</option>
                        <option value="ADMIN"   <?= (isset($_POST['ft']) && $_POST['ft'] === 'ADMIN')   ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
            </div>

            <div class="divider"></div>

            <div class="d-flex gap-3 justify-content-end">
                <button type="reset" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-1"></i>Réinitialiser
                </button>
                <button type="submit" name="ajt" class="btn-ajouter">
                    <i class="bi bi-person-plus me-1"></i>Créer le compte
                </button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>