<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get cover id and new image
$coverID = $_GET['id'];
$newImage = addslashes($_GET['f']);

//Change name of the cover
$changeName = mysqli_query($con, "UPDATE Trolleys_Covers SET Bestandsnaam = '" . $newImage . "' WHERE ID = '" . $coverID . "'");

//Close database connection
$con->close();

echo "success";

?>