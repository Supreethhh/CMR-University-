<?php
$host = 'localhost';
$db = 'task_management';
$user = 'root';  // Update if needed
$password = '';  // Update if needed

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
