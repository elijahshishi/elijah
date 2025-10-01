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
    
    $patient_name = trim($_POST['patient_name']);
    $email        = trim($_POST['email']);
    $symptoms     = trim($_POST['symptoms']);
    $phone        = trim($_POST['phone']);
    $date         = $_POST['date'];
    $gender       = $_POST['gender'];

   
    if (empty($patient_name) || empty($email) || empty($symptoms) || 
        empty($phone) || empty($date) || empty($gender)) {
        die("All fields are required.");
    }

  
    $stmt = $conn->prepare("INSERT INTO appointments 
        (patient_name, email, symptoms, phone, date, gender) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $patient_name, $email, $symptoms, $phone, $date, $gender);

    if ($stmt->execute()) {
        echo "<h2 style='color:green;text-align:center;'>Appointment booked successfully!</h2>";
        echo "<p style='text-align:center;'><a href='homepage.html'>Back to Home</a></p>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
