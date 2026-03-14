<?php 
    $host = 'localhost';
    $bd_name ='agtime';
    $pwd='';
    $user='root';

    try{
        $pdo = new PDO("mysql:host=$host;dbname=$bd_name;charset=utf8",$user,$pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo"con ok";
    } catch(PDOException $ex){
        die("Erreur de connexion: " .$ex->getMessage());
    }

?>