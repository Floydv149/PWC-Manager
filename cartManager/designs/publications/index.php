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

?>

<title>Publicaties beheren - Schelsgebied</title>

<style>
    #library {
        min-width: 300px;
        width: calc(100% - 10px);
        background: var(--color-content-background-primary);
        max-height: 60vh;
        align-self: flex-start;
        overflow-y: scroll;
        border: 5px solid var(--color-button-background);
        border-radius: 10px;
        overflow-x: hidden;
        box-sizing: border-box;
        margin: 5px;
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    #library::-webkit-scrollbar,
    .publicationRow::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    #library,
    .publicationRow {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    #library .publicationRow {
        width: 100%;
        overflow-x: scroll;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: left;
        gap: 5px;
        padding: 0px 10px;
        box-sizing: border-box;
    }

    #library .publication {
        max-width: 94.29px;
        min-width: 94.29px;
    }

    #library .publication img {
        border-radius: 2.5px;
    }
</style>

<div id="container">
    <a class="normal rounded full small" href="../"><img src="/images/icons/back.png">Terug</a>
    <h2 class="center"><img src="/images/icons/library.png">PUBLICATIES</h2>
    <details <?php if (isset($_GET["o"])) {
        echo "open";
    } ?>>
        <summary><img src="/images/icons/add.png">Voeg publicatie toe</summary>
        <form class="center" action="addPublication.php" method="post">
            <p class="center">Titel</p>
            <input type="text" name="title" value="" required>
            <p class="center">Publiceerdatum</p>
            <input type="date" name="date" value="" required>
            <p class="center">Afbeelding-URL JW.ORG</p>
            <input type="url" name="imageURL" value="" required>
            <p class="center">Categorie</p>
            <select name="category" required>
                <option value="0">Kies een categorie</option>
                <?php
                $categories = mysqli_query($con, "SELECT ID, Naam FROM Publicaties_Categorieen WHERE NOT ID = 0 ORDER BY ID ASC;");
                while ($category = $categories->fetch_assoc()) {
                    echo "<option id='" . $category["ID"] . "' value='" . $category["ID"] . "'>" . $category["Naam"] . "</option>";
                }
                ?>
            </select>
            <br><br>
            <input type="submit" value="Toevoegen">
        </form>
    </details>
    <div id="library">
        <h3><img src="/images/icons/library.png">Bibliotheek</h3>
        <div id="publications">
            <?php

            //Get all publications
            $publications = mysqli_query($con, "SELECT Publicaties.ID, Publicaties.Titel, Publicaties.Datum, Publicaties.AfbeeldingURL, Publicaties.CategorieID, Publicaties_Categorieen.Naam FROM Publicaties INNER JOIN Publicaties_Categorieen ON Publicaties.CategorieID = Publicaties_Categorieen.ID WHERE NOT Publicaties.ID = 0 ORDER BY Publicaties.CategorieID ASC, Publicaties.Datum DESC;");

            $lastCategory = -1;

            while ($publication = $publications->fetch_assoc()) {
                if ($lastCategory != $publication["CategorieID"]) {
                    echo "</div>";
                    echo "<h4>" . $publication["Naam"] . "</h4>";
                    $lastCategory = $publication["CategorieID"];
                    echo "<div class='publicationRow'>";
                }
                echo "<div class='publication' onclick='location.href=&quot;editPublication?id=" . $publication["ID"] . "&quot;'>";
                echo "<img src='" . $publication["AfbeeldingURL"] . "'></a>";
                echo "</div>";
            }

            echo "</div>";
            ?>
        </div>
    </div>
</div>
<script>
</script>

<?php

//Close database connection
$con->close();

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>