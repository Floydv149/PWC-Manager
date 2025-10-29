<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Include user input validation
include("/home/schelsge/public_html/includes/userInputValidation.php");

//Get data from POST
$cartID = $_POST["cartID"];
$newLocation = test_input($_POST["newLocation"]);

//Change current location
$changeCurrentLocation = mysqli_query($con, "UPDATE Trolleys SET BezitterID = '$newLocation' WHERE ID = '$cartID';");

//Get name of new possessor if there is one
if ($newLocation != 0) {
    $getPossessor = mysqli_query($con, "SELECT Voornaam, Achternaam FROM Accounts WHERE ID = '$newLocation';");
    $getPossessor = mysqli_fetch_assoc($getPossessor);
    $possessor = $getPossessor["Voornaam"] . " " . $getPossessor["Achternaam"];
}

//Add activity
$message = "";

if ($newLocation == 0) {
    $message = "Huidige locatie gewijzigd naar de opslaglocatie";
} else {
    $message = "Huidige bezitter gewijzigd naar " . addslashes($possessor);
}

$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 3, '$message');");

//Close database connection
$con->close();

//Redirect back
header("Location: ../details?id=" . $cartID . "#huidigeLocatie");

?>