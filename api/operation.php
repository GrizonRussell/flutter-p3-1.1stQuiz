<?php

include 'connection.php';

class Operation
{
    function getOperation()
    {
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $returnValue = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($returnValue);
    }
}
$json = isset($_POST['json']) ? $_POST['json'] : "0";
$operation = isset($_POST['operation']) ? $_POST['operation'] : "0";
$operation = new Operation();

switch ($operation) {
    case "getOperation":
        $operation->getOperation();
        break;
        default:
        break;
}