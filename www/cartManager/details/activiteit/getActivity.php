<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get max from GET
$max = $_GET["m"];
$cartID = $_GET["ID"];

$weekDaysNL = [
    "zondag",
    "maandag",
    "dinsdag",
    "woensdag",
    "donderdag",
    "vrijdag",
    "zaterdag"
];

//Get cart activity
$cartActivity = mysqli_query($con, "SELECT TA.Tijdstip, Accounts.Voornaam, Accounts.Achternaam, TA.Type, TA.Bericht FROM Trolleys_Activiteit AS TA INNER JOIN Accounts ON TA.AccountID = Accounts.ID WHERE TA.TrolleyID = " . $cartID . " ORDER BY TA.Tijdstip DESC LIMIT $max;");

$i = 0;

if ($cartActivity->num_rows > 0) {
    $lastDate = "";

    while ($activity = mysqli_fetch_array($cartActivity)) {
        if ($i >= $max - 50) {
            if ($lastDate != date("Y-m-d", strtotime($activity["Tijdstip"]))) {
                if ($lastDate != "") {
                    echo "</table></div>";
                }
                $lastDate = date("Y-m-d", strtotime($activity["Tijdstip"]));
                echo "<h3><img src='/images/icons/calendarIcon.png'> " . $weekDaysNL[date("w", strtotime($activity["Tijdstip"]))] . " " . date("d-m-Y", strtotime($activity["Tijdstip"])) . "</h3>";
                echo "<div class='table'><table>";
                echo "<tr><th><img src='/images/icons/timeAlert.png'>Tijdstip</th><th><img src='/images/icons/person.png'>Persoon</th><th><img src='/images/icons/filter.png'>Type</th><th><img src='/images/icons/change.png'>Wijziging</th></tr>";
                echo "<tr>";
            }

            echo "<td>" . date("H:i", strtotime($activity["Tijdstip"])) . "</td>";
            echo "<td>" . $activity["Voornaam"] . " " . $activity["Achternaam"] . "</td>";
            echo "<td>";
            if ($activity["Type"] == 1) {
                echo "<img src='/images/icons/shelf.png'>Voorraad";
            } else if ($activity["Type"] == 2) {
                echo "<img src='/images/icons/pogg.png'>Staanplaats";
            } else if ($activity["Type"] == 3) {
                echo "<img src='/images/icons/mapIcon.png'>Huidige locatie";
            } else if ($activity["Type"] == 4) {
                echo "<img src='/images/icons/clean.png'>Laatste onderhoud";
            } else if ($activity["Type"] == 5) {
                echo "<img src='/images/icons/admin.png'>Verantwoordelijke";
            } else if ($activity["Type"] == 6) {
                echo "<img src='/images/icons/garage.png'>Opslaglocatie";
            } else if ($activity["Type"] == 7) {
                echo "<img src='/images/icons/shelf.png'>Voorraad";
            } else if ($activity["Type"] == 8) {
                echo "<img src='/images/icons/note.png'>Opmerking";
            } else if ($activity["Type"] == 9) {
                echo "<img src='/images/icons/rulerPen.png'>Ontwerp";
            } else if ($activity["Type"] == 10) {
                echo "<img src='/images/icons/status.png'>Status";
            }
            echo "</td>";
            echo "<td>" . $activity["Bericht"] . "</td>";
            echo "</tr>";
        }
        $i++;
    }

    echo "</table></div>";
} else {
    echo "<p class='center'>Er zijn momenteel nog geen activiteiten vastgelegd voor deze trolley.</p>";
}

//Close database connection
$con->close();

?>