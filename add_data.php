<?php
// Spojení s databází
$servername = "localhost";
$username = "m217578";
$password = "osel1234";
$dbname = "m217578";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Zpracování formuláře pro tabulku experiments
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_experiments'])) {
    // Získání dat z formuláře pro experiment
    $name = $_POST['name'];
    $description = $_POST['description'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Připravení SQL dotazu pro vložení dat
    $sql = "INSERT INTO experiments (Name, Description, StartDate, EndDate) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $description, $startDate, $endDate);
    $stmt->execute();
    $stmt->close();
}

// Zpracování formuláře pro tabulku scientists
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_scientists'])) {
    // Získání dat z formuláře pro vědce
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $specialization = $_POST['specialization'];

    // Připravení SQL dotazu pro vložení dat
    $sql = "INSERT INTO scientists (FirstName, LastName, Specialization) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $firstName, $lastName, $specialization);
    $stmt->execute();
    $stmt->close();
}

// Zpracování formuláře pro tabulku instruments
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_instruments'])) {
    // Získání dat z formuláře pro přístroje
    $name = $_POST['instrument_name'];
    $type = $_POST['type'];
    $location = $_POST['location'];

    // Připravení SQL dotazu pro vložení dat
    $sql = "INSERT INTO instruments (Name, Type, Location) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $type, $location);
    $stmt->execute();
    $stmt->close();
}

// Zpracování formuláře pro tabulku experimentaldata
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_experimentaldata'])) {
    // Získání dat z formuláře pro experimentální data
    $experimentName = $_POST['experiment_name'];
    $observations = $_POST['observations'];
    $recordedAt = $_POST['recorded_at'];

    // Najít ExperimentID podle Názvu Experimentu
    $sql = "SELECT ExperimentID FROM experiments WHERE Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $experimentName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Pokud byl experiment nalezen, vložit data do tabulky experimentaldata
    if ($row) {
        $experimentID = $row['ExperimentID'];

        // Připravení SQL dotazu pro vložení dat
        $sql_data = "INSERT INTO experimentaldata (LinkedExperimentID, Observations, RecordedAt) VALUES (?, ?, ?)";
        $stmt_data = $conn->prepare($sql_data);
        $stmt_data->bind_param("iss", $experimentID, $observations, $recordedAt);
        $stmt_data->execute();
        $stmt_data->close();
    } else {
        echo "Experiment s tímto názvem nebyl nalezen.";
    }
    $stmt->close();
}

// Zpracování formuláře pro tabulku experimentsscientists
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_experimentsscientists'])) {
    $experimentName = $_POST['experiment_name'];
    $scientistLastname = $_POST['scientist_lastname'];
    $role = $_POST['role'];

    // Najít ExperimentID podle Názvu Experimentu
    $stmt = $conn->prepare("SELECT ExperimentID FROM experiments WHERE Name = ?");
    $stmt->bind_param("s", $experimentName);
    $stmt->execute();
    $result = $stmt->get_result();
    $experiment = $result->fetch_assoc();

    // Najít ScientistID podle Příjmení Vědce
    $stmt = $conn->prepare("SELECT ScientistID FROM scientists WHERE LastName = ?");
    $stmt->bind_param("s", $scientistLastname);
    $stmt->execute();
    $result = $stmt->get_result();
    $scientist = $result->fetch_assoc();

    if ($experiment && $scientist) {
        $experimentID = $experiment['ExperimentID'];
        $scientistID = $scientist['ScientistID'];

        // Vložení dat do tabulky experimentsscientists
        $stmt = $conn->prepare("INSERT INTO experimentsscientists (LinkedExperimentID, LinkedScientistID, Role) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $experimentID, $scientistID, $role);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Experiment nebo vědec nebyl nalezen.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat Data</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Přidat nová data do databáze</h1>
        <nav>
            <ul>
                <li><a href="index.php">Domů</a></li>
                <li><a href="add_data.php">Přidat Data</a></li>
                <li><a href="readme.html">Readme</a></li>
                <li><a href="report.php">Report</a></li>
            </ul>
        </nav>
    </header>
    <main>

        <section id="add-experiments">
            <h2>Přidat Experiment</h2>
            <form action="add_data.php" method="post">
                <label for="name">Název Experimentu:</label>
                <input type="text" id="name" name="name" required><br>
                <label for="description">Popis:</label>
                <input type="text" id="description" name="description" required><br>
                <label for="start_date">Datum Začátku:</label>
                <input type="date" id="start_date" name="start_date" required><br>
                <label for="end_date">Datum Konce:</label>
                <input type="date" id="end_date" name="end_date" required><br>
                <input type="submit" name="submit_experiments" value="Přidat Experiment">
            </form>
        </section>

        <section id="add-scientists">
            <h2>Přidat Vědce</h2>
            <form action="add_data.php" method="post">
                <label for="first_name">Jméno:</label>
                <input type="text" id="first_name" name="first_name" required><br>
                <label for="last_name">Příjmení:</label>
                <input type="text" id="last_name" name="last_name" required><br>
                <label for="specialization">Specializace:</label>
                <input type="text" id="specialization" name="specialization" required><br>
                <input type="submit" name="submit_scientists" value="Přidat Vědce">
            </form>
        </section>

        <section id="add-instruments">
            <h2>Přidat Přístroj</h2>
            <form action="add_data.php" method="post">
                <label for="instrument_name">Název Přístroje:</label>
                <input type="text" id="instrument_name" name="instrument_name" required><br>
                <label for="type">Typ:</label>
                <input type="text" id="type" name="type" required><br>
                <label for="location">Umístění:</label>
                <input type="text" id="location" name="location" required><br>
                <input type="submit" name="submit_instruments" value="Přidat Přístroj">
            </form>
        </section>

        <section id="add-experimentaldata">
    		<h2>Přidat Experimentální Data</h2>
    		<form action="add_data.php" method="post">
        		<label for="experiment_name">Název Experimentu:</label>
        		<input type="text" id="experiment_name" name="experiment_name" required><br>

        		<label for="observations">Pozorování:</label>
        		<input type="text" id="observations" name="observations" required><br>

        		<label for="recorded_at">Datum Záznamu:</label>
        		<input type="date" id="recorded_at" name="recorded_at" required><br>

        		<input type="submit" name="submit_experimentaldata" value="Přidat Data">
    		</form>
	</section>

        <section id="add-experimentsscientists">
    		<h2>Přidat Roli Vědce</h2>
    		<form action="add_data.php" method="post">
        		<label for="experiment_name">Název Experimentu:</label>
        		<input type="text" id="experiment_name" name="experiment_name" required><br>

        		<label for="scientist_lastname">Příjmení Vědce:</label>
        		<input type="text" id="scientist_lastname" name="scientist_lastname" required><br>

        		<label for="role">Role:</label>
        		<input type="text" id="role" name="role" required><br>

        		<input type="submit" name="submit_experimentsscientists" value="Přidat Roli">
    		</form>
	</section>

    </main>
    <footer>
        <p>&copy; 2024 Experimentální Databáze</p>
    </footer>
</body>
</html>