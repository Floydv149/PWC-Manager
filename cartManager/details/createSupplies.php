<?php

//Start session
session_start();

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get data from POST
$cartID = $_GET["id"];

//Get design content of cart
$cartDesignContent = mysqli_query($con, "SELECT TD.ID, TD.Inhoud FROM Trolleys INNER JOIN Trolleys_Ontwerpen AS TD ON Trolleys.OntwerpID = TD.ID WHERE Trolleys.ID = '$cartID';");
$cartDesignContent = mysqli_fetch_assoc($cartDesignContent);
$cartContent = json_decode($cartDesignContent["Inhoud"]);

$uniquePublications = [];

for ($i = 0; $i < count($cartContent); $i++) {
    for ($j = 0; $j < count($cartContent[$i]); $j++) {
        if (!in_array($cartContent[$i][$j], $uniquePublications)) {
            $uniquePublications[] = $cartContent[$i][$j];
        }
    }
    echo "</div>";
}

$supplies = [];

for ($i = 0; $i < count($uniquePublications); $i++) {
    $supplies[$uniquePublications[$i]] = 0;
}

//Other language
$supplies["OL"] = 0;

//Return to Jehovah
$supplies["RJ"] = 0;

json_encode($supplies);

//Create supplies in cart
$createSupplies = mysqli_query($con, "UPDATE Trolleys SET Voorraad = '" . json_encode($supplies) . "' WHERE ID = '$cartID';");

//Add activity
$addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 7, 'Voorraad gereset');");

//Close database connection
$con->close();

//Redirect back
header("Location: ../details?id=" . $cartID . "#voorraad");

?>