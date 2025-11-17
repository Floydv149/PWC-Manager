<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get data
$cartID = $_GET["cid"];
$designID = $_GET["did"];

//Update cart design
$updateCartDesign = mysqli_query($con, "UPDATE Trolleys SET OntwerpID = $designID WHERE ID = $cartID");

//Add activity
$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 9, 'Ontwerp gewijzigd');");

header("Location: ../details?id=$cartID");

?>