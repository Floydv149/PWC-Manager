<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Include user input validation
include("/home/schelsge/public_html/includes/userInputValidation.php");

//Get data from POST
$cartID = $_POST["cartID"];
$newLocation = test_input($_POST["newLocation"]);

//Change storage location
$changeStorageLocation = mysqli_query($con, "UPDATE Trolleys SET OpslaglocatieID = '$newLocation' WHERE ID = '$cartID';");

//Close database connection
$con->close();

//Redirect back
header("Location: ../details?id=" . $cartID . "#opslaglocatie");

?>