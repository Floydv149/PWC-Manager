<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get design id
$designID = $_GET["id"];

ini_set("display_errors", 1);
error_reporting(E_ALL);

//Get design
$design = mysqli_query($con, "SELECT Naam, CoverID, Inhoud FROM Trolleys_Ontwerpen WHERE ID = $designID;");

$design = mysqli_fetch_assoc($design);

//Add design
$addDesign = mysqli_query($con, "INSERT INTO Trolleys_Ontwerpen (Naam, CoverID, Inhoud) VALUES ('Kopie van " . $design["Naam"] . "', " . $design["CoverID"] . ", '" . $design["Inhoud"] . "')");

$insertID = $con->insert_id;

//Close database connection
$con->close();

header("Location: ../editor?id=" . $insertID);

?>