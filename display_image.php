<?php
// Nastavení MIME typ na JPEG
header('Content-Type: image/jpeg');

// Vytvoření spojení s databází
$servername = "localhost";
$username = "m217578";
$password = "osel1234";
$dbname = "m217578";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Název obrázku pro načtení
$imageName = 'atom.jpg';

// Příprava SQL dotazu pro získání obrázku z databáze podle názvu
$stmt = $conn->prepare("SELECT image_data FROM images WHERE image_name = ?");
$stmt->bind_param("s", $imageName);
$stmt->execute();
$stmt->bind_result($imageData);
$stmt->fetch();
$stmt->close();
$conn->close();

// Výstup dat obrázku
echo $imageData;
?>