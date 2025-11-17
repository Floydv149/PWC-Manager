<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get data
$title = addslashes($_POST['title']);
$date = $_POST['date'];
$imageURL = $_POST['imageURL'];
$categoryID = $_POST['category'];

//Add publication
$addPublication = mysqli_query($con, "INSERT INTO Publicaties (Titel, Datum, AfbeeldingURL, CategorieID) VALUES ('$title', '$date', '$imageURL', '$categoryID')");

//Close database connection
$con->close();

header("Location: ../publications?o=1");

?>