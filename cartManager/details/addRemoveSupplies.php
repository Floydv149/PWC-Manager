<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get data from GET
$cartID = $_GET["cID"];
$publicationID = ($_GET["pID"]);
$quantityChange = $_GET["q"];

ini_set("display_errors", 1);

//Get current supplies
$getSupplies = mysqli_query($con, "SELECT Voorraad FROM Trolleys WHERE ID = '$cartID';");
$getSupplies = mysqli_fetch_assoc($getSupplies);
$supplies = (array) json_decode($getSupplies["Voorraad"]);

//Add/remove to supplies
$supplies[$publicationID] = $supplies[$publicationID] + $quantityChange;

//Update supplies
$updateSupplies = mysqli_query($con, "UPDATE Trolleys SET Voorraad = '" . json_encode($supplies) . "' WHERE ID = '$cartID';");

if ($publicationID == "OL") {
    $publicationName = "Andere talen";
} else if ($publicationID == "RJ") {
    $publicationName = "Kom terug bij Jehovah";
} else {
    //Get name of publication
    $publication = mysqli_query($con, "SELECT Titel FROM Publicaties WHERE ID = " . $publicationID . ";");
    $publication = mysqli_fetch_assoc($publication);
    $publicationName = $publication["Titel"];
}

//Add activity
$message = "Er is 1 stuk van de publicatie &quot;" . $publicationName . "&quot; ";

if ($quantityChange > 0) {
    $message .= "toegevoegd aan ";
} else if ($quantityChange < 0) {
    $message .= "verwijderd van ";

    //Save that there is an update on the cart supplies
    $updateSupplies = mysqli_query($con, "UPDATE Trolleys SET VoorraadUpdate = 1 WHERE ID = '$cartID';");
}

$message .= "de voorraad";

$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ('$cartID', " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 1, '" . $message . "');");

//Close database connection
$con->close();

echo "Success";

?>