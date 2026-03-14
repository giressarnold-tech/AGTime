<?php 
    include_once("bd.php");
    
    session_start();
    $rq = "SELECT u.id_user, u.nom, u.prenom, u.tel, u.email, u.mot_de_passe
           FROM utilisateur u JOIN employe e ON u.id_user=e.id_user";
    $statement = $pdo->query($rq);
    $employe = $statement->fetchAll(PDO::FETCH_ASSOC); 

    // $id_user = $_SESSION['id_user'];

    // $sql = "SELECT * FROM notification 
    //     WHERE id_user=? AND statut='non_lu'
    //     ORDER BY date_creation DESC";

    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("i", $id_user);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // while($notif = $result->fetch_assoc()){
    //     echo "<div class='alert alert-info'>";
    //     echo $notif['message'];
    //     echo "</div>";
    // }
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
    <script source="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap -->
    <link href="./bootstrap/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">


<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Template Stylesheet -->
    <link href="css/pageRH.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="60x60" href="img/Logo contemporain AG-Time sur fond blanc.png">
    
</head>

<body>

<!-- NAVBAR RH -->
<nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="pageRH.html">
            <i class="bi bi-people-fill"></i> Dashboard RH
        </a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="listeEmployeIndex.php">Utilisateurs</a></li>
                <li class="nav-item"><a class="nav-link" href="listedesdemandes.html">Demandes</a></li>
                <li class="nav-item"><a class="nav-link" href="stat.html">Statistiques</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Déconnexion</a></li>
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
<!-- CONTENU -->
<div class="container mt-4">

    <!-- CARTES STATS -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-users shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Total employés</h6>
                    <h2 id="totalUsers">0</h2>
                </div>
                <i class="bi bi-people"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-pending shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Demandes en attente</h6>
                    <h2 id="pendingRequests">0</h2>
                </div>
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-approved shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Demandes validées</h6>
                    <h2 id="approvedRequests">0</h2>
                </div>
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-refused shadow d-flex justify-content-between align-items-center">
                <div>
                    <h6>Demandes refusées</h6>
                    <h2 id="refusedRequests">0</h2>
                </div>
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
    </div>

    <!-- GESTION UTILISATEURS -->
    <div class="card shadow">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Gestion des utilisateurs
            <button class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus"></i> Ajouter
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>prenom</th>
                                <th>telephone</th>
                                <th>Email</th>
                                <th>mot de passe</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if($employe): ?>
                            <?php foreach ($employe as $ep): ?>
                            <tr>
                                <td> <?php echo htmlspecialchars($ep['id_user']) ?> </td>
                                <td><?php echo htmlspecialchars($ep['nom']) ?></td>
                                <td><?php echo htmlspecialchars($ep['prenom']) ?></td>
                                <td><?php echo htmlspecialchars($ep['tel']) ?></td>
                                <td> <?php echo htmlspecialchars($ep['email']) ?> </td>
                                <td><?php echo htmlspecialchars($ep['mot_de_passe']) ?></td>
                                <td>
                                    <span class="badge bg-success" id="status-1">Actif</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary bi bi-pencil" href="modifier_employe.php?id_user=<?= $ep['id_user']?>"
                                        onclick="modifiercompte()"> 
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="supprimercompte()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="toggleStatus(1)">
                                        <i class="bi bi-power"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else :  ?>
                                <p>Aucun employe trouve.</p>
                            <?php endif; ?>
                        </tbody>
                    </table>
        </div>
    </div>

</div>

</body>
</html>

<!-- JS -->
<script>
/* ==========================
   DONNÉES RH SIMULÉES
   ========================== */
const rhData = {
    stats: {
        totalUsers: 5,
        pending: 3,
        approved: 18,
        refused: 2
    },
    users: [
        { matricule: "EMP001", nom: "Jean Mballa", service: "Informatique", role: "Employé", actif: true },
        { matricule: "EMP002", nom: "Aline Nkodo", service: "Finance", role: "Employé", actif: true },
        { matricule: "EMP003", nom: "Paul Ndzi", service: "RH", role: "RH", actif: true },
        { matricule: "EMP004", nom: "Sarah Kengne", service: "Marketing", role: "Employé", actif: false },
        { matricule: "EMP005", nom: "David Fomekong", service: "Logistique", role: "Employé", actif: true }
    ]
};

/* ==========================
   INITIALISATION
   ========================== */
document.addEventListener("DOMContentLoaded", () => {
    chargerStats();
    chargerUtilisateurs();
});

/* ==========================
   FONCTIONS
   ========================== */
function chargerStats() {
    document.getElementById("totalUsers").innerText = rhData.stats.totalUsers;
    document.getElementById("pendingRequests").innerText = rhData.stats.pending;
    document.getElementById("approvedRequests").innerText = rhData.stats.approved;
    document.getElementById("refusedRequests").innerText = rhData.stats.refused;
}

function chargerUtilisateurs() {
    const table = document.getElementById("usersTable");
    table.innerHTML = "";

    rhData.users.forEach(user => {
        const statutBadge = user.actif
            ? `<span class="badge badge-active">Actif</span>`
            : `<span class="badge badge-inactive">Inactif</span>`;

        table.innerHTML += `
            <tr>
                <td>${user.matricule}</td>
                <td>${user.nom}</td>
                <td>${user.service}</td>
                <td>${user.role}</td>
                <td>${statutBadge}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-power"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}
</script>
