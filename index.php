<?php
// Spojení s databází
$servername = "localhost";
$username = "m217578";
$password = "osel1234";
$dbname = "m217578";

// Vytvoření spojení s databází
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola spojení
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dotaz pro vědce
$scientists = $conn->query("SELECT ScientistID, FirstName, LastName, Specialization FROM scientists");

// Dotaz pro experimenty
$experiments = $conn->query("SELECT ExperimentID, Name, Description, StartDate, EndDate FROM experiments");

// Dotaz pro přístroje
$instruments = $conn->query("SELECT InstrumentID, Name, Type, Location FROM instruments");

// Dotaz pro experimentální data
$experimentalData = $conn->query("SELECT ed.DataID, ex.Name as ExperimentName, ed.Observations, ed.RecordedAt FROM experimentaldata ed JOIN experiments ex ON ed.LinkedExperimentID = ex.ExperimentID");

// Dotaz pro získání role vědců v experimentech
$scientistRoles = $conn->query("SELECT scientists.FirstName, scientists.LastName, experiments.Name AS ExperimentName, experimentsscientists.Role FROM experimentsscientists JOIN scientists ON experimentsscientists.LinkedScientistID = scientists.ScientistID JOIN experiments ON experimentsscientists.LinkedExperimentID = experiments.ExperimentID");

// Zavření spojení
$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Experimentální Databáze</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Vítejte v Experimentální Databázi</h1>
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
            <h2>O Projektu</h2>
            <p>Tento projekt poskytuje přehled experimentálních dat shromážděných v naší laboratoři.</p>
	    <br>
	</section>

        <section>
            <h2>Seznam Vědců</h2>
            <table>
                <tr><th>ID</th><th>Jméno</th><th>Příjmení</th><th>Specializace</th></tr>
                <?php while($row = $scientists->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['ScientistID'] ?></td>
                    <td><?= $row['FirstName'] ?></td>
                    <td><?= $row['LastName'] ?></td>
                    <td><?= $row['Specialization'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
	    <br>
        </section>

	<section>
            <h2>Seznam Experimentů</h2>
            <table>
                <tr><th>ID</th><th>Název</th><th>Popis</th><th>Začátek</th><th>Konec</th></tr>
                <?php while($row = $experiments->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['ExperimentID'] ?></td>
                    <td><?= $row['Name'] ?></td>
                    <td><?= $row['Description'] ?></td>
                    <td><?= $row['StartDate'] ?></td>
                    <td><?= $row['EndDate'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
	    <br>
        </section>

        <section>
            <h2>Seznam Přístrojů</h2>
            <table>
                <tr><th>ID</th><th>Název</th><th>Typ</th><th>Umístění</th></tr>
                <?php while($row = $instruments->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['InstrumentID'] ?></td>
                    <td><?= $row['Name'] ?></td>
                    <td><?= $row['Type'] ?></td>
                    <td><?= $row['Location'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
	    <br>
        </section>

	<section>
    	    <h2>Experimentální Data</h2>
            <table>
        	<tr><th>ID</th><th>Experiment</th><th>Pozorování</th><th>Datum Záznamu</th></tr>
        	<?php while($row = $experimentalData->fetch_assoc()): ?>
        	<tr>
            	    <td><?= $row['DataID'] ?></td>
            	    <td><?= $row['ExperimentName'] ?></td>
            	    <td><?= $row['Observations'] ?></td>
            	    <td><?= $row['RecordedAt'] ?></td>
        	</tr>
        	<?php endwhile; ?>
    	    </table>
	    <br>
	</section>

	<section>
    	    <h2>Role Vědců</h2>
    	    <table>
        	<tr><th>Jméno</th><th>Příjmení</th><th>Experiment</th><th>Role</th></tr>
        	<?php while ($row = $scientistRoles->fetch_assoc()): ?>
        	<tr>
            	    <td><?= htmlspecialchars($row['FirstName']) ?></td>
            	    <td><?= htmlspecialchars($row['LastName']) ?></td>
            	    <td><?= htmlspecialchars($row['ExperimentName']) ?></td>
            	    <td><?= htmlspecialchars($row['Role']) ?></td>
        	</tr>
        	<?php endwhile; ?>
    	    </table>
	    <br>
	</section>

    </main>
    <footer>
        <p>&copy; 2024 Experimentální Databáze</p>
    </footer>
</body>
</html>