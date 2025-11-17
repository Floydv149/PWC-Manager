<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get cover id
$coverID = $_GET['id'];

//Check if the cover is used somewhere
$check = mysqli_query($con, "SELECT ID FROM Trolleys_Ontwerpen WHERE CoverID = '$coverID';");

if ($check->num_rows > 0) {
    //Cover is used
    echo "inUse";
} else {
    //Delete cover
    mysqli_query($con, "DELETE FROM Trolleys_Covers WHERE ID = '$coverID';");
    echo "success";
}

//Close database connection
$con->close();

?>