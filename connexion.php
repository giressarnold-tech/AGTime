<?php 
session_start(); 
include_once('bd.php'); 
if (isset($_POST['ok'])) { 
    $email = htmlspecialchars($_POST['email']); 
    $mdp = htmlspecialchars($_POST['mdp']); 
    // 🔍 Recherche par email et mot de passe 
    $req = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ? AND mot_de_passe = ?"); 
    $req->execute([$email,$mdp]); 
    if ($req->rowCount() === 1) { 
        $user = $req->fetch(PDO::FETCH_ASSOC); 
         // 🧠 Sessions 
         $_SESSION['id_user'] = $user['id_user']; 
         $_SESSION['nom'] = $user['nom']; 
         $_SESSION['prenom'] = $user['prenom']; 
         $_SESSION['tel'] = $user['tel']; 
         $_SESSION['email'] = $user['email']; 
         $_SESSION['mot_de_passe'] = $user['mot_de_passe']; 
         $_SESSION['role'] = $user['role']; 
         // 🚦 Redirection selon le rôle 
         switch ($user['role']) { 
            case 'ADMIN': 
                header('Location: dashboardadmin2.php'); 
                break; 
            case 'RH': 
                header('Location: pageRH.php'); 
                break; 
            case 'EMPLOYE': 
                header('Location: pageemployé.php'); 
                break; 
            default: 
                echo "❌ Rôle non reconnu"; 
                } exit();
             } else { 
              echo "❌ Mot de passe incorrect"; 
             } } else{
                 echo "❌ Email incorrect ou compte désactivé"; 
             } 
             
 




