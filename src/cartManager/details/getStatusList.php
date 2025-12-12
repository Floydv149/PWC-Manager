<?php

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

echo "<div id='statusList'>";

$statuses = mysqli_query($con, "SELECT ID, Titel, Afbeelding FROM Trolleys_Status ORDER BY ID ASC;");

while ($status = mysqli_fetch_assoc($statuses)) {
    echo "<div class='status' onclick='setTimeout(function() { setStatus(" . $status['ID'] . ") }, 500); closeModal(this.parentElement.parentElement);'><img src='/images/icons/" . $status['Afbeelding'] . "'><div class='title'>" . $status['Titel'] . "</div></div>";
}

echo "</div>";

?>