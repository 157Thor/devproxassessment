<?php
ini_set("memory_limit", "1024M");
set_time_limit(0);

$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS test2DB";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS test2DB.csv_import (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    initials CHAR(1) NOT NULL,
    age TINYINT(3) UNSIGNED NOT NULL,
    date_of_birth DATE NOT NULL,
    UNIQUE (name, surname, date_of_birth)
)";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating myTable: " . $conn->error;
}
$conn->close();

$names = array(
    'Johnathon',
    'Anthony',
    'Erasmo',
    'Raleigh',
    'Nancie',
    'Tama',
    'Camellia',
    'Augustine',
    'Christeen',
    'Luz',
    'Diego',
    'Lyndia',
    'Thomas',
    'Georgianna',
    'Leigha',
    'Alejandro',
    'Marquis',
    'Joan',
    'Stephania',
    'Elroy',
);

$surnames = array(
    'Mischke',
    'Serna',
    'Pingree',
    'Mcnaught',
    'Pepper',
    'Schildgen',
    'Mongold',
    'Wrona',
    'Geddes',
    'Lanz',
    'Fetzer',
    'Schroeder',
    'Block',
    'Mayoral',
    'Fleishman',
    'Roberie',
    'Latson',
    'Lupo',
    'Motsinger',
    'Drews',
);

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'generate') {
    $num_rows = $_POST['rows'];
    $i = 1;
    $today = new DateTime();
    $date = new DateTime();
    $start_date = strtotime("1920-01-01");
    $unique_names = array();
    $unique_dates = array();
    header("Content-type: text/csv;");
    header("Content-Disposition: attachment; filename=output.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    $f = fopen("php://memory", 'w');
    fputcsv($f, array(
        "Id",
        "Name",
        "Surname",
        "Initials",
        "Age",
        "DateOfBirth"
    ));
    while ($i <= $num_rows) {
        $name = $names[array_rand($names)];
        $surname = $surnames[array_rand($surnames)];
        $unique_name = $name . $surname;
        $date->setTimestamp(rand($start_date, time()));
        $age = date_diff($date, $today);

        if (array_key_exists($date->format("d/m/Y"), $unique_dates)) {
            if (sizeof($unique_dates[$date->format("d/m/Y")]) >= 399) {
                continue;
            }
            while (true) {
                $unique_names = $unique_dates[$date->format("d/m/Y")];
                if (in_array($unique_name, $unique_names)) {
                    $name = $names[array_rand($names)];
                    $surname = $surnames[array_rand($surnames)];
                    $unique_name = $name . $surname;
                } else {
                    $unique_dates[$date->format("d/m/Y")][] = $unique_name;
                    $temp = array(
                        $i,
                        $name,
                        $surname,
                        $name[0],
                        $age->format('%y'),
                        $date->format("d/m/Y")
                    );
                    fputcsv($f, $temp);
                    $i++;
                    break;
                }
            }
        } else {
            $unique_dates[$date->format("d/m/Y")][] = $unique_name;
            $temp = array(
                $i,
                $name,
                $surname,
                $name[0],
                $age->format('%y'),
                $date->format("d/m/Y")
            );
            fputcsv($f, $temp);
            $i++;
        }
    }
    fseek($f, 0);
    fpassthru($f);
    fclose($f);
    return;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'upload') {
    if (isset($_FILES['file'])) {
        if ($_FILES['file']['error']) {
            echo "File error: " . $_FILES['file']['error'] . "<br>";
        } else {
            $conn = new mysqli($servername, $username, $password, 'test2DB');
            $flag = false;
            $filename = $_FILES['file']['tmp_name'];
            $f = fopen($filename, 'r');
            fgetcsv($f, 100);
            $batchnum = 0;
            while (!feof($f)) {
                $sql = "INSERT INTO csv_import VALUES ";
                while (strlen($sql) < 1000000) {
                    if (!($line = fgetcsv($f, 1000))) {
                        break;
                    }
                    $sql .= "(
                        '$line[0]', '$line[1]', '$line[2]', '$line[3]', '$line[4]', STR_TO_DATE('$line[5]', '%d/%m/%Y')
                    ),";
                }
                $sql = rtrim($sql, ',');
                try {
                    $result = $conn->query($sql);
                    $flag = true;
                } catch (\Throwable $th) {
                    echo $th . "<br>";
                    echo $sql;
                    break;
                }
            }
            if ($flag) {
                echo "File uploaded successfully!";
            } else {
                echo "Error when uploading file.<br>";
                echo $conn->error;
            }
            fclose($f);
            $conn->close();
        }
    } else {
        echo "No file selected <br>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>DEVPROX Assessment Test 2</title>
</head>

<body>
    <h1>DEVPROX Assessment Test 2</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <label for="rows">Enter the number of records to generate: </label>
        <input type="number" name="rows" id="rows">
        <input type="hidden" name="action" value="generate">
        <br>
        <button type="submit">Submit</button>
        <button type="reset">Cancel</button>
    </form>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data">
        <label for="file">Upload file: </label>
        <input type="file" name="file" id="file">
        <input type="hidden" name="action" value="upload">
        <br>
        <button type="submit">Upload</button>
        <button type="reset">Cancel</button>
    </form>
</body>

</html>