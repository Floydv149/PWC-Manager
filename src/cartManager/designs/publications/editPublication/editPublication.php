<?php

//Include database connection
include ("/home/schelsge/public_html/includes/databaseConnection.php");

//Get data
$publicationID = $_POST['id'];
$title = addslashes($_POST['title']);
$date = $_POST['date'];
$imageURL = $_POST['imageURL'];
$categoryID = $_POST['category'];

//Edit publication
$editPublication = mysqli_query($con, "UPDATE Publicaties SET Titel = '$title', Datum = '$date', AfbeeldingURL = '$imageURL', CategorieID = $categoryID WHERE ID = $publicationID");

//Close database connection
$con->close();

header("Location: ../");

?>