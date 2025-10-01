<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$db   = "appointments_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $newPassword, $token);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "<h3 style='color:green;text-align:center;'>Password updated successfully.</h3>";
        echo "<p style='text-align:center;'><a href='login.html'>Login Now</a></p>";
    } else {
        echo "<h3 style='color:red;text-align:center;'>Error updating password.</h3>";
    }
}
$conn->close();
?>