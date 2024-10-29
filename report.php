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

// Nahrání obrázku do databáze
if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
    // Načtení dat obrázku
    $imageData = file_get_contents($_FILES['image']['tmp_name']);
    $imageType = $_FILES['image']['type'];
    $imageName = $_FILES['image']['name']; // Zde se ukládá název souboru

    // Příprava SQL dotazu pro vložení obrázku
    $stmt = $conn->prepare("INSERT INTO images (image_data, mime_type, image_name) VALUES (?, ?, ?)");
    $null = NULL;
    $stmt->bind_param("bss", $null, $imageType, $imageName);
    $stmt->send_long_data(0, $imageData);

    // Vykonání SQL dotazu
    if ($stmt->execute()) {
        echo "Obrázek byl úspěšně nahrán.";
    } else {
        echo "Nepodařilo se nahrát obrázek: " . $stmt->error;
    }
    $stmt->close();
}

// SQL dotaz 1: Získání počtu experimentů pro každého vědce - agregační dotaz mezi tabulkami scientist a experiments
$sql1 = "SELECT s.FirstName, s.LastName, COUNT(e.ExperimentID) AS NumberOfExperiments
        FROM scientists s
        JOIN experimentsscientists es ON s.ScientistID = es.LinkedScientistID
        JOIN experiments e ON es.LinkedExperimentID = e.ExperimentID
        GROUP BY s.ScientistID, s.FirstName, s.LastName
        ORDER BY NumberOfExperiments DESC";
$result1 = $conn->query($sql1);

// SQL dotaz 2: Seznam vědců pracujících na experimentech pouze pro rok 2024 - využití poddotazu (subquery)
$sql2 = "SELECT DISTINCT s.FirstName, s.LastName
        FROM scientists s
        JOIN experimentsscientists es ON s.ScientistID = es.LinkedScientistID
        WHERE es.LinkedExperimentID IN (
            SELECT e.ExperimentID
            FROM experiments e
            WHERE e.StartDate > '2024-01-01'
        )";
$result2 = $conn->query($sql2);

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Report Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Analýza dat z databáze</h1>
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

        <section>
            <form action="" method="post" enctype="multipart/form-data">
                Vyberte obrázek k nahrání:
                <input type="file" name="image" accept="image/*">
                <input type="submit" value="Nahrát Obrázek">
            </form>
	    <br>
	    <! -- Zobrazení obrázku, který není v souboru, ale přímo v BLOBu v databázi (obrázek je načten z databéze pomocí souboru "display_image.php") -->
	    <img src="display_image.php" alt="Atom Image">
	    <br>
        </section>

        <section>
            <h1>Agregační dotaz spojující tabulky scientists a experiments - Počet Experimentů pro Každého Vědce</h1>
            <?php if ($result1->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Jméno</th>
                        <th>Příjmení</th>
                        <th>Počet Experimentů</th>
                    </tr>
                    <?php while ($row = $result1->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['FirstName']) ?></td>
                        <td><?= htmlspecialchars($row['LastName']) ?></td>
                        <td><?= $row['NumberOfExperiments'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Žádná data k zobrazení.</p>
            <?php endif; ?>
	    <br>
        </section>

        <section>
            <h1>Využití poddotazu (subquery) - Seznam vědců pracujících na experimentech pouze pro rok 2024</h1>
            <?php if ($result2->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Jméno</th>
                        <th>Příjmení</th>
                    </tr>
                    <?php while ($row = $result2->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['FirstName']) ?></td>
                        <td><?= htmlspecialchars($row['LastName']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Žádní vědci nebyli nalezeni.</p>
            <?php endif; ?>
        </section>

    </main>
    <?php $conn->close(); ?>
    <footer>
        <p>&copy; 2024 Experimentální Databáze</p>
    </footer>
</body>
</html>