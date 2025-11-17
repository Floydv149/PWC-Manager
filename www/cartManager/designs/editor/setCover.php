<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get design id
$designID = $_GET["did"];
$coverID = $_GET["cid"];

//Update cover
$updateCover = mysqli_query($con, "UPDATE Trolleys_Ontwerpen SET CoverID = '$coverID' WHERE ID = '$designID'");

//Close database connection
$con->close();

//Redirect
header("Location: ../editor?id=" . $designID);

?>