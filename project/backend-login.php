<?php

session_start();


$host = "localhost";   
$user = "root";        
$pass = "";            
$db   = "appointments_db"; 

$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        die("Username and password are required.");
    }

   
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $dbUsername, $dbPassword);
        $stmt->fetch();

        
        if (password_verify($password, $dbPassword)) {
            
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $dbUsername;

            header("Location: homepage.html");
            exit;
        } else {
            echo "<h3 style='color:red;text-align:center;'>Invalid password.</h3>";
        }
    } else {
        echo "<h3 style='color:red;text-align:center;'>User not found.</h3>";
    }

    $stmt->close();
}

$conn->close();
?>
