<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Include user input validation
include("/home/schelsge/public_html/includes/userInputValidation.php");

//Get date from POST
$cartID = $_POST["cartID"];
$updatedComment = str_replace(array("\r", "\n"), '', nl2br(addslashes($_POST["updatedComment"])));

//Update comment
$updateComment = mysqli_query($con, "UPDATE Trolleys SET Opmerkingen = '$updatedComment' WHERE ID = '$cartID';");

//Get cart responsible
$cartResponsible = mysqli_query($con, "SELECT VerantwoordelijkeID FROM Trolleys WHERE ID = '$cartID';");
$cartResponsible = mysqli_fetch_assoc($cartResponsible);

$cartResponsible = $cartResponsible["VerantwoordelijkeID"];

if ($cartResponsible != $_SESSION["ID"]) {
    mysqli_query($con, "INSERT INTO Velddienstbijeenkomsten_Updates (AccountID, AfspraakID, Datum, Type, Beschrijving, Doeldatum) VALUES (" . $cartResponsible . ", 0, '" . date("Y-m-d H:i:s") . "', 4, '" . $_SESSION["firstName"] . " " . $_SESSION["lastName"] . " heeft een opmerking toegevoegd/gewijzigd aan je trolley.<br>Neem een kijkje om te zien wat je ermee kan doen.', '" . date("Y-m-d") . "');");
}

// echo "INSERT INTO Velddienstbijeenkomsten_Updates (AccountID, AfspraakID, Datum, Type, Beschrijving, Doeldatum) VALUES (" . $cartResponsible . ", 0, '" . date("Y-m-d H:i:s") . "', 4, '" . $_SESSION["firstName"] . " " . $_SESSION["lastName"] . " heeft een opmerking toegevoegd/gewijzigd aan je trolley. Neem een kijkje om te zien wat je eraan kan doen.', '" . date("Y-m-d") . "');";

//Add activity
$message = "";

if ($updatedComment == "") {
    $message .= "Opmerkingen verwijderd";
} else {
    $message .= "Opmerkingen toegevoegd/gewijzigd: " . nl2br(addslashes($_POST["updatedComment"]));
}

$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 8, '$message');");

//Close database connection
$con->close();

//Redirect back
header("Location: ../details?id=" . $cartID . "#opmerkingen");

?>