<?php
global $db;
include 'config_bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $userId = $_POST['id'];

        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gestionprojet";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Delete user
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'error: execute failed';
            }
            $stmt->close();
        } else {
            echo 'error: prepare failed';
        }

        $conn->close();
    } else {
        echo 'error: id not set';
    }
} else {
    echo 'error: wrong request method';
}
?>
