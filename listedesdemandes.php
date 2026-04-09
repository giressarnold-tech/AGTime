<?php
session_start();
include_once("bd.php");

if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['RH', 'ADMIN'])) {
    header("Location: connexion.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// ── Récupérer id_rh ───────────────────────────────────────────────────────────
$stmtRH = $pdo->prepare("SELECT id_rh FROM rh WHERE id_user = ?");
$stmtRH->execute([$id_user]);
$rhRow  = $stmtRH->fetch(PDO::FETCH_ASSOC);
$id_rh  = $rhRow ? $rhRow['id_rh'] : null;

// ── Traitement demande ────────────────────────────────────────────────────────
$succes = "";
$erreur = "";

if (isset($_POST['traiter'])) {
    $id_demande  = (int) $_POST['id_demande'];
    $decision    = $_POST['decision'];
    $commentaire = htmlspecialchars($_POST['commentaire']);

    if (!in_array($decision, ['ACCEPTEE', 'REFUSEE'])) {
        $erreur = "Décision invalide.";
    } else {
        $pdo->prepare("UPDATE demande SET statut = ? WHERE id_demande = ?")
            ->execute([$decision, $id_demande]);

        if ($id_rh) {
            $pdo->prepare("INSERT INTO validation (decision, commentaire, id_rh, id_demande) VALUES (?,?,?,?)")
                ->execute([$decision, $commentaire, $id_rh, $id_demande]);
        }

        // Notifier l'employé
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

        $succes = "Demande " . ($decision === 'ACCEPTEE' ? "acceptée" : "refusée") . " avec succès.";
    }
}

// ── Filtre statut ─────────────────────────────────────────────────────────────
$filtre = isset($_GET['statut']) ? $_GET['statut'] : 'TOUS';
$where  = $filtre !== 'TOUS' ? "WHERE d.statut = " . $pdo->quote($filtre) : "";

// ── Liste des demandes ────────────────────────────────────────────────────────
$demandes = $pdo->query("
    SELECT d.id_demande, d.type_demande, d.date_debut, d.date_fin,
           d.motif, d.statut, d.date_demande,
           u.nom, u.prenom, u.email, u.matricule
    FROM demande d
    JOIN employe e ON d.id_employe = e.id_employe
    JOIN utilisateur u ON e.id_user = u.id_user
    $where
    ORDER BY d.date_demande DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ── Stats rapides ─────────────────────────────────────────────────────────────
$nbTous     = $pdo->query("SELECT COUNT(*) FROM demande")->fetchColumn();
$nbAttente  = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut='EN_ATTENTE'")->fetchColumn();
$nbAccept   = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut='ACCEPTEE'")->fetchColumn();
$nbRefus    = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut='REFUSEE'")->fetchColumn();

// ── Notifications ─────────────────────────────────────────────────────────────
$stmtNotifs = $pdo->prepare("SELECT COUNT(*) as count FROM notification WHERE id_user = ? AND statut = 'non_lu'");
$stmtNotifs->execute([$id_user]);
$nb_notifs = $stmtNotifs->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Liste des demandes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">
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

        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header {
            background: #fff; border-bottom: 2px solid #f0f2f5;
            font-weight: 600; color: #0f3460;
            border-radius: 12px 12px 0 0 !important; padding: 14px 20px;
        }

        /* STATS MINI */
        .stat-mini {
            border-radius: 10px; padding: 14px 18px; color: white;
            display: flex; align-items: center; gap: 14px;
        }
        .stat-mini i  { font-size: 28px; opacity: 0.4; }
        .stat-mini h6 { font-size: 12px; margin: 0; opacity: 0.9; }
        .stat-mini h3 { font-size: 24px; font-weight: 700; margin: 0; }
        .bg-tous     { background: linear-gradient(135deg, #0f3460, #0984e3); }
        .bg-attente  { background: linear-gradient(135deg, #fdcb6e, #e17055); }
        .bg-accept   { background: linear-gradient(135deg, #00b894, #00cec9); }
        .bg-refus    { background: linear-gradient(135deg, #e94560, #d63031); }

        /* BADGES */
        .badge-attente  { background: #fff3cd; color: #856404; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600; }
        .badge-acceptee { background: #d1e7dd; color: #0f5132; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600; }
        .badge-refusee  { background: #f8d7da; color: #842029; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600; }

        /* FILTRES */
        .filtre-btn {
            border-radius: 20px; padding: 5px 16px; font-size: 13px;
            font-weight: 500; border: 1.5px solid #dee2e6;
            background: white; color: #555; cursor: pointer;
            text-decoration: none; transition: all 0.2s;
        }
        .filtre-btn:hover, .filtre-btn.actif {
            background: #0f3460; color: white; border-color: #0f3460;
        }

        .alerte-succes {
            background: #f0fff4; border: 1.5px solid #b2dfdb; border-radius: 8px;
            color: #1a7a4a; padding: 12px 16px; font-size: 13px;
            display: flex; align-items: center; gap: 8px; animation: fadeIn 0.3s ease;
        }
        .alerte-erreur {
            background: #fff5f5; border: 1.5px solid #f5c6cb; border-radius: 8px;
            color: #c0392b; padding: 12px 16px; font-size: 13px;
            display: flex; align-items: center; gap: 8px; animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .modal-header { background: #0f3460; color: white; border-radius: 12px 12px 0 0; }
        .modal-header .btn-close { filter: invert(1); }
        .modal-content { border-radius: 12px; border: none; }

        /* CLOCHE */
        .notif-icon { position: relative; display: inline-block; }
        .notif-badge-top {
            position: absolute; top: -5px; right: -8px;
            background: #e94560; color: white; border-radius: 50%;
            padding: 1px 5px; font-size: 10px; font-weight: 700;
            min-width: 18px; text-align: center;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4><?= $_SESSION['role'] === 'ADMIN' ? 'Administration' : 'RH' ?></h4>
    <?php if ($_SESSION['role'] === 'ADMIN'): ?>
        <a href="dashboardadmin2.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="listeEmployeIndex.php"><i class="bi bi-people"></i> Employés</a>
        <a href="listeRHIndex.php"><i class="bi bi-person-badge"></i> RH</a>
    <?php else: ?>
        <a href="pageRH.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <?php endif; ?>
    <a href="listedesdemandes.php" class="active"><i class="bi bi-calendar2-check"></i> Demandes</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
</div>

<!-- CONTENU -->
<div class="main-content">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 style="font-weight:700; color:#0f3460; margin:0;">
                <i class="bi bi-calendar2-check me-2"></i>Liste des demandes
            </h4>
            <p style="font-size:13px; color:#888; margin:0;">
                👤 <?= htmlspecialchars($_SESSION['prenom'] . " " . $_SESSION['nom']) ?>
                &nbsp;|&nbsp;
                <a href="pageRH.php" style="color:#0f3460; text-decoration:none; font-size:13px;">
                    <i class="bi bi-bell<?= $nb_notifs > 0 ? '-fill' : '' ?>"></i>
                    <?= $nb_notifs > 0 ? $nb_notifs . ' notification(s)' : 'Aucune notification' ?>
                </a>
            </p>
        </div>
    </div>

    <!-- Alertes -->
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

    <!-- STATS MINI -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-mini bg-tous shadow">
                <i class="bi bi-list-check"></i>
                <div><h6>Total</h6><h3><?= $nbTous ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-mini bg-attente shadow">
                <i class="bi bi-hourglass-split"></i>
                <div><h6>En attente</h6><h3><?= $nbAttente ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-mini bg-accept shadow">
                <i class="bi bi-check-circle"></i>
                <div><h6>Acceptées</h6><h3><?= $nbAccept ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-mini bg-refus shadow">
                <i class="bi bi-x-circle"></i>
                <div><h6>Refusées</h6><h3><?= $nbRefus ?></h3></div>
            </div>
        </div>
    </div>

    <!-- FILTRES -->
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <a href="listedesdemandes.php"               class="filtre-btn <?= $filtre === 'TOUS'       ? 'actif' : '' ?>">Toutes</a>
        <a href="listedesdemandes.php?statut=EN_ATTENTE" class="filtre-btn <?= $filtre === 'EN_ATTENTE' ? 'actif' : '' ?>">⏳ En attente</a>
        <a href="listedesdemandes.php?statut=ACCEPTEE"   class="filtre-btn <?= $filtre === 'ACCEPTEE'   ? 'actif' : '' ?>">✅ Acceptées</a>
        <a href="listedesdemandes.php?statut=REFUSEE"    class="filtre-btn <?= $filtre === 'REFUSEE'    ? 'actif' : '' ?>">❌ Refusées</a>
    </div>

    <!-- TABLEAU -->
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Demandes <?= $filtre !== 'TOUS' ? '— ' . $filtre : '' ?></span>
            <span class="badge bg-secondary"><?= count($demandes) ?> résultat(s)</span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($demandes)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:40px;"></i>
                    <p class="mt-2">Aucune demande trouvée.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-4">Employé</th>
                                <th>Matricule</th>
                                <th>Type</th>
                                <th>Du</th>
                                <th>Au</th>
                                <th>Motif</th>
                                <th>Date demande</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($demandes as $d): ?>
                            <tr>
                                <td class="ps-4">
                                    <strong><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></strong>
                                    <small class="d-block text-muted"><?= htmlspecialchars($d['email']) ?></small>
                                </td>
                                <td><small><?= htmlspecialchars($d['matricule']) ?></small></td>
                                <td><?= $d['type_demande'] === 'CONGE' ? '🏖️ Congé' : '🕐 Permission' ?></td>
                                <td><?= date('d/m/Y', strtotime($d['date_debut'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($d['date_fin'])) ?></td>
                                <td style="max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    <?= htmlspecialchars($d['motif']) ?>
                                </td>
                                <td><small><?= date('d/m/Y H:i', strtotime($d['date_demande'])) ?></small></td>
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
                                        <span class="text-muted" style="font-size:12px;">Traité</span>
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
            <form method="POST" action="listedesdemandes.php<?= $filtre !== 'TOUS' ? '?statut='.$filtre : '' ?>">
                <div class="modal-body">
                    <input type="hidden" name="id_demande" id="modal_id_demande">
                    <div class="mb-3 p-3" style="background:#f8f9fa; border-radius:8px;">
                        <p class="mb-1"><strong>Employé :</strong> <span id="modal_employe"></span></p>
                        <p class="mb-1"><strong>Type :</strong> <span id="modal_type"></span></p>
                        <p class="mb-0"><strong>Période :</strong> <span id="modal_periode"></span></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Décision <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="decision" value="ACCEPTEE" id="rad_accept" required>
                                <label class="form-check-label text-success fw-bold" for="rad_accept">✅ Accepter</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="decision" value="REFUSEE" id="rad_refus">
                                <label class="form-check-label text-danger fw-bold" for="rad_refus">❌ Refuser</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Commentaire <small class="text-muted">(obligatoire si refus)</small></label>
                        <textarea name="commentaire" class="form-control" rows="3"
                                  placeholder="Expliquez votre décision..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="traiter" class="btn btn-primary">
                        <i class="bi bi-check2 me-1"></i>Confirmer
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
</script>
</body>
</html>