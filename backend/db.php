<?php
$host = 'localhost'; 
$dbname = 'newlearn'; 
$username = 'root'; // Your MySQL username
$password = 'RaisHatim@2004'; // Your MySQL password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
