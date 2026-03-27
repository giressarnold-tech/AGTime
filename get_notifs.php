<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(["count" => 0]);
    exit;
}

include_once('bd.php');

$stmt = $pdo->prepare("
    SELECT COUNT(*) as count 
    FROM notification 
    WHERE id_user = ? AND statut = 'non_lu'
");
$stmt->execute([$_SESSION['id_user']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($result);
?>