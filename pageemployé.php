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
    <link href="css/pageemploye.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="pageemploye.php">
            <i class="bi bi-people-fill"></i> GESTION DES CONGES
        </a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="listeEmployeIndex.php">dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="listedesdemandes.html">mes Demandes</a></li>
                <li class="nav-item"><a class="nav-link" href="stat.html">mon profil</a></li>
                <li class="nav-item"><a class="nav-link" href="connexion.html">Déconnexion</a></li>
            </ul>
            <span class="navbar-text ms-3">
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
    </div>
</nav>

<!-- CONTENU PRINCIPAL -->
<div class="container mt-4">

    <div class="text-center mb-4">
        <h3 class="fw-bold">Bienvenue sur votre tableau de bord</h3>
        <p class="text-muted">Gérez vos congés et permissions d’absence facilement</p>
    </div>

    <!-- CARTES -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="stat-card bg-green shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Congés restants</h6>
                    <h2 id="congesRestants">0</h2>
                </div>
                <i class="bi bi-calendar2-check"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-orange shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Demandes en attente</h6>
                    <h2 id="demandesAttente">0</h2>
                </div>
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-blue shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Congés validés</h6>
                    <h2 id="congesValides">0</h2>
                </div>
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-red shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Demandes refusées</h6>
                    <h2 id="demandesRefusees">0</h2>
                </div>
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
    </div>

    <!-- BOUTONS ACTION -->
    <div class="text-center my-4">
        <a onclick="nouvelleDemande()" class="btn btn-primary btn-dashboard">
            <i class="bi bi-plus-circle"></i> Nouvelle demande
        </a>
        <a onclick="voirDemandes()" class="btn btn-outline-secondary btn-dashboard">
            <i class="bi bi-eye"></i> Voir mes demandes
        </a>
    </div>

    <!-- HISTORIQUE ET STATS -->
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header fw-bold">Historique récent</div>
                <ul class="list-group list-group-flush" id="historiqueListe">
                    <li class="list-group-item">
                        Congé annuel (12–15 Mai 2024)
                        <span class="float-end status-approved">Approuvé</span>
                    </li>
                    <li class="list-group-item">
                        Permission (05 Avril 2024)
                        <span class="float-end status-pending">En attente</span>
                    </li>
                    <li class="list-group-item">
                        RTT (20 Mars 2024)
                        <span class="float-end status-refused">Refusé</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header fw-bold">Statistiques</div>
                <div class="card-body">
                    <p>Total demandes : <strong id="totalDemandes">0</strong></p>
                    <p>Congés ce mois : <strong id="congesMois">0</strong></p>
                    <p>Jours d’absence : <strong id="joursAbsence">0</strong></p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<script>
/* ============================
   DONNÉES SIMULÉES (FAKE DATA)
   ============================ */

const dashboardData = {
    congesRestants: 1,
    demandesEnAttente: 2,
    congesValides: 8,
    demandesRefusees: 1,

    statistiques: {
        totalDemandes: 30,
        congesMois: 5,
        joursAbsence: 18
    },

    historique: [
        {
            type: "Congé annuel",
            date: "12–15 Mai 2024",
            statut: "Approuvé"
        },
        {
            type: "Permission",
            date: "05 Avril 2024",
            statut: "En attente"
        },
        {
            type: "RTT",
            date: "20 Mars 2024",
            statut: "Refusé"
        }
    ]
};

/* ============================
   INITIALISATION DU DASHBOARD
   ============================ */

document.addEventListener("DOMContentLoaded", () => {
    chargerStatistiques();
    chargerHistorique();
});

/* ============================
   FONCTIONS
   ============================ */

// Chargement des cartes statistiques
function chargerStatistiques() {
    document.querySelector("#congesRestants").innerText = dashboardData.congesRestants;
    document.querySelector("#demandesAttente").innerText = dashboardData.demandesEnAttente;
    document.querySelector("#congesValides").innerText = dashboardData.congesValides;
    document.querySelector("#demandesRefusees").innerText = dashboardData.demandesRefusees;

    document.querySelector("#totalDemandes").innerText = dashboardData.statistiques.totalDemandes;
    document.querySelector("#congesMois").innerText = dashboardData.statistiques.congesMois;
    document.querySelector("#joursAbsence").innerText = dashboardData.statistiques.joursAbsence;
}

// Chargement de l’historique
function chargerHistorique() {
    const liste = document.querySelector("#historiqueListe");
    liste.innerHTML = "";

    dashboardData.historique.forEach(item => {
        const li = document.createElement("li");
        li.className = "list-group-item";

        let statutClass = "";
        if (item.statut === "Approuvé") statutClass = "status-approved";
        if (item.statut === "En attente") statutClass = "status-pending";
        if (item.statut === "Refusé") statutClass = "status-refused";

        li.innerHTML = `
            ${item.type} (${item.date})
            <span class="float-end ${statutClass}">
                ${item.statut}
            </span>
        `;

        liste.appendChild(li);
    });
}

/* ============================
   ACTIONS BOUTONS (SIMULATION)
   ============================ */

function nouvelleDemande() {
    alert(window.location.href = "demande_conge.html");
    // window.location.href = "nouvelle-demande.html";
}

function voirDemandes() {
    alert("Redirection vers la liste des demandes...");
    // window.location.href = "mes-demandes.html";
}
</script>

