<?php
include_once("bd.php");
// Vérifier que l'ID existe
//$id = isset($_GET['id_user']) ? $_GET['id_user'] : null;
//if (!$id) die("Erreur : aucun utilisateur sélectionné.");
$id = $_GET["id_user"];

// Requête
$st = $pdo->prepare("SELECT u.id_user, u.nom, u.prenom, u.tel, u.email, u.mot_de_passe, u.role, u.actif
                     FROM utilisateur u 
                     JOIN employe e ON u.id_user=e.id_user 
                     WHERE u.id_user=?");
$st->execute([$id]);

$ep = $st->fetch();
//if (!$ep) die("Erreur : utilisateur introuvable.");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="./bootstrap/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Template Stylesheet -->
    <link href="css/modifier_compte.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    
</head>

<body>

<div class="container-fluid">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-2 bg-primary sidebar p-3">
    <h4><i class="bi bi-people-fill"></i> RH / Admin</h4>
    <a href="#"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="#"><i class="bi bi-file-text"></i> Demandes</a>
    <a href="#" class="active"><i class="bi bi-people"></i> Utilisateurs</a>
    <a href="#"><i class="bi bi-bar-chart"></i> Statistiques</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
</div>

<!-- CONTENU -->
<div class="col-md-10 p-4">

<h3 class="mb-4">
    <i class="bi bi-person-plus-fill"></i> modifier personnel
</h3>

<div class="card shadow-sm p-4">
<form id="userForm" method="post" action="modifier.php">

<div class="row mb-3">

    <div class="col-md-6">
        <label class="form-label" for="id_user">id_user</label>
        <input type="text" value="<?= $ep['id_user'] ?>" name="mk" class="form-control" placeholder="id_user" readonly>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="name">Nom</label>
        <input type="text" value="<?= $ep['nom'] ?>" name="nom" class="form-control" placeholder="nom" required>
    </div>

    
</div>

<div class="row mb-3">

    <div class="col-md-6">
        <label class="form-label" for="name">prenom</label>
        <input type="text" value="<?= $ep['prenom'] ?>" name="prenom" class="form-control" placeholder="prenom" required>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="tel">telephone</label>
        <input type="tel" value="<?= $ep['tel'] ?>" name="tel" class="form-control" placeholder="" required>
    </div>
    
</div>

<div class="row mb-3">

    <div class="col-md-6">
        <label class="form-label" for="email">email</label>
        <input type="email" value="<?= $ep['email'] ?>" name="email" class="form-control" placeholder="example@gmail.com" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Mot de passe</label>
        <input type="password" value="<?= $ep['mot_de_passe'] ?>" name="mdp" class="form-control" id="password" required>
    </div>

    <div class="col-12">
        <label class="form-label">Rôle</label>
        <select class="form-select" name="ft"  required>
            <option>Employé</option>
            <option>RH</option>
            <option>Administrateur</option>
        </select>
    </div>
    
</div>

<div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" id="activeAccount" checked>
    <label class="form-check-label">
        Activer le compte
    </label>
</div>

<div class="d-flex justify-content-end gap-2">
    <button type="reset" class="btn btn-danger">
        <i class="bi bi-x-circle"></i> Annuler
    </button>
    <button type="submit" class="btn btn-primary" name="modif">
        <i class="bi bi-person-plus"></i> modifier
    </button>
</div>

</form>
</div>

</div>
</div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("editUserForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const actif = document.getElementById("activeSwitch").checked;

    if (!actif) {
        if (!confirm("Voulez-vous vraiment désactiver ce compte ?")) {
            return;
        }
    }

    alert("Les informations de l'utilisateur ont été mises à jour avec succès.");
});
</script>

</body>
</html>
