<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get data from POST
$cartID = $_POST["cartID"];
$newResponsibleID = $_POST["newResponsible"];

//Change responsible
$changeResponsible = mysqli_query($con, "UPDATE Trolleys SET VerantwoordelijkeID = '$newResponsibleID' WHERE ID = '$cartID';");

//Get new responsible name
$getName = mysqli_query($con, "SELECT Voornaam, Achternaam FROM Accounts WHERE ID = $newResponsibleID;");
$getName = mysqli_fetch_assoc($getName);
$name = $getName["Voornaam"] . " " . $getName["Achternaam"];

//Add activity
$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 5, 'Verantwoordelijke gewijzigd naar " . addslashes($name) . "');");

//Close database connection
$con->close();

//Redirect back
header("Location: ../details?id=" . $cartID . "#verantwoordelijke");

?>