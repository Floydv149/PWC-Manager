<?php

//Include database connection
include ("/home/schelsge/public_html/includes/databaseConnection.php");

//Get data
$publicationID = $_GET['id'];

//Delete publication
$editPublication = mysqli_query($con, "DELETE FROM Publicaties WHERE ID = $publicationID");

//Close database connection
$con->close();

header("Location: ../");

?>