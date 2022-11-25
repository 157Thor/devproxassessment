<!DOCTYPE html>
<html lang="en">

<head>
    <title>DEVPROX Assessment Test 1</title>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
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

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS test1DB";
    if ($conn->query($sql) !== TRUE) {
        echo "Error creating database: " . $conn->error;
    }

    $sql = "CREATE TABLE IF NOT EXISTS test1DB.myTable (
        id VARCHAR(13) PRIMARY KEY,
        name VARCHAR(30) NOT NULL,
        surname VARCHAR(30) NOT NULL,
        date_of_birth DATE NOT NULL
    )";

    if ($conn->query($sql) !== TRUE) {
        echo "Error creating myTable: " . $conn->error;
    }

    $conn->close();
    $conn = new mysqli($servername, $username, $password, 'test1DB');
    $name = "";
    $surname = "";
    $id = "";
    $id_error = "";
    $date = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_REQUEST['name'];
        $surname = $_REQUEST['surname'];
        $id = $_REQUEST['id'];
        $date = $_REQUEST['date'];
        $result = $conn->query("SELECT * FROM myTable WHERE id = $id");
        if ($result->num_rows > 0) {
            $id_error = "This ID number already exists!";
        } else {
            $sql = "INSERT INTO myTable VALUES (
                '$id', '$name', '$surname', STR_TO_DATE('$date', '%d/%m/%Y')
                )";
            if ($conn->query($sql) === TRUE) {
                echo "Record entered successfully";
                $name = '';
                $surname = '';
                $id = '';
                $date = '';
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    $conn->close();
    ?>
    <h1>DEVPROX Assessment Test 1</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" onsubmit="return validateForm()">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $name ?>">
        <label for="" id="name-error"></label>
        <br>
        <label for="surname">Surname:</label>
        <input type="text" name="surname" id="surname" value="<?php echo $surname ?>">
        <label for="" id="surname-error"></label>
        <br>
        <label for="id">ID:</label>
        <input type="text" name="id" id="id" value="<?php echo $id ?>">
        <label for="" id="id-error"><?php echo $id_error ?></label>
        <br>
        <!-- I make the date field readonly and use jQuery datepicker for validation -->
        <label for="date">Date of Birth:</label>
        <input type="text" name="date" id="date" readonly="readonly" value="<?php echo $date ?>">
        <label for="" id="date-error"></label>
        <br>
        <button type="submit">Submit</button>
        <button type="reset">Cancel</button>
    </form>
    <script>
        $("#date").datepicker({
            dateFormat: "dd/mm/yy",
            minDate: "01/01/1920",
            maxDate: -1,
            changeMonth: true,
            changeYear: true,
            yearRange: "1920:2022",
        });

        function validateForm() {
            var id = $("#id").val();
            var date = $("#date").val();
            var name = $("#name").val();
            var surname = $("#surname").val();
            var flag = true;
            if (!name.match("^[A-Za-z]{1,30}$")) {
                $("#name-error").text("Name is invalid!")
                flag = false;
            } else {
                $("#name-error").empty();
            }
            if (!surname.match("^[A-Za-z]{1,30}$")) {
                $("#surname-error").text("Surname is invalid!")
                flag = false;
            } else {
                $("#surname-error").empty();
            }
            if (id.length != 13 || isNaN(id)) {
                $("#id-error").text("This ID number is invalid!");
                flag = false;
            } else {
                $("#id-error").empty();
            }
            if (!date || date == "") {
                $("#date-error").text("Please enter a date!");
                flag = false;
            } else if (id.substr(0, 2) != date.substr(8, 2) || id.substr(2, 2) != date.substr(3, 2) || id.substr(4, 2) != date.substr(0, 2)) {
                $("#date-error").text("Your Date of Birth doesn't match your ID number!");
                flag = false;
            } else {
                $("#date-error").empty();
            }
            return flag;
        }
    </script>
</body>

</html>