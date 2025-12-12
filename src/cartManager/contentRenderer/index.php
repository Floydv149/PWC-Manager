<?php

$cartID = $_GET["id"];

$cartContent = mysqli_query($con, "SELECT Trolleys_Ontwerpen.Inhoud FROM Trolleys INNER JOIN Trolleys_Ontwerpen ON Trolleys.OntwerpID = Trolleys_Ontwerpen.ID WHERE Trolleys.ID = " . $cartID);

$cartContent = mysqli_fetch_assoc($cartContent);
$cartContent = json_decode($cartContent['Inhoud']);

for ($i = 0; $i < count($cartContent); $i++) {
    echo "<div class='cartRow'>";
    for ($j = 0; $j < count($cartContent[$i]); $j++) {
        $publication = mysqli_query($con, "SELECT AfbeeldingURL FROM Publicaties WHERE ID = " . $cartContent[$i][$j] . ";");
        echo '<img onclick="fullScreen(this)" src="' . mysqli_fetch_assoc($publication)['AfbeeldingURL'] . '">';
    }
    echo "</div>";
}

//Close database connection
$con->close();

?>