<?php
session_start();
include_once("bd.php");
//  
if(isset($_POST['envoyer'])){

    //$id_employe = $_SESSION['id_employe'];
    $type_demande = htmlspecialchars($_POST['type_demande']);
    $date_debut = htmlspecialchars($_POST ['date_debut']);
    $date_fin = htmlspecialchars($_POST['date_fin']);
    $raison = htmlspecialchars($_POST['raison']);
    $nm = htmlspecialchars($_POST['nom']);
    $prn = htmlspecialchars($_POST['prenom']);
    
    // insertion demande 
    $id_employe = getId($pdo,$nm,$prn);
    echo $id_employe;
    $sql = "INSERT INTO demande (id_employe, type_demande, date_debut, date_fin, motif) VALUES (?, ?, ?, ?, ?)";
    $prepare = $pdo->prepare($sql);
    $execute = $prepare->execute([$id_employe, $type_demande, $date_debut, $date_fin, $raison]);

    $sqlRH = "SELECT id_user FROM utilisateur WHERE role='RH' OR role='ADMIN'";
    $result = $pdo->query($sqlRH);
    if ($execute) {
        echo "✅ Demande soumise avec succès.";
    header('Location: pageemploye.php?id_employe='+$id_employe);
    } else {
    echo "❌ Tous les champs sont requis pour soumettre une demande.";
    }

    // 🔔 Notification aux RH et ADMIN

    // while($row = $result->fetchAll(PDO::FETCH_ASSOC)){ 
    //      $message = "Nouvelle demande de congé en attente.";
    //      $insertNotif = $conn->prepare(
    //          "INSERT INTO notification (id_user, message) VALUES (?, ?)");
    //      $insertNotif->bind_param("is", $row['id_user'], $message);
    //      $insertNotif->execute();
    //  }
    }
    function getId($pdo,$n,$prn){
        $rq = "SELECT e.id_employe FROM employe e INNER JOIN utilisateur u ON e.id_user = u.id_user
         WHERE u.nom = :n AND u.prenom = :prn";
        $stm = $pdo->prepare($rq);
        $stm->execute([
            ':n' => $n,
            ':prn' => $prn
        ]);
        $trouve = $stm->fetch(PDO::FETCH_ASSOC);
        if ($trouve) {
            return $trouve['id_employe'];
        }
        return null;
    }
?>
