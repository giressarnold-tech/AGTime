<?php
session_start();
include_once("bd.php");

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'EMPLOYE') {
    header("Location: connexion.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// ── Récupérer l'id_employe ────────────────────────────────────────────────────
$stmtEmp = $pdo->prepare("SELECT id_employe FROM employe WHERE id_user = ?");
$stmtEmp->execute([$id_user]);
$empRow     = $stmtEmp->fetch(PDO::FETCH_ASSOC);
$id_employe = $empRow ? $empRow['id_employe'] : null;

// ── Stats réelles depuis la BDD ───────────────────────────────────────────────
$stmtAttente = $pdo->prepare("SELECT COUNT(*) FROM demande WHERE id_employe = ? AND statut = 'EN_ATTENTE'");
$stmtAttente->execute([$id_employe]);
$nb_attente = $stmtAttente->fetchColumn();

$stmtValides = $pdo->prepare("SELECT COUNT(*) FROM demande WHERE id_employe = ? AND statut = 'ACCEPTEE'");
$stmtValides->execute([$id_employe]);
$nb_valides = $stmtValides->fetchColumn();

$stmtRefuses = $pdo->prepare("SELECT COUNT(*) FROM demande WHERE id_employe = ? AND statut = 'REFUSEE'");
$stmtRefuses->execute([$id_employe]);
$nb_refuses = $stmtRefuses->fetchColumn();

$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM demande WHERE id_employe = ?");
$stmtTotal->execute([$id_employe]);
$nb_total = $stmtTotal->fetchColumn();

// ── Historique des 5 dernières demandes ───────────────────────────────────────
$stmtHisto = $pdo->prepare("
    SELECT type_demande, date_debut, date_fin, statut, motif
    FROM demande
    WHERE id_employe = ?
    ORDER BY date_demande DESC
    LIMIT 5
");
$stmtHisto->execute([$id_employe]);
$historique = $stmtHisto->fetchAll(PDO::FETCH_ASSOC);

// ── Notifications non lues ────────────────────────────────────────────────────
$stmtNotifs = $pdo->prepare("
    SELECT id_notif, message, date_creation
    FROM notification
    WHERE id_user = ? AND statut = 'non_lu'
    ORDER BY date_creation DESC
");
$stmtNotifs->execute([$id_user]);
$notifications = $stmtNotifs->fetchAll(PDO::FETCH_ASSOC);
$nb_notifs = count($notifications);

// ── Marquer les notifs comme lues si on clique sur la cloche ──────────────────
if (isset($_GET['voir_notifs'])) {
    $pdo->prepare("UPDATE notification SET statut = 'lu' WHERE id_user = ?")
        ->execute([$id_user]);
    header("Location: pageemployé.php");
    exit();
}

// ── Message de succès après soumission de demande ─────────────────────────────
$succes = isset($_GET['succes']) ? "Votre demande a été soumise avec succès. Le RH a été notifié." : "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Tableau de bord Employé</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    <style>
        * { font-family: 'Roboto', sans-serif; }
        body { background: #f0f2f5; }

        /* NAVBAR */
        .navbar { background: #0f3460; }
        .navbar-brand, .nav-link { color: #fff !important; }
        .nav-link:hover { color: #a8c7fa !important; }
        .navbar-text { color: #a8c7fa !important; font-size: 14px; }

        /* CLOCHE NOTIFICATIONS */
        .notif-wrapper {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .notif-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background: #e94560;
            color: white;
            border-radius: 50%;
            padding: 1px 5px;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
        }
        .notif-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 32px;
            width: 320px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            z-index: 9999;
            overflow: hidden;
        }
        .notif-dropdown.show { display: block; }
        .notif-header {
            background: #0f3460;
            color: white;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
        }
        .notif-item {
            padding: 10px 16px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            color: #333;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item small { color: #999; display: block; margin-top: 3px; }
        .notif-vide { padding: 16px; text-align: center; color: #aaa; font-size: 13px; }

        /* CARTES STATS */
        .stat-card {
            border-radius: 12px;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-card h6 { font-size: 13px; margin: 0 0 6px; opacity: 0.9; }
        .stat-card h2 { font-size: 32px; font-weight: 700; margin: 0; }
        .stat-card i  { font-size: 36px; opacity: 0.3; }
        .bg-green   { background: linear-gradient(135deg, #00b894, #00cec9); }
        .bg-orange  { background: linear-gradient(135deg, #fdcb6e, #e17055); }
        .bg-blue    { background: linear-gradient(135deg, #0f3460, #0984e3); }
        .bg-red     { background: linear-gradient(135deg, #e94560, #d63031); }

        /* CARTES CONTENU */
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header {
            background: #fff;
            border-bottom: 2px solid #f0f2f5;
            font-weight: 600;
            color: #0f3460;
            border-radius: 12px 12px 0 0 !important;
            padding: 14px 20px;
        }

        /* BADGES STATUT */
        .badge-attente  { background: #fff3cd; color: #856404; border-radius: 20px; padding: 4px 12px; font-size: 12px; }
        .badge-acceptee { background: #d1e7dd; color: #0f5132; border-radius: 20px; padding: 4px 12px; font-size: 12px; }
        .badge-refusee  { background: #f8d7da; color: #842029; border-radius: 20px; padding: 4px 12px; font-size: 12px; }

        /* ALERTES */
        .alerte-succes {
            background: #f0fff4;
            border: 1.5px solid #b2dfdb;
            border-radius: 8px;
            color: #1a7a4a;
            padding: 12px 16px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* BOUTON NOUVELLE DEMANDE */
        .btn-nouvelle {
            background: #0f3460;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-nouvelle:hover { background: #16213e; color: white; }

        .page-title { font-weight: 700; color: #0f3460; margin-bottom: 4px; }
        .page-subtitle { font-size: 13px; color: #888; margin-bottom: 0; }
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
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="pageemployé.php">
                        <i class="bi bi-house me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="demande.php">
                        <i class="bi bi-plus-circle me-1"></i>Nouvelle demande
                    </a>
                </li>

                <!-- CLOCHE NOTIFICATIONS -->
                <li class="nav-item">
                    <div class="notif-wrapper" onclick="toggleNotifs()">
                        <i class="bi bi-bell-fill text-white" style="font-size:20px;"></i>
                        <?php if ($nb_notifs > 0): ?>
                            <span class="notif-badge"><?= $nb_notifs ?></span>
                        <?php endif; ?>

                        <div class="notif-dropdown" id="notifDropdown">
                            <div class="notif-header">
                                🔔 Notifications
                                <?php if ($nb_notifs > 0): ?>
                                    — <a href="pageemployé.php?voir_notifs=1"
                                         style="color:#a8c7fa;font-size:11px;">
                                        Tout marquer comme lu
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php if (empty($notifications)): ?>
                                <div class="notif-vide">Aucune nouvelle notification</div>
                            <?php else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <div class="notif-item">
                                        <?= htmlspecialchars($notif['message']) ?>
                                        <small><?= date('d/m/Y à H:i', strtotime($notif['date_creation'])) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
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

<!-- CONTENU -->
<div class="container mt-4">

    <!-- Message de succès -->
    <?php if (!empty($succes)): ?>
        <div class="alerte-succes mb-4">
            <i class="bi bi-check-circle-fill"></i>
            <?= htmlspecialchars($succes) ?>
        </div>
    <?php endif; ?>

    <!-- Titre page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="page-title">Tableau de bord</h4>
            <p class="page-subtitle">Bienvenue, <?= htmlspecialchars($_SESSION['prenom']) ?> 👋</p>
        </div>
        <a href="demande.php" class="btn-nouvelle">
            <i class="bi bi-plus-circle me-1"></i> Nouvelle demande
        </a>
    </div>

    <!-- CARTES STATS -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-orange shadow">
                <div>
                    <h6>Demandes en attente</h6>
                    <h2><?= $nb_attente ?></h2>
                </div>
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-blue shadow">
                <div>
                    <h6>Demandes validées</h6>
                    <h2><?= $nb_valides ?></h2>
                </div>
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-red shadow">
                <div>
                    <h6>Demandes refusées</h6>
                    <h2><?= $nb_refuses ?></h2>
                </div>
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-green shadow">
                <div>
                    <h6>Total demandes</h6>
                    <h2><?= $nb_total ?></h2>
                </div>
                <i class="bi bi-list-check"></i>
            </div>
        </div>
    </div>

    <!-- HISTORIQUE -->
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history me-2"></i>Mes dernières demandes</span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($historique)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:40px;"></i>
                    <p class="mt-2">Aucune demande pour le moment.</p>
                    <a href="demande.php" class="btn-nouvelle" style="font-size:13px;">
                        Faire ma première demande
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-4">Type</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Motif</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historique as $d): ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-500">
                                            <?= $d['type_demande'] === 'CONGE' ? '🏖️ Congé' : '🕐 Permission' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($d['date_debut'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($d['date_fin'])) ?></td>
                                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        <?= htmlspecialchars($d['motif']) ?>
                                    </td>
                                    <td>
                                        <?php if ($d['statut'] === 'EN_ATTENTE'): ?>
                                            <span class="badge-attente">⏳ En attente</span>
                                        <?php elseif ($d['statut'] === 'ACCEPTEE'): ?>
                                            <span class="badge-acceptee">✅ Acceptée</span>
                                        <?php else: ?>
                                            <span class="badge-refusee">❌ Refusée</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle dropdown notifications
function toggleNotifs() {
    const dd = document.getElementById('notifDropdown');
    dd.classList.toggle('show');
}

// Fermer si on clique ailleurs
document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notifDropdown').classList.remove('show');
    }
});

// Polling toutes les 30 secondes pour mettre à jour le badge
setInterval(function() {
    fetch('get_notifs.php')
        .then(r => r.json())
        .then(data => {
            const badge = document.querySelector('.notif-badge');
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count;
                } else {
                    // Créer le badge s'il n'existait pas
                    const b = document.createElement('span');
                    b.className = 'notif-badge';
                    b.textContent = data.count;
                    document.querySelector('.notif-wrapper').appendChild(b);
                }
            } else if (badge) {
                badge.remove();
            }
        }).catch(() => {});
}, 30000);
</script>
</body>
</html>