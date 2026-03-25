<?php
include_once("bd.php");

// Récupération de tous les utilisateurs sauf les admins
$rq = " SELECT u.id_user, u.matricule, u.matricule, u.nom, u.prenom, u.tel, u.email, u.mot_de_passe,
           CASE 
               WHEN e.id_user IS NOT NULL THEN 'Employé'
               WHEN r.id_user IS NOT NULL THEN 'RH'
               ELSE 'Inconnu'
           END AS role
    FROM utilisateur u
    LEFT JOIN employe e ON u.id_user = e.id_user
    LEFT JOIN rh r ON u.id_user = r.id_user
    WHERE e.id_user IS NOT NULL OR r.id_user IS NOT NULL
    ORDER BY u.id_user ASC
";

$statement = $pdo->query($rq);
$utilisateurs = $statement->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title> AG-TIME </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script  source="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" >
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="./bootstrap/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Template Stylesheet -->
    <link href="css/admin2.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 sidebar p-3">
            <h4 class="text-center mb-4">Admin</h4>
            <a href="dashboardadmin2.html" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="listeEmployeIndex.php"><i class="bi bi-people"></i> employes</a>
            <a href="listeRHIndex.php"><i class="bi bi-people"></i> RH</a>
            <a href="#"><i class="bi bi-calendar2-check"></i> Demandes</a>
            <a href="#"><i class="bi bi-bar-chart"></i> Statistiques</a>
            <a href="connexion.html"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10 p-4">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Dashboard Administrateur</h3>
                <span class="navbar-text-primary ms-3">
                    <?php
                    session_start();

                    // Vérifier que l'utilisateur est connecté
                    if(!isset($_SESSION['id_user'])) {
                        header("Location: connexion.php");
                        exit;
                    }

                    // Afficher le nom et prénom
                    echo "👤 " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . " ";
                    ?>
                </span> 
            </div>

            <!-- STAT CARDS -->
            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <div class="card card-stat bg-users text-center shadow-sm">
                        <h6>Total utilisateurs</h6>
                        <h2 id="totalUsers">25</h2>
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat bg-requests text-center shadow-sm">
                        <h6>Demandes en attente</h6>
                        <h2 id="pendingRequests">4</h2>
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat bg-approved text-center shadow-sm">
                        <h6>Demandes validées</h6>
                        <h2 id="approvedRequests">15</h2>
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stat bg-inactive text-center shadow-sm">
                        <h6>Comptes inactifs</h6>
                        <h2 id="inactiveUsers">3</h2>
                        <i class="bi bi-person-x"></i>
                    </div>
                </div>
            </div>

            <!-- TABLEAU UTILISATEURS -->
            <div class="card shadow-sm p-3">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Gestion des utilisateurs</h5>
                    <button class="btn btn-primary btn-sm" onclick="ajoutercompte()"><i class="bi bi-plus-circle">
                    </i> Ajouter</button>
                </div>

                <table class="table table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th style="display: none;">id_emp</th>
                                <th>matricule</th>
                                <th>Nom</th>
                                <th>prenom</th>
                                <th>telephone</th>
                                <th>Email</th>
                                <th>mot de passe</th>
                                <th>role</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if($utilisateurs): ?>
                                <?php foreach($utilisateurs as $user): ?>
                                <tr>
                                    <td style="display: none;"><?= htmlspecialchars($user['id_user']) ?></td>
                                    <td><?= htmlspecialchars($user['matricule']) ?></td>
                                    <td><?= htmlspecialchars($user['nom']) ?></td>
                                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                                    <td><?= htmlspecialchars($user['tel']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['mot_de_passe']) ?></td>
                                    <td>
                                        <span ><?= htmlspecialchars($user['role']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if($user['role'] == 'Employé'): ?>
                                            <a class="btn btn-sm btn-primary" href="modifier_employe.php?id_user=<?= $user['id_user']?>">modifier</a>
                                            <a class="btn btn-sm btn-danger" href="supprimer_employe.php?id_user=<?= $user['id_user']?>">supprimer</a>
                                        <?php elseif($user['role'] == 'RH'): ?>
                                            <a class="btn btn-sm btn-primary" href="modifierrh.php?id_user=<?= $user['id_user']?>">modifier</a>
                                            <a class="btn btn-sm btn-danger" href="supprimer_rh.php?id_user=<?= $user['id_user']?>">supprimer</a>
                                        <?php elseif($user['role'] == 'ADMIN'): ?>
                                            <a class="btn btn-sm btn-primary" href="modifieradmin.php?id_user=<?= $user['id_user']?>">modifier</a>
                                            <a class="btn btn-sm btn-danger" href="supprimer_admin.php?id_user=<?= $user['id_user']?>">supprimer</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Aucun utilisateur trouvé</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

 function ajoutercompte() {
        // Redirection vers la liste
    window.location.href = "ajouter_compte.html";
        
}


function modifiercompte() {
     // Redirection vers la liste
    window.location.href = "modifier_employe.html";
        
    }

function supprimercompte() {
        // Redirection vers la liste
    window.location.href = "supprimer_employe.html";
        
    }

</script>

</body>
</html>
