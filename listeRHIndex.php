<?php
session_start();
include_once("bd.php");

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: connexion.php");
    exit();
}

// ── Supprimer un RH directement ───────────────────────────────────────────────
$succes = "";
$erreur = "";

if (isset($_GET['supprimer'])) {
    $id = (int) $_GET['supprimer'];
    try {
        $pdo->prepare("DELETE FROM utilisateur WHERE id_user = ?")->execute([$id]);
        $succes = "Compte RH supprimé avec succès.";
    } catch (Exception $e) {
        $erreur = "Impossible de supprimer : " . $e->getMessage();
    }
}

// ── Liste RH ──────────────────────────────────────────────────────────────────
$rhs = $pdo->query("
    SELECT u.id_user, u.matricule, u.nom, u.prenom, u.tel, u.email, u.actif, u.date_creation
    FROM utilisateur u
    JOIN rh r ON u.id_user = r.id_user
    ORDER BY u.id_user DESC
")->fetchAll(PDO::FETCH_ASSOC);

$nbTotal  = count($rhs);
$nbActifs = count(array_filter($rhs, fn($r) => $r['actif'] == 1));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AG-TIME — Liste des RH</title>
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
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header {
            background: #fff; border-bottom: 2px solid #f0f2f5; font-weight: 600;
            color: #0f3460; border-radius: 12px 12px 0 0 !important; padding: 14px 20px;
        }
        .mini-stat {
            background: white; border-radius: 10px; padding: 16px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; align-items: center; gap: 14px;
        }
        .mini-stat .ico { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .mini-stat h6 { font-size: 12px; color: #888; margin: 0; }
        .mini-stat h4 { font-size: 22px; font-weight: 700; color: #0f3460; margin: 0; }
        .badge-actif   { background: #d1e7dd; color: #0f5132; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600; }
        .badge-inactif { background: #f8d7da; color: #842029; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600; }
        .search-box { border-radius: 8px; border: 1.5px solid #dee2e6; padding: 9px 14px; font-size: 14px; width: 250px; transition: border-color 0.2s; }
        .search-box:focus { outline: none; border-color: #0f3460; }
        .btn-ajouter { background: #0f3460; color: white; border: none; border-radius: 8px; padding: 9px 18px; font-size: 13px; font-weight: 600; text-decoration: none; transition: background 0.2s; }
        .btn-ajouter:hover { background: #16213e; color: white; }
        .alerte-succes { background: #f0fff4; border: 1.5px solid #b2dfdb; border-radius: 8px; color: #1a7a4a; padding: 12px 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; animation: fadeIn 0.3s ease; }
        .alerte-erreur { background: #fff5f5; border: 1.5px solid #f5c6cb; border-radius: 8px; color: #c0392b; padding: 12px 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
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
            <h4 style="font-weight:700; color:#0f3460; margin:0;"><i class="bi bi-person-badge me-2"></i>Liste des RH</h4>
            <p style="font-size:13px; color:#888; margin:0;">👤 <?= htmlspecialchars($_SESSION['prenom'] . " " . $_SESSION['nom']) ?></p>
        </div>
        <a href="ajouter_compte.php" class="btn-ajouter"><i class="bi bi-person-plus me-1"></i>Ajouter un RH</a>
    </div>

    <?php if (!empty($succes)): ?>
        <div class="alerte-succes mb-3"><i class="bi bi-check-circle-fill"></i> <?= $succes ?></div>
    <?php endif; ?>
    <?php if (!empty($erreur)): ?>
        <div class="alerte-erreur mb-3"><i class="bi bi-exclamation-circle-fill"></i> <?= $erreur ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="mini-stat">
                <div class="ico" style="background:#fce4ec;"><i class="bi bi-person-badge" style="color:#880e4f;"></i></div>
                <div><h6>Total RH</h6><h4><?= $nbTotal ?></h4></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mini-stat">
                <div class="ico" style="background:#d1e7dd;"><i class="bi bi-person-check" style="color:#0f5132;"></i></div>
                <div><h6>Actifs</h6><h4><?= $nbActifs ?></h4></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mini-stat">
                <div class="ico" style="background:#f8d7da;"><i class="bi bi-person-x" style="color:#842029;"></i></div>
                <div><h6>Inactifs</h6><h4><?= $nbTotal - $nbActifs ?></h4></div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Tous les responsables RH</span>
            <input type="text" id="searchInput" class="search-box" placeholder="🔍 Rechercher..." onkeyup="filtrerTableau()">
        </div>
        <div class="card-body p-0">
            <?php if (empty($rhs)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-person-badge" style="font-size:40px;"></i>
                    <p class="mt-2">Aucun RH trouvé.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableRH">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-4">Matricule</th>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date création</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rhs as $rh): ?>
                            <tr>
                                <td class="ps-4"><small><?= htmlspecialchars($rh['matricule']) ?></small></td>
                                <td><strong><?= htmlspecialchars($rh['prenom'] . ' ' . $rh['nom']) ?></strong></td>
                                <td><?= htmlspecialchars($rh['email']) ?></td>
                                <td><?= htmlspecialchars($rh['tel']) ?></td>
                                <td><small><?= date('d/m/Y', strtotime($rh['date_creation'])) ?></small></td>
                                <td>
                                    <span class="<?= $rh['actif'] ? 'badge-actif' : 'badge-inactif' ?>">
                                        <?= $rh['actif'] ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary me-1"
                                       href="modifier_rh.php?id_user=<?= $rh['id_user'] ?>" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger"
                                       href="listeRHIndex.php?supprimer=<?= $rh['id_user'] ?>"
                                       onclick="return confirm('Supprimer ce RH ?')" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </a>
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
function filtrerTableau() {
    const val = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tableRH tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
}
</script>
</body>
</html>