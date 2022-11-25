<?php
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
    date_of_birth DATE NOT NULL
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

$output = array(array(
    "Id",
    "Name",
    "Surname",
    "Initials",
    "Age",
    "DateOfBirth"
));
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_rows = $_REQUEST['rows'];
    $i = 1;
    $unique_names = array();
    // for ($i=1; $i < $num_rows; $i++) { 
    while ($i <= $num_rows) {
?>
        <script>
            console.log(<?php echo $i ?>);
        </script>
<?php
        $name = $names[array_rand($names)];
        $initial = $name[0];
        $surname = $surnames[array_rand($surnames)];
        $unique_name = $name . $surname;
        $date = new DateTime();
        $date->setTimestamp(rand(strtotime("1920-01-01"), time()));
        $today = new DateTime();
        $age = date_diff($date, $today);
        // if (array_key_exists($unique_name, $unique_names)) {
        //     while (true) {
        //         $dates  = $unique_names[$unique_name];
        //         if (in_array($date->format("d/m/Y"), $dates)) {
        //             $date->setTimestamp(rand(strtotime("1920-01-01"), time()));
        //         } else {
        //             $unique_names[$unique_name][] = $date->format("d/m/Y");
        //             break;
        //         }
        //     }
        //     $temp = array(
        //         $i,
        //         $name,
        //         $surname,
        //         $initial,
        //         $age->format('%y'),
        //         $date->format("d/m/Y")
        //     );
        //     $output[] = $temp;
        // } else {
        //     $unique_names[$unique_name] = array($date->format("d/m/Y"));
        //     $temp = array(
        //         $i,
        //         $name,
        //         $surname,
        //         $initial,
        //         $age->format('%y'),
        //         $date->format("d/m/Y")
        //     );
        //     $output[] = $temp;
        // }
        $temp = array(
            $i,
            $name,
            $surname,
            $initial,
            $age->format('%y'),
            $date->format("d/m/Y")
        );
        if (!in_array($temp, $output)) {
            $output[] = $temp;
            $i++;
        }
    }
    header("Content-type: text/csv;");
    header("Content-Disposition: attachment; filename=output.csv");
    $f = fopen("php://memory", 'w');
    foreach ($output as $line) {
        fputcsv($f, $line);
    }
    fseek($f, 0);
    fpassthru($f);
    fclose($f);
    return;
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
        <br>
        <button type="submit">Submit</button>
        <button type="reset">Cancel</button>
    </form>
</body>

</html>