<?php

$host = "localhost";  
$user = "root";       
$pass = "";         
$db   = "appointments_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email)) {
        die("Email is required.");
    }

    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "<h3 style='color:red;text-align:center;'>Email not found.</h3>";
        exit;
    }

 
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour")); 

    
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expires, $email);

    if ($stmt->execute()) {
        
        $resetLink = "http://localhost/reset-password.php?token=$token"; 
        

     
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n$resetLink\n\nThis link expires in 1 hour.";
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<h3 style='color:green;text-align:center;'>Reset link sent to your email.</h3>";
        } else {
            echo "<h3 style='color:red;text-align:center;'>Error sending email. Check server mail settings.</h3>";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
