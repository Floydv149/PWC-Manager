<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Include user input validation
include("/home/schelsge/public_html/includes/userInputValidation.php");

//Get data from POST
$cartID = $_POST["cartID"];
$newDate = test_input($_POST["newDate"]);

//Change date
$changeDate = mysqli_query($con, "UPDATE Trolleys SET LaatsteOnderhoud = '$newDate' WHERE ID = '$cartID';");

//Add activity
$message = "De datum van het laatste onderhoud is gewijzigd naar ";

if ($newDate == "") {
    $message .= " leeg";
} else {
    $message .= $newDate;
}

$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 4, '" . $message . "');");

//Close database connection
$con->close();

//Redirect back
header("Location: ../details?id=" . $cartID . "#laatsteOnderhoud");

?>