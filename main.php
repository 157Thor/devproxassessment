<!DOCTYPE html>
<html lang="en">

<head>
    <title>DEVPROX Assessment</title>
</head>

<body>
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
    
    $conn->close();
    $conn = new mysqli($servername, $username, $password, 'myDB');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_REQUEST['name'];
        $surname = $_REQUEST['surname'];
        $id = $_REQUEST['id'];
        $date = $_REQUEST['date'];
        $sql = "INSERT INTO myTable VALUES (
            '$id', '$name', '$surname', '$date'
            )";
        echo $sql;
        if ($conn->query($sql) === TRUE) {
            echo "Record entered successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
    }
    ?>
    <h1>DEVPROX Assessment</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name">
        <br>
        <label for="surname">Surname:</label>
        <input type="text" name="surname">
        <br>
        <label for="id">ID:</label>
        <input type="text" name="id">
        <br>
        <label for="date">Date of Birth:</label>
        <input type="date" name="date" placeholder="dd/mm/yyyy" min="1921-01-01" max="2021-12-31">
        <br>
        <button type="submit">Submit</button>
        <button type="reset">Cancel</button>
    </form>
    <?php

    $conn->close();

    ?>

</body>

</html>