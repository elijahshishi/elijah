<?php

$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$db   = "appointments_db";

$conn = new mysqli($host, $user, $pass, $db);

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        echo "
        <form action='update-password.php' method='post'>
            <input type='hidden' name='token' value='$token'>
            <input type='password' name='new_password' placeholder='Enter new password' required>
            <button type='submit'>Update Password</button>
        </form>";
    } else {
        echo "<h3 style='color:red;'>Invalid or expired token.</h3>";
    }
}
$conn->close();
?>
