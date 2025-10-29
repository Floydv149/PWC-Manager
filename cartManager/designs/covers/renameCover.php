<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get cover id and new name
$coverID = $_GET['id'];
$newTitle = addslashes($_GET['t']);

//Change name of the cover
$changeName = mysqli_query($con, "UPDATE Trolleys_Covers SET Titel = '" . $newTitle . "' WHERE ID = '" . $coverID . "'");

//Close database connection
$con->close();

echo "success";

?>