<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

echo "<div id='designs'>";

$designs = mysqli_query($con, "SELECT Trolleys_Ontwerpen.ID, Trolleys_Ontwerpen.Naam, Trolleys_Ontwerpen.Inhoud, Trolleys_Covers.Bestandsnaam FROM Trolleys_Ontwerpen INNER JOIN Trolleys_Covers ON Trolleys_Ontwerpen.CoverID = Trolleys_Covers.ID ORDER BY Aanmaakdatum DESC, Trolleys_Ontwerpen.ID DESC;");

while ($design = mysqli_fetch_assoc($designs)) {
    echo "<div class='design' onclick='setTimeout(function() { setDesign(" . $design["ID"] . ") }, 500); closeModal(this.parentElement.parentElement)'><div class='looks'><div class='cover'><img src='/images/cartCovers/" . $design["Bestandsnaam"] . "'></div><div class='cart'>";
    $cartContent = json_decode($design["Inhoud"]);
    for ($i = 0; $i < count($cartContent); $i++) {
        echo "<div class='cartRow'>";
        for ($j = 0; $j < count($cartContent[$i]); $j++) {
            $publication = mysqli_query($con, "SELECT AfbeeldingURL FROM Publicaties WHERE ID = " . $cartContent[$i][$j] . ";");
            echo '<img src="' . mysqli_fetch_assoc($publication)['AfbeeldingURL'] . '">';
        }
        echo "</div>";
    }
    echo "</div></div><div class='title'>" . $design["Naam"] . "</div></div>";
}

echo "</div>";

?>