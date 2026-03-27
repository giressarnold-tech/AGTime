<?php
session_start();
include_once("bd.php");

// Vérifier que l'utilisateur est connecté et est un EMPLOYE
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'EMPLOYE') {
    header("Location: connexion.php");
    exit();
}

$erreur  = "";
$succes  = "";

if (isset($_POST['envoyer'])) {
    $type_demande = htmlspecialchars($_POST['type_demande']);
    $date_debut   = htmlspecialchars($_POST['date_debut']);
    $date_fin     = htmlspecialchars($_POST['date_fin']);
    $raison       = htmlspecialchars($_POST['raison']);

    // Validation des champs
    if (empty($type_demande) || empty($date_debut) || empty($date_fin) || empty($raison)) {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif ($date_fin < $date_debut) {
        $erreur = "La date de fin ne peut pas être avant la date de début.";
    } else {
        // Récupérer l'id_employe depuis la session
        $stmt = $pdo->prepare("SELECT id_employe FROM employe WHERE id_user = ?");
        $stmt->execute([$_SESSION['id_user']]);
        $employe = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employe) {
            $erreur = "Employé introuvable. Contactez l'administrateur.";
        } else {
            $id_employe = $employe['id_employe'];

            // ── Insérer la demande ────────────────────────────────────────
            $sql     = "INSERT INTO demande (id_employe, type_demande, date_debut, date_fin, motif)
                        VALUES (?, ?, ?, ?, ?)";
            $prepare = $pdo->prepare($sql);
            $ok      = $prepare->execute([$id_employe, $type_demande, $date_debut, $date_fin, $raison]);

            if ($ok) {
                // ── Notifier tous les RH et ADMIN ─────────────────────────
                $sqlRH  = "SELECT id_user FROM utilisateur WHERE role = 'RH' OR role = 'ADMIN'";
                $result = $pdo->query($sqlRH);
                $rhs    = $result->fetchAll(PDO::FETCH_ASSOC);

                $message = "Nouvelle demande de " . strtolower($type_demande) .
                           " soumise par " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . ".";

                $insertNotif = $pdo->prepare(
                    "INSERT INTO notification (id_user, message) VALUES (?, ?)"
                );
                foreach ($rhs as $rh) {
                    $insertNotif->execute([$rh['id_user'], $message]);
                }

                // Redirection avec message de succès via paramètre URL
                header("Location: pageemployé.php?succes=1");
                exit();
            } else {
                $erreur = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Nouvelle demande</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">

    <style>
        * { font-family: 'Roboto', sans-serif; }

        body {
            background: #f0f2f5;
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: #0f3460;
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
        }
        .nav-link:hover {
            color: #a8c7fa !important;
        }
        .navbar-text {
            color: #a8c7fa !important;
            font-size: 14px;
        }

        /* ── CARTE FORMULAIRE ── */
        .form-card {
            background: #fff;
            border-radius: 14px;
            padding: 36px 32px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            max-width: 620px;
            margin: 40px auto;
        }

        .form-card h4 {
            font-weight: 700;
            color: #0f3460;
            margin-bottom: 6px;
        }

        .form-card p.sous-titre {
            font-size: 13px;
            color: #888;
            margin-bottom: 24px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #444;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            padding: 11px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0f3460;
            box-shadow: 0 0 0 3px rgba(15,52,96,0.1);
        }

        .btn-soumettre {
            background: #0f3460;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-soumettre:hover {
            background: #16213e;
            color: white;
            transform: translateY(-1px);
        }

        .btn-annuler {
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 15px;
        }

        /* ── ALERTES ── */
        .alerte-erreur {
            background: #fff5f5;
            border: 1.5px solid #f5c6cb;
            border-radius: 8px;
            color: #c0392b;
            padding: 10px 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            animation: fadeIn 0.3s ease;
        }

        .alerte-succes {
            background: #f0fff4;
            border: 1.5px solid #b2dfdb;
            border-radius: 8px;
            color: #1a7a4a;
            padding: 10px 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .divider {
            border-top: 1px solid #eee;
            margin: 20px 0;
        }

        /* ── BADGE TYPE ── */
        .type-badge {
            display: inline-block;
            background: #e8f0fe;
            color: #0f3460;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="pageemployé.php">
            <i class="bi bi-people-fill me-1"></i> AG-TIME
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="pageemployé.php">
                        <i class="bi bi-house me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
                    </a>
                </li>
            </ul>
            <span class="navbar-text ms-3">
                👤 <?= htmlspecialchars($_SESSION['prenom'] . " " . $_SESSION['nom']) ?>
            </span>
        </div>
    </div>
</nav>

<!-- FORMULAIRE -->
<div class="form-card">

    <span class="type-badge"><i class="bi bi-plus-circle me-1"></i>Nouvelle demande</span>
    <h4>Soumettre une demande</h4>
    <p class="sous-titre">Remplissez le formulaire ci-dessous. Le RH sera notifié automatiquement.</p>

    <div class="divider"></div>

    <!-- Message d'erreur -->
    <?php if (!empty($erreur)): ?>
        <div class="alerte-erreur">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="demande.php">

        <!-- Type de demande -->
        <div class="mb-3">
            <label class="form-label">Type de demande <span class="text-danger">*</span></label>
            <select name="type_demande" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <option value="CONGE"      <?= (isset($_POST['type_demande']) && $_POST['type_demande'] === 'CONGE')      ? 'selected' : '' ?>>
                    Congé annuel
                </option>
                <option value="PERMISSION" <?= (isset($_POST['type_demande']) && $_POST['type_demande'] === 'PERMISSION') ? 'selected' : '' ?>>
                    Permission d'absence
                </option>
            </select>
        </div>

        <!-- Dates -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Date de début <span class="text-danger">*</span></label>
                <input type="date" name="date_debut" class="form-control"
                       value="<?= isset($_POST['date_debut']) ? htmlspecialchars($_POST['date_debut']) : '' ?>"
                       min="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Date de fin <span class="text-danger">*</span></label>
                <input type="date" name="date_fin" class="form-control"
                       value="<?= isset($_POST['date_fin']) ? htmlspecialchars($_POST['date_fin']) : '' ?>"
                       min="<?= date('Y-m-d') ?>" required>
            </div>
        </div>

        <!-- Motif -->
        <div class="mb-4">
            <label class="form-label">Motif / Raison <span class="text-danger">*</span></label>
            <textarea name="raison" class="form-control" rows="4"
                      placeholder="Décrivez brièvement la raison de votre demande..."
                      required><?= isset($_POST['raison']) ? htmlspecialchars($_POST['raison']) : '' ?></textarea>
        </div>

        <div class="divider"></div>

        <!-- Boutons -->
        <div class="d-flex gap-3 justify-content-end">
            <a href="pageemployé.php" class="btn btn-outline-secondary btn-annuler">
                <i class="bi bi-x me-1"></i>Annuler
            </a>
            <button type="submit" name="envoyer" class="btn-soumettre">
                <i class="bi bi-send me-1"></i>Soumettre la demande
            </button>
        </div>

    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Validation côté client : date de fin >= date de début
document.querySelector('form').addEventListener('submit', function(e) {
    const debut = document.querySelector('[name="date_debut"]').value;
    const fin   = document.querySelector('[name="date_fin"]').value;
    if (fin && debut && fin < debut) {
        e.preventDefault();
        alert("La date de fin ne peut pas être avant la date de début.");
    }
});
</script>

</body>
</html>