<?php

$servername = "Localhost";
$username = "root";
$password = "";
$dbname = "appointmed";

$conn new = mysqli($servername, $username, $password, $dbname);

if($conn -> connection_error)
{
    die("Connection Failed:" . $conn -> connect_error);
}
echo
"Connect Successfully";
?>