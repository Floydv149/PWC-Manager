<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get design id
$designID = $_POST['id'];
$name = addslashes($_POST['name']);

//Change name of design
$changeName = mysqli_query($con, "UPDATE Trolleys_Ontwerpen SET Naam = '" . $name . "' WHERE ID = '" . $designID . "'");

//Close database connection
$con->close();

header("Location: ../editor?id=" . $designID);

?>