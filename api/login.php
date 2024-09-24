<?php
include 'connection.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data) {
    $username = $data['user_username'];
    $password = $data['user_password'];

    $sql = "SELECT * FROM users WHERE user_username= :username AND user_password=:password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $returnValue = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($returnValue);
} else {
    echo json_encode(['error' => 'Invalid JSON']);
}
?>
