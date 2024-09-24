<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'connection.php';

try {
    // $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['user_id'], $data['user_firstname'], $data['user_lastname'], $data['user_age'], $data['user_address'], $data['user_username'], $data['user_level'])) {
        $stmt = $pdo->prepare("UPDATE users SET 
            user_firstname = :user_firstname,
            user_lastname = :user_lastname,
            user_age = :user_age,
            user_address = :user_address,
            user_username = :user_username,
            user_password = :user_password,
            user_level = :user_level
            WHERE user_id = :user_id");

        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':user_firstname' => $data['user_firstname'],
            ':user_lastname' => $data['user_lastname'],
            ':user_age' => $data['user_age'],
            ':user_address' => $data['user_address'],
            ':user_username' => $data['user_username'],
            ':user_password' => $data['user_password'],
            ':user_level' => $data['user_level']
        ]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
