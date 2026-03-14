<?php 
    include_once("bd.php");
    $id = $_GET["id_user"];
    $rq = $pdo->prepare("SELECT * FROM utilisateur WHERE id_user = ?");
    $rq->execute([$id]);
    $lui = $rq->fetch();

    if(isset($_POST["sup_e"])) {

    $id = $_GET["id_user"];
    $sql = "DELETE FROM employe WHERE employe.id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    $sql = "DELETE FROM utilisateur WHERE utilisateur.id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("location: listeEmployeIndex.php");
}
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
    <link href="css/supprimer_employe.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    
</head>
<body>

<div class="container-fluid" style="text-align: center;">
    <div class="row">
        <!-- Contenu -->
     <form action="" method="post">
        <main class="col-md-10 content" >

            <h3 class="mb-4 text-danger">
                <i class="bi bi-trash-fill"></i>
                Suppression d’un utilisateur
            </h3>

            <div class="card delete-card shadow">
                <div class="card-body text-center">

                    <i class="bi bi-exclamation-triangle-fill text-danger warning-icon"></i>

                    <h5 class="mt-3">Confirmer la suppression</h5>

                    <p class="mt-3">
                        Voulez-vous vraiment supprimer l’utilisateur :
                        <strong id="username"> <?= $lui['nom'] ?> <?= $lui['prenom'] ?> </strong> ?
                    </p>

                    <p class="text-danger fw-bold">
                        Cette action est définitive et ne peut pas être annulée.
                    </p>

                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button class="btn btn-secondary" onclick="annulerSuppression()">
                            <i class="bi bi-x-circle"></i> Annuler
                        </button>

                        <!-- <button class="btn btn-danger bi bi-trash" type="submit" name="sup_e">
                             Supprimer
                        </button> -->
                        
                        <button type="submit" class="btn btn-danger" name="sup_e">
                            <i class="bi bi-person-plus"></i> supprimer
                        </button>
                    </div>

                </div>
            </div>

        </main>
     </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS -->
<script src="script.js"></script>

</body>
</html>
<script>
    function annulerSuppression() {
    alert("Suppression annulée.");
    // Redirection vers la liste
    window.location.href = "listeEmployeIndex.php";
}

function confirmerSuppression() {
    const nom = document.getElementById("username").innerText;

    // Simulation suppression
    alert("L'utilisateur " + nom + " a été supprimé avec succès.");

    // Redirection après suppression
    window.location.href = "dashboardadmin2.html";
}

</script>