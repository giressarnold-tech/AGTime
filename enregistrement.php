<?php  
    include_once('bd.php');
    if(isset($_POST['ok'])){
        $nom = htmlspecialchars($_POST['nom']);
        $prn = htmlspecialchars($_POST['prn']);
        $email = htmlspecialchars($_POST['email']);
        $tel = htmlspecialchars($_POST['tel']);
        $mdp = htmlspecialchars($_POST['mdp']);

        $rq = "INSERT INTO utilisateur(nom, prenom, tel, email, mot_de_passe, role) VALUES(?, ?, ?, ?, ?, ?)";
        $prepare = $pdo->prepare($rq);
        $execute = $prepare->execute(array($nom, $prn, $tel, $email, $mdp, "ADMIN"));
        if ($execute) {
            header('Location: dashboardadmin2.php');
                } else {
            echo"Erreur d'enregistrement";
        }
    }

?>