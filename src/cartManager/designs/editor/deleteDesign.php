<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get design id
$designID = $_GET['id'];

//Check if design is used somewhere
$check = mysqli_query($con, "SELECT ID FROM Trolleys WHERE OntwerpID = '$designID';");

if ($check->num_rows > 0) {
    //Design is used
    echo "inUse";
} else {
    //Delete design
    mysqli_query($con, "DELETE FROM Trolleys_Ontwerpen WHERE ID = '$designID';");
    echo "success";
}

//Close database connection
$con->close();

?>