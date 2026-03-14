<?php 
    //session_start();
    include_once("bd.php");

    if(isset($_POST["ajt"])){
        try {
            $pdo->beginTransaction();
            $nom = htmlspecialchars($_POST["nom"]);
            $prenom = htmlspecialchars($_POST["prenom"]);
            $tel = htmlspecialchars($_POST["tel"]);
            $email = htmlspecialchars($_POST["email"]);
            $mdp = htmlspecialchars($_POST["mdp"]);
            $ft = htmlspecialchars($_POST["ft"]);

            $rq = "INSERT INTO utilisateur(nom,prenom,tel,email,mot_de_passe,role) VALUES (?,?,?,?,?,?)";
            $prepare = $pdo->prepare($rq);
            $execute = $prepare->execute([$nom,$prenom,$tel,$email,$mdp,$ft]); 
            
            $u_id = $pdo->lastInsertId();
            switch ($ft) {
                case 'ADMIN':
                    $rqA = "INSERT INTO administrateur(id_user) VALUES (?)";
                    $prepare = $pdo->prepare($rqA);
                    $execute = $prepare->execute([$u_id]); 
                    header('Location: dashboardadmin2.php');
                    break;
            case 'EMPLOYE':
                    $rqE = "INSERT INTO employe(id_user) VALUES (?)";
                    $prepare = $pdo->prepare($rqE);
                    $execute = $prepare->execute([$u_id]); 
                    header('Location: dashboardadmin2.php');
                    break;
                case 'RH':
                    $rqR = "INSERT INTO rh(id_user) VALUES (?)";
                    $prepare = $pdo->prepare($rqR);
                    $execute = $prepare->execute([$u_id]); 
                    header('Location: dashboardadmin2.php');
                        break;
                default:
                    # code...
                    break;
            }
            $pdo->commit(); 
        } catch (Exception $e) {
            $pdo->rollback();
            echo"ERREUR : ". $e->getMessage();
        }
    }

?>