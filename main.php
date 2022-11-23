<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS myDB";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

// $conn->close();

// $conn = new mysqli($servername, $username, $password, "myDB");
$sql = "CREATE TABLE IF NOT EXISTS myDB.myTable (
    id VARCHAR(13) PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    date_of_birth DATE NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "myTable created successfully";
} else {
    echo "Error creating myTable: " . $conn->error;
}

?>