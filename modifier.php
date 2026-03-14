<?php
include_once('bd.php'); // connexion à la base de données avec PDO

if(isset($_POST['modif'])) {
    $id = $_POST['mk']; // l'id de l'utilisateur à modifier
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $tel = htmlspecialchars($_POST['tel']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = htmlspecialchars( $_POST['mdp']); // sécurisation du mot de passe
    $role = $_POST['ft'];

    try {
        // Requête SQL préparée pour éviter les injections SQL
        $sql = "UPDATE utilisateur 
                SET nom = :nom, prenom = :prenom, tel = :tel, email = :email, mot_de_passe = :mot_de_passe, role = :role 
                WHERE id_user = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom'=> $prenom,
            ':tel'=> $tel,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe,
            ':role' => $role,
            ':id' => $id
        ]);

        header('Location: listeEmployeIndex.php');
        // echo "Utilisateur modifié avec succès !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}


if(isset($_POST['modif_rh'])) {
    $id = $_POST['mk']; // l'id de l'utilisateur à modifier
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $tel = htmlspecialchars($_POST['tel']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = htmlspecialchars( $_POST['mdp']); // sécurisation du mot de passe
    $role = $_POST['ft'];

    try {
        // Requête SQL préparée pour éviter les injections SQL
        $sql = "UPDATE utilisateur 
                SET nom = :nom, prenom = :prenom, tel = :tel, email = :email, mot_de_passe = :mot_de_passe, role = :role 
                WHERE id_user = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom'=> $prenom,
            ':tel'=> $tel,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe,
            ':role' => $role,
            ':id' => $id
        ]);

        header('Location: listeRHIndex.php');
        // echo "Utilisateur modifié avec succès !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

 