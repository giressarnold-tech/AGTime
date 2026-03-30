<?php
session_start();
include_once("bd.php");

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: connexion.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// ── Stats réelles ─────────────────────────────────────────────────────────────
$nbUtilisateurs = $pdo->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
$nbAttente      = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut='EN_ATTENTE'")->fetchColumn();
$nbAccept       = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut='ACCEPTEE'")->fetchColumn();
$nbInactifs     = $pdo->query("SELECT COUNT(*) FROM utilisateur WHERE actif=0")->fetchColumn();
$nbEmployes     = $pdo->query("SELECT COUNT(*) FROM employe")->fetchColumn();
$nbRH           = $pdo->query("SELECT COUNT(*) FROM rh")->fetchColumn();

// ── Liste utilisateurs ────────────────────────────────────────────────────────
$utilisateurs = $pdo->query("
    SELECT u.id_user, u.matricule, u.nom, u.prenom, u.tel, u.email, u.actif,
           CASE
               WHEN e.id_user IS NOT NULL THEN 'Employé'
               WHEN r.id_user IS NOT NULL THEN 'RH'
               ELSE 'Admin'
           END AS role_label
    FROM utilisateur u
    LEFT JOIN employe e ON u.id_user = e.id_user
    LEFT JOIN rh r ON u.id_user = r.id_user
    ORDER BY u.id_user DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// ── Notifications ─────────────────────────────────────────────────────────────
$stmtNotifs = $pdo->prepare("
    SELECT id_notif, message, date_creation
    FROM notification WHERE id_user = ? AND statut = 'non_lu'
    ORDER BY date_creation DESC
");
$stmtNotifs->execute([$id_user]);
$notifications = $stmtNotifs->fetchAll(PDO::FETCH_ASSOC);
$nb_notifs     = count($notifications);

if (isset($_GET['voir_notifs'])) {
    $pdo->prepare("UPDATE notification SET statut='lu' WHERE id_user=?")->execute([$id_user]);
    header("Location: dashboardadmin2.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    <style>
        * { font-family: 'Roboto', sans-serif; }
        body { background: #f0f2f5; display: flex; min-height: 100vh; }

        .sidebar {
            width: 230px; min-height: 100vh; background: #0f3460;
            padding: 24px 16px; position: fixed; top: 0; left: 0;
        }
        .sidebar h4 { color: #a8c7fa; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; }
        .sidebar a {
            display: flex; align-items: center; gap: 10px; color: #b2bec3;
            text-decoration: none; padding: 10px 12px; border-radius: 8px;
            font-size: 14px; margin-bottom: 4px; transition: background 0.2s, color 0.2s;
        }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: #fff; }

        .main-content { margin-left: 230px; padding: 32px; flex: 1; }

        /* TOPBAR */
        .topbar {
            background: white; border-radius: 12px; padding: 14px 20px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 28px;
        }
        .topbar h5 { font-weight: 700; color: #0f3460; margin: 0; }
        .topbar small { color: #888; font-size: 13px; }

        /* CLOCHE */
        .notif-wrapper { position: relative; display: inline-block; cursor: pointer; }
        .notif-badge {
            position: absolute; top: -6px; right: -8px; background: #e94560;
            color: white; border-radius: 50%; padding: 1px 5px; font-size: 10px;
            font-weight: 700; min-width: 18px; text-align: center;
        }
        .notif-dropdown {
            display: none; position: absolute; right: 0; top: 32px; width: 300px;
            background: #fff; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            z-index: 9999; overflow: hidden;
        }
        .notif-dropdown.show { display: block; }
        .notif-header { background: #0f3460; color: white; padding: 10px 16px; font-size: 13px; font-weight: 600; }
        .notif-item { padding: 10px 16px; border-bottom: 1px solid #f0f0f0; font-size: 13px; }
        .notif-item small { color: #999; display: block; margin-top: 3px; }
        .notif-vide { padding: 16px; text-align: center; color: #aaa; font-size: 13px; }

        /* STAT CARDS */
        .stat-card {
            border-radius: 12px; padding: 20px; color: white;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        .stat-card h6 { font-size: 13px; margin: 0 0 4px; opacity: 0.9; }
        .stat-card h2 { font-size: 32px; font-weight: 700; margin: 0; }
        .stat-card i  { font-size: 38px; opacity: 0.25; }
        .bg-users   { background: linear-gradient(135deg, #0f3460, #0984e3); }
        .bg-pending { background: linear-gradient(135deg, #fdcb6e, #e17055); }
        .bg-approved{ background: linear-gradient(135deg, #00b894, #00cec9); }
        .bg-inactive{ background: linear-gradient(135deg, #636e72, #2d3436); }
        .bg-emp     { background: linear-gradient(135deg, #6c5ce7, #a29bfe); }
        .bg-rh      { background: linear-gradient(135deg, #e94560, #d63031); }

        /* TABLE */
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header {
            background: #fff; border-bottom: 2px solid #f0f2f5; font-weight: 600;
            color: #0f3460; border-radius: 12px 12px 0 0 !important; padding: 14px 20px;
        }

        .badge-actif   { background: #d1e7dd; color: #0f5132; border-radius: 20px; padding: 3px 10px; font-size: 11px; }
        .badge-inactif { background: #f8d7da; color: #842029; border-radius: 20px; padding: 3px 10px; font-size: 11px; }
        .badge-emp     { background: #e8f0fe; color: #0f3460; border-radius: 20px; padding: 3px 10px; font-size: 11px; }
        .badge-rh      { background: #fce4ec; color: #880e4f; border-radius: 20px; padding: 3px 10px; font-size: 11px; }
        .badge-admin   { background: #fff3e0; color: #e65100; border-radius: 20px; padding: 3px 10px; font-size: 11px; }

        .btn-ajouter {
            background: #0f3460; color: white; border: none; border-radius: 8px;
            padding: 8px 18px; font-size: 13px; font-weight: 600; text-decoration: none;
            transition: background 0.2s;
        }
        .btn-ajouter:hover { background: #16213e; color: white; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>Administration</h4>
    <a href="dashboardadmin2.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="listeEmployeIndex.php"><i class="bi bi-people"></i> Employés</a>
    <a href="listeRHIndex.php"><i class="bi bi-person-badge"></i> RH</a>
    <a href="listedesdemandes.php"><i class="bi bi-calendar2-check"></i> Demandes</a>
    <a href="ajouter_compte.php"><i class="bi bi-person-plus"></i> Ajouter compte</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
</div>

<!-- CONTENU -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">
        <div>
            <h5>Dashboard Administrateur</h5>
            <small>Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] . " " . $_SESSION['nom']) ?> 👋</small>
        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- CLOCHE -->
            <div class="notif-wrapper" onclick="toggleNotifs()">
                <i class="bi bi-bell-fill text-secondary" style="font-size:20px;"></i>
                <?php if ($nb_notifs > 0): ?>
                    <span class="notif-badge"><?= $nb_notifs ?></span>
                <?php endif; ?>
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        🔔 Notifications
                        <?php if ($nb_notifs > 0): ?>
                            — <a href="dashboardadmin2.php?voir_notifs=1" style="color:#a8c7fa;font-size:11px;">Tout lire</a>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($notifications)): ?>
                        <div class="notif-vide">Aucune nouvelle notification</div>
                    <?php else: ?>
                        <?php foreach ($notifications as $n): ?>
                            <div class="notif-item">
                                <?= htmlspecialchars($n['message']) ?>
                                <small><?= date('d/m/Y à H:i', strtotime($n['date_creation'])) ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <a href="ajouter_compte.php" class="btn-ajouter">
                <i class="bi bi-person-plus me-1"></i>Ajouter un compte
            </a>
        </div>
    </div>

    <!-- STATS -->
    <div class="row g-4 mb-4">
        <div class="col-md-2">
            <div class="stat-card bg-users">
                <div><h6>Utilisateurs</h6><h2><?= $nbUtilisateurs ?></h2></div>
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card bg-emp">
                <div><h6>Employés</h6><h2><?= $nbEmployes ?></h2></div>
                <i class="bi bi-person"></i>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card bg-rh">
                <div><h6>RH</h6><h2><?= $nbRH ?></h2></div>
                <i class="bi bi-person-badge"></i>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card bg-pending">
                <div><h6>En attente</h6><h2><?= $nbAttente ?></h2></div>
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card bg-approved">
                <div><h6>Validées</h6><h2><?= $nbAccept ?></h2></div>
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card bg-inactive">
                <div><h6>Inactifs</h6><h2><?= $nbInactifs ?></h2></div>
                <i class="bi bi-person-x"></i>
            </div>
        </div>
    </div>

    <form method="GET" action="imprimer_periode.php" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="type" class="form-select" required>
                <option value="">-- Période --</option>
                <option value="jour">Jour</option>
                <option value="mois">Mois</option>
                <option value="annee">Année</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-dark w-100">
                <i class="bi bi-download"></i> Télécharger
            </button>
        </div>
    </form>


    <!-- TABLEAU UTILISATEURS -->
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people me-2"></i>Derniers utilisateurs enregistrés</span>
            <a href="listeEmployeIndex.php" style="font-size:13px; color:#0f3460; text-decoration:none;">
                Voir tous <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="ps-4">Matricule</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $u): ?>
                        <tr>
                            <td class="ps-4"><small><?= htmlspecialchars($u['matricule']) ?></small></td>
                            <td><strong><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['tel']) ?></td>
                            <td>
                                <?php if ($u['role_label'] === 'Employé'): ?>
                                    <span class="badge-emp">Employé</span>
                                <?php elseif ($u['role_label'] === 'RH'): ?>
                                    <span class="badge-rh">RH</span>
                                <?php else: ?>
                                    <span class="badge-admin">Admin</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="<?= $u['actif'] ? 'badge-actif' : 'badge-inactif' ?>">
                                    <?= $u['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($u['role_label'] === 'Employé'): ?>
                                    <a class="btn btn-sm btn-outline-primary" href="modifier_employe.php?id_user=<?= $u['id_user'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger" href="supprimer_employe.php?id_user=<?= $u['id_user'] ?>"
                                       onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php elseif ($u['role_label'] === 'RH'): ?>
                                    <a class="btn btn-sm btn-outline-primary" href="modifier_rh.php?id_user=<?= $u['id_user'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger" href="supprimer_rh.php?id_user=<?= $u['id_user'] ?>"
                                       onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleNotifs() {
    document.getElementById('notifDropdown').classList.toggle('show');
}
document.addEventListener('click', function(e) {
    const w = document.querySelector('.notif-wrapper');
    if (w && !w.contains(e.target)) {
        document.getElementById('notifDropdown').classList.remove('show');
    }
});

setInterval(function() {
    fetch('get_notifs.php').then(r => r.json()).then(data => {
        const badge = document.querySelector('.notif-badge');
        if (data.count > 0) {
            if (badge) badge.textContent = data.count;
            else {
                const b = document.createElement('span');
                b.className = 'notif-badge';
                b.textContent = data.count;
                document.querySelector('.notif-wrapper').appendChild(b);
            }
        } else if (badge) badge.remove();
    }).catch(() => {});
}, 30000);
</script>
</body>
</html>