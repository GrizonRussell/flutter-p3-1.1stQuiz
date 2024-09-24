<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        exit();
    }

    if (isset($data['user_firstname'], $data['user_lastname'], $data['user_age'], $data['user_address'], $data['user_username'], $data['user_password'], $data['user_level'])) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_username = :user_username");
        $stmt->bindParam(':user_username', $data['user_username']);
        $stmt->execute();
        $userExists = $stmt->fetchColumn();

        if ($userExists) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO users (user_firstname, user_lastname, user_age, user_address, user_username, user_password, user_level) VALUES (:user_firstname, :user_lastname, :user_age, :user_address, :user_username, :user_password, :user_level)");
        $stmt->bindParam(':user_firstname', $data['user_firstname']);
        $stmt->bindParam(':user_lastname', $data['user_lastname']);
        $stmt->bindParam(':user_age', $data['user_age']);
        $stmt->bindParam(':user_address', $data['user_address']);
        $stmt->bindParam(':user_username', $data['user_username']);
        $stmt->bindParam(':user_password', $data['user_password']);
        $stmt->bindParam(':user_level', $data['user_level']);
        $stmt->execute();

        $userId = $conn->lastInsertId();
        echo json_encode(['success' => true, 'user_id' => $userId, 'user_firstname' => $data['user_firstname'], 'user_lastname' => $data['user_lastname'], 'user_username' => $data['user_username']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
