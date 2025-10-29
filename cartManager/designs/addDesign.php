<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get data
$name = addslashes($_POST["name"]);

//Add design
$addDesign = mysqli_query($con, "INSERT INTO Trolleys_Ontwerpen (Naam) VALUES ('$name')");

$insertID = $con->insert_id;

//Close database connection
$con->close();

header("Location: editor?id=" . $insertID);

?>