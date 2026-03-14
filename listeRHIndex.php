<?php 
     include_once("bd.php");

    $rq = "SELECT u.id_user, u.nom, u.prenom, u.tel, u.email, u.mot_de_passe
           FROM utilisateur u JOIN rh e ON u.id_user=e.id_user";
    $statement = $pdo->query($rq);
    $rh = $statement->fetchAll(PDO::FETCH_ASSOC); 
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

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-3">
            <h4 class="text-center mb-4">Admin</h4>
            <a href="dashboardadmin2.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="listeEmployeIndex.php"><i class="bi bi-people"></i> employes</a>
            <a href="listeRHIndex.php" class="active"><i class="bi bi-people"></i> RH</a>
            <a href="#"><i class="bi bi-calendar2-check"></i> Demandes</a>
            <a href="#"><i class="bi bi-bar-chart"></i> Statistiques</a>
            <a href="connexion.html"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
        </div>

        <!-- Contenu -->
        <main class="col-md-10 p-4">

            <h3 class="mb-4">
                <i class="bi bi-people"></i> Liste des rh
            </h3>

            <div class="card shadow">
                <div class="card-body">

                    <table class="table table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>id_rh</th>
                                <th>Nom</th>
                                <th>prenom</th>
                                <th>telephone</th>
                                <th>Email</th>
                                <th>mot de passe</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if($rh): ?>
                            <?php foreach ($rh as $ep): ?>
                            <tr>
                                <td> <?php echo htmlspecialchars($ep['id_user']) ?> </td>
                                <td><?php echo htmlspecialchars($ep['nom']) ?></td>
                                <td><?php echo htmlspecialchars($ep['prenom']) ?></td>
                                <td><?php echo htmlspecialchars($ep['tel']) ?></td>
                                <td> <?php echo htmlspecialchars($ep['email']) ?> </td>
                                <td><?php echo htmlspecialchars($ep['mot_de_passe']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary bi bi-pencil">
                                         <a style="text-decoration: none; color: white;" href="modifierrh.php?id_user=<?= $ep['id_user']?>">
                                            modifier
                                         </a>
                                    </button>
                                     <button class="btn btn-sm btn-danger bi bi-trash">
                                        <a style="text-decoration: none; color: white;" href="supprimer_employe.php?id_user=<?= $ep['id_user']?>">
                                              supprimer
                                        </a>
                                    </button>                                 
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else :  ?>
                                <p>Aucun rh trouve.</p>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS -->
<script src="script.js"></script>
</body>
</html>
<script>
    function toggleStatus(id) {
    const badge = document.getElementById("status-" + id);

    if (badge.classList.contains("bg-success")) {
        badge.classList.remove("bg-success");
        badge.classList.add("bg-danger");
        badge.textContent = "Inactif";
    } else {
        badge.classList.remove("bg-danger");
        badge.classList.add("bg-success");
        badge.textContent = "Actif";
    }
}
     function modifiercompte() {
        // Redirection vers la liste
    window.location.href = "modifier_employe.php";
        
    }

     function supprimercompte() {
        // Redirection vers la liste
    window.location.href = "supprimer_employe.html";
        
    }

</script>