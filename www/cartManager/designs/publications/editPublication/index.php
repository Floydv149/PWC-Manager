<?php

session_start();

if ($_SESSION["loggedIn"] != true) {
    header("Location: https://schelsgebied.rf.gd/account/inloggen");
} else if (!in_array(50, $_SESSION["permissions"])) {
    header("Location: ../");
}

//Include header
include("/home/schelsge/public_html/includes/header.php");

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get publication id
$publicationID = $_GET["id"];

//Get publication data
$publication = mysqli_query($con, "SELECT Titel, Datum, AfbeeldingURL, CategorieID FROM Publicaties WHERE ID = " . $publicationID . ";");
$publication = $publication->fetch_assoc();

?>

<title>Publicatie wijzigen - Schelsgebied</title>

<style>
    #publicationImage {
        width: 200px;
        border-radius: 5px;
    }
</style>

<div id="container">
    <a class="normal rounded full small" href="../"><img src="/images/icons/back.png">Terug</a>
    <h2 class="center"><img src="/images/icons/pen.png">WIJZIG PUBLICATIE</h2>
    <img id="publicationImage" onclick="fullScreen(this)" src="<?php echo $publication["AfbeeldingURL"]; ?>"
        class="center">
    <hr>
    <form class="center" action="editPublication.php" method="post">
        <input type="hidden" name="id" value="<?php echo $publicationID; ?>">
        <p class="center">Titel</p>
        <input type="text" name="title" value="<?php echo $publication["Titel"]; ?>" required>
        <p class="center">Publiceerdatum</p>
        <input type="date" name="date" value="<?php echo $publication["Datum"]; ?>" required>
        <p class="center">Afbeelding-URL JW.ORG</p>
        <input type="url" name="imageURL" value="<?php echo $publication["AfbeeldingURL"]; ?>" required>
        <p class="center">Categorie</p>
        <select name="category" required>
            <?php
            $categories = mysqli_query($con, "SELECT ID, Naam FROM Publicaties_Categorieen WHERE NOT ID = 0 ORDER BY ID ASC;");
            while ($category = $categories->fetch_assoc()) {
                if ($category["ID"] == $publication["CategorieID"]) {
                    echo "<option id='" . $category["ID"] . "' value='" . $category["ID"] . "' selected>" . $category["Naam"] . "</option>";
                } else {
                    echo "<option id='" . $category["ID"] . "' value='" . $category["ID"] . "'>" . $category["Naam"] . "</option>";
                }
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Wijzigen">
        <hr>
        <a class="normal rounded full small" onclick="deletePublication()"><img src="/images/icons/trash.png">Verwijder
            publicatie</a>
    </form>
</div>
<script>
    function deletePublication() {
        if (confirm("Weet je zeker dat je deze publicatie wil verwijderen? Als deze gekozen is voor ontwerpen kan dit voor problemen zorgen.")) {
            window.location.href = "deletePublication.php?id=" + <?php echo $publicationID; ?>;
        }
    }
</script>

<?php

//Close database connection
$con->close();

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>