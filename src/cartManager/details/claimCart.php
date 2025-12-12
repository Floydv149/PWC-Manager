<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get cart ID from GET
$cartID = $_GET["ID"];

//Change current location
$changeCurrentLocation = mysqli_query($con, "UPDATE Trolleys SET BezitterID = '" . $_SESSION["ID"] . "' WHERE ID = '$cartID';");

//Add activity
$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 3, 'Trolley handmatig geclaimd');");

//Close database connection
$con->close();

//Redirect back
header("Location: ../details?id=" . $cartID . "#opslaglocatie");

?>