<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get design id
$cartID = $_GET["cid"];
$statusID = $_GET["sid"];
if (isset($_GET["r"])) {
    $redirect = false;
} else {
    $redirect = true;
}

//Change status
$changeStatus = mysqli_query($con, "UPDATE Trolleys SET StatusID = '$statusID' WHERE ID = '$cartID';");

//Get status name
$statusName = mysqli_query($con, "SELECT Titel FROM Trolleys_Status WHERE ID = $statusID;");
$statusName = mysqli_fetch_assoc($statusName);
$statusName = $statusName["Titel"];

//Add activity
$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 10, 'Status gewijzigd naar: $statusName');");

//Close database connection
$con->close();

//Redirect back
if ($redirect) {
    header("Location: ../details?id=" . $cartID . "#status");
} else {
    echo "Success";
}

?>