<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get design id
$designID = $_GET["id"];
$content = $_GET["c"];

//Update design
$updateDesign = mysqli_query($con, "UPDATE Trolleys_Ontwerpen SET Inhoud = '" . $content . "' WHERE id = " . $designID . ";");

//Close database connection
$con->close();

echo "success";

?>