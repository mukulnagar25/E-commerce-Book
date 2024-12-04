<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

// Database configuration
$db_host = 'localhost:3307';
$db_user = 'root';
$db_pass = '';
$db_name = 'shop_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Check for the 'endpoint' query parameter
if (isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        switch ($endpoint) {
            case 'users':
                $result = $conn->query("SELECT * FROM users");
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
                break;

            case 'cart':
                $result = $conn->query("SELECT * FROM cart");
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
                break;

            case 'orders':
                $result = $conn->query("SELECT * FROM orders");
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
                break;

            case 'products':
                $result = $conn->query("SELECT * FROM products");
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
                break;

            default:
                echo json_encode(["error" => "Invalid endpoint"]);
                break;
        }
    }
} else {
    echo json_encode(["error" => "No endpoint specified"]);
}
?>
