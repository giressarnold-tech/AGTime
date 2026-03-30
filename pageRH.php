<?php
session_start();
include_once("bd.php");

// Vérifier que l'utilisateur est connecté et est RH ou ADMIN
if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['RH', 'ADMIN'])) {
    header("Location: connexion.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// ── Récupérer l'id_rh ────────────────────────────────────────────────────────
$stmtRH = $pdo->prepare("SELECT id_rh FROM rh WHERE id_user = ?");
$stmtRH->execute([$id_user]);
$rhRow  = $stmtRH->fetch(PDO::FETCH_ASSOC);
$id_rh  = $rhRow ? $rhRow['id_rh'] : null;

// ── Traitement d'une demande (Accepter / Refuser) ─────────────────────────────
$succes = "";
$erreur = "";

if (isset($_POST['traiter'])) {
    $id_demande  = (int) $_POST['id_demande'];
    $decision    = $_POST['decision'];      // ACCEPTEE ou REFUSEE
    $commentaire = htmlspecialchars($_POST['commentaire']);

    if (!in_array($decision, ['ACCEPTEE', 'REFUSEE'])) {
        $erreur = "Décision invalide.";
    } else {
        // Mettre à jour le statut de la demande
        $pdo->prepare("UPDATE demande SET statut = ? WHERE id_demande = ?")
            ->execute([$decision, $id_demande]);

        // Insérer dans la table validation
        if ($id_rh) {
            $pdo->prepare("INSERT INTO validation (decision, commentaire, id_rh, id_demande)
                           VALUES (?, ?, ?, ?)")
                ->execute([$decision, $commentaire, $id_rh, $id_demande]);
        }

        // Notifier l'employé concerné
        $stmtEmp = $pdo->prepare("
            SELECT u.id_user, u.prenom, u.nom
            FROM utilisateur u
            JOIN employe e ON e.id_user = u.id_user
            JOIN demande d ON d.id_employe = e.id_employe
            WHERE d.id_demande = ?
        ");
        $stmtEmp->execute([$id_demande]);
        $emp = $stmtEmp->fetch(PDO::FETCH_ASSOC);

        if ($emp) {
            $texte = $decision === 'ACCEPTEE'
                ? "✅ Votre demande a été acceptée."
                : "❌ Votre demande a été refusée. Motif : " . $commentaire;

            $pdo->prepare("INSERT INTO notification (id_user, message) VALUES (?, ?)")
                ->execute([$emp['id_user'], $texte]);
        }

        $succes = "La demande a été " . ($decision === 'ACCEPTEE' ? "acceptée" : "refusée") . " avec succès.";
    }
}

// ── Stats réelles ─────────────────────────────────────────────────────────────
$nbEmployes = $pdo->query("SELECT COUNT(*) FROM employe")->fetchColumn();
$nbAttente  = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut = 'EN_ATTENTE'")->fetchColumn();
$nbAccept   = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut = 'ACCEPTEE'")->fetchColumn();
$nbRefus    = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut = 'REFUSEE'")->fetchColumn();

// ── Liste des demandes EN ATTENTE ─────────────────────────────────────────────
$stmtDemandes = $pdo->query("
    SELECT d.id_demande, d.type_demande, d.date_debut, d.date_fin,
           d.motif, d.statut, d.date_demande,
           u.nom, u.prenom, u.email
    FROM demande d
    JOIN employe e ON d.id_employe = e.id_employe
    JOIN utilisateur u ON e.id_user = u.id_user
    ORDER BY d.date_demande DESC
    LIMIT 20
");
$demandes = $stmtDemandes->fetchAll(PDO::FETCH_ASSOC);

// ── Notifications non lues ────────────────────────────────────────────────────
$stmtNotifs = $pdo->prepare("
    SELECT id_notif, message, date_creation
    FROM notification
    WHERE id_user = ? AND statut = 'non_lu'
    ORDER BY date_creation DESC
");
$stmtNotifs->execute([$id_user]);
$notifications = $stmtNotifs->fetchAll(PDO::FETCH_ASSOC);
$nb_notifs     = count($notifications);

// Marquer comme lues
if (isset($_GET['voir_notifs'])) {
    $pdo->prepare("UPDATE notification SET statut = 'lu' WHERE id_user = ?")
        ->execute([$id_user]);
    header("Location: pageRH.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Dashboard RH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* CLOCHE */
        .notif-wrapper { position: relative; display: inline-block; cursor: pointer; }
        .notif-badge {
            position: absolute; top: -6px; right: -8px;
            background: #e94560; color: white; border-radius: 50%;
            padding: 1px 5px; font-size: 10px; font-weight: 700;
            min-width: 18px; text-align: center;
        }
        .notif-dropdown {
            display: none; position: absolute; right: 0; top: 32px;
            width: 320px; background: #fff; border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15); z-index: 9999; overflow: hidden;
        }
        .notif-dropdown.show { display: block; }
        .notif-header { background: #0f3460; color: white; padding: 10px 16px; font-size: 13px; font-weight: 600; }
        .notif-item { padding: 10px 16px; border-bottom: 1px solid #f0f0f0; font-size: 13px; color: #333; }
        .notif-item:last-child { border-bottom: none; }
        .notif-item small { color: #999; display: block; margin-top: 3px; }
        .notif-vide { padding: 16px; text-align: center; color: #aaa; font-size: 13px; }

        /* STATS */
        .stat-card {
            border-radius: 12px; padding: 20px; color: white;
            display: flex; justify-content: space-between; align-items: center;
        }
        .stat-card h6 { font-size: 13px; margin: 0 0 6px; opacity: 0.9; }
        .stat-card h2 { font-size: 32px; font-weight: 700; margin: 0; }
        .stat-card i  { font-size: 36px; opacity: 0.3; }
        .bg-users   { background: linear-gradient(135deg, #0f3460, #0984e3); }
        .bg-pending { background: linear-gradient(135deg, #fdcb6e, #e17055); }
        .bg-approved{ background: linear-gradient(135deg, #00b894, #00cec9); }
        .bg-refused { background: linear-gradient(135deg, #e94560, #d63031); }

        /* CARTES */
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header {
            background: #fff; border-bottom: 2px solid #f0f2f5;
            font-weight: 600; color: #0f3460;
            border-radius: 12px 12px 0 0 !important; padding: 14px 20px;
        }

        /* BADGES */
        .badge-attente  { background: #fff3cd; color: #856404; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600; }
        .badge-acceptee { background: #d1e7dd; color: #0f5132; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600; }
        .badge-refusee  { background: #f8d7da; color: #842029; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600; }

        /* ALERTES */
        .alerte-succes {
            background: #f0fff4; border: 1.5px solid #b2dfdb;
            border-radius: 8px; color: #1a7a4a; padding: 12px 16px;
            font-size: 13px; display: flex; align-items: center; gap: 8px;
            animation: fadeIn 0.3s ease;
        }
        .alerte-erreur {
            background: #fff5f5; border: 1.5px solid #f5c6cb;
            border-radius: 8px; color: #c0392b; padding: 12px 16px;
            font-size: 13px; display: flex; align-items: center; gap: 8px;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* MODAL TRAITEMENT */
        .modal-header { background: #0f3460; color: white; border-radius: 12px 12px 0 0; }
        .modal-header .btn-close { filter: invert(1); }
        .modal-content { border-radius: 12px; border: none; }

        .btn-accepter {
            background: #00b894; color: white; border: none;
            border-radius: 8px; padding: 8px 20px; font-weight: 600;
            transition: background 0.2s;
        }
        .btn-accepter:hover { background: #00cec9; color: white; }

        .btn-refuser {
            background: #e94560; color: white; border: none;
            border-radius: 8px; padding: 8px 20px; font-weight: 600;
            transition: background 0.2s;
        }
        .btn-refuser:hover { background: #d63031; color: white; }

        .page-title { font-weight: 700; color: #0f3460; margin-bottom: 4px; }
        .page-subtitle { font-size: 13px; color: #888; margin-bottom: 0; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="pageRH.php">
            <i class="bi bi-people-fill me-1"></i> AG-TIME — RH
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="pageRH.php">
                        <i class="bi bi-house me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ajouter_compte.php">
                        <i class="bi bi-person-plus me-1"></i>Ajouter employé
                    </a>
                </li>

                <!-- CLOCHE -->
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
                                    — <a href="pageRH.php?voir_notifs=1"
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

                <!-- Statistiques -->
                <li class="nav-item">
                    <a class="nav-link" href="statistiques.php">
                        <i class="bi bi-bar-chart-line me-1"></i>Statistiques
                    </a>
                </li>

                </li>

                <!-- Déconnexion -->
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

    <?php if (!empty($succes)): ?>
        <div class="alerte-succes mb-3">
            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($succes) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($erreur)): ?>
        <div class="alerte-erreur mb-3">
            <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <!-- Titre -->
    <div class="mb-4">
        <h4 class="page-title">Dashboard RH</h4>
        <p class="page-subtitle">Bienvenue, <?= htmlspecialchars($_SESSION['prenom']) ?> 👋</p>
    </div>

    <!-- CARTES STATS -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-users shadow">
                <div>
                    <h6>Total employés</h6>
                    <h2><?= $nbEmployes ?></h2>
                </div>
                <i class="bi bi-people"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-pending shadow">
                <div>
                    <h6>Demandes en attente</h6>
                    <h2><?= $nbAttente ?></h2>
                </div>
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-approved shadow">
                <div>
                    <h6>Demandes acceptées</h6>
                    <h2><?= $nbAccept ?></h2>
                </div>
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-refused shadow">
                <div>
                    <h6>Demandes refusées</h6>
                    <h2><?= $nbRefus ?></h2>
                </div>
                <i class="bi bi-x-circle"></i>
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

    <!-- TABLEAU DES DEMANDES -->
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-inbox me-2"></i>Demandes reçues</span>
            <span class="badge bg-warning text-dark"><?= $nbAttente ?> en attente</span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($demandes)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:40px;"></i>
                    <p class="mt-2">Aucune demande pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-4">Employé</th>
                                <th>Type</th>
                                <th>Du</th>
                                <th>Au</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($demandes as $d): ?>
                                <tr>
                                    <td class="ps-4">
                                        <strong><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></strong>
                                        <small class="d-block text-muted"><?= htmlspecialchars($d['email']) ?></small>
                                    </td>
                                    <td><?= $d['type_demande'] === 'CONGE' ? '🏖️ Congé' : '🕐 Permission' ?></td>
                                    <td><?= date('d/m/Y', strtotime($d['date_debut'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($d['date_fin'])) ?></td>
                                    <td style="max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
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
                                    <td class="text-center">
                                        <a href="imprimer.php?id=<?= $d['id_demande'] ?>" 
                                            class="btn btn-sm btn-outline-dark" target="_blank">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <?php if ($d['statut'] === 'EN_ATTENTE'): ?>
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="ouvrirModal(
                                                    <?= $d['id_demande'] ?>,
                                                    '<?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?>',
                                                    '<?= $d['type_demande'] ?>',
                                                    '<?= date('d/m/Y', strtotime($d['date_debut'])) ?>',
                                                    '<?= date('d/m/Y', strtotime($d['date_fin'])) ?>'
                                                )">
                                                <i class="bi bi-pencil-square me-1"></i>Traiter
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size:12px;">Déjà traité</span>
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

<!-- MODAL TRAITEMENT -->
<div class="modal fade" id="modalTraiter" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Traiter la demande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="pageRH.php">
                <div class="modal-body">
                    <input type="hidden" name="id_demande" id="modal_id_demande">

                    <!-- Infos de la demande -->
                    <div class="mb-3 p-3" style="background:#f8f9fa; border-radius:8px;">
                        <p class="mb-1"><strong>Employé :</strong> <span id="modal_employe"></span></p>
                        <p class="mb-1"><strong>Type :</strong> <span id="modal_type"></span></p>
                        <p class="mb-1"><strong>Période :</strong> <span id="modal_periode"></span></p>
                    </div>

                    <!-- Décision -->
                    <div class="mb-3">
                        <label class="form-label fw-600">Décision <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="decision"
                                       id="accepter" value="ACCEPTEE" required>
                                <label class="form-check-label text-success fw-600" for="accepter">
                                    ✅ Accepter
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="decision"
                                       id="refuser" value="REFUSEE">
                                <label class="form-check-label text-danger fw-600" for="refuser">
                                    ❌ Refuser
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-2">
                        <label class="form-label">Commentaire <small class="text-muted">(obligatoire si refus)</small></label>
                        <textarea name="commentaire" class="form-control" rows="3"
                                  placeholder="Expliquez votre décision..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="traiter" class="btn-accepter">
                        <i class="bi bi-check2 me-1"></i>Confirmer la décision
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function ouvrirModal(id, employe, type, debut, fin) {
    document.getElementById('modal_id_demande').value = id;
    document.getElementById('modal_employe').textContent = employe;
    document.getElementById('modal_type').textContent = type === 'CONGE' ? '🏖️ Congé' : '🕐 Permission';
    document.getElementById('modal_periode').textContent = debut + ' → ' + fin;
    new bootstrap.Modal(document.getElementById('modalTraiter')).show();
}

function toggleNotifs() {
    const dd = document.getElementById('notifDropdown');
    dd.classList.toggle('show');
}

document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notifDropdown').classList.remove('show');
    }
});

// Polling 30 secondes
setInterval(function() {
    fetch('get_notifs.php')
        .then(r => r.json())
        .then(data => {
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

