<?php

session_start();

if ($_SESSION["loggedIn"] != true) {
    header("Location: https://schelsgebied.rf.gd/account/inloggen");
} else if (!in_array(51, $_SESSION["permissions"])) {
    header("Location: ../");
}

//Include header
include("/home/schelsge/public_html/includes/header.php");

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

$directory = "/home/schelsge/public_html/images/cartCovers/";

if (isset($_POST['submit'])) {
    move_uploaded_file($_FILES["file"]["tmp_name"], $directory . pathinfo(basename($_FILES["file"]["name"]), PATHINFO_FILENAME) . ".png");

    $addCover = mysqli_query($con, "INSERT INTO Trolleys_Covers (Titel, Bestandsnaam) VALUES ('" . addslashes($_POST['title']) . "', '" . pathinfo(basename($_FILES["file"]["name"]), PATHINFO_FILENAME) . ".png" . "');");
}

?>

<title>Covers beheren - Schelsgebied</title>

<style>
    .covers {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin: 5px;
    }

    .cover {
        max-width: 200px;
        min-width: 200px;
        width: min-content;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        /* gap: 5px; */
        background: var(--color-content-background-primary);
        border-radius: 10px;
        border: 2.5px solid var(--color-button-hover-background);
        transition: border-color .25s, background .25s;
    }

    .cover:hover {
        background: var(--color-content-background-secondary);
        border-color: var(--color-button-background);
    }

    .coverImage {
        justify-self: left;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .coverImage img {
        border-radius: 10px;
        margin: 0px;
        width: 200px;
    }

    .title {
        text-align: center;
        padding: 10px;
    }

    .cover a {
        margin: 5px !important;
    }
</style>

<div id="container">
    <a class="normal rounded full small" href="../"><img src="/images/icons/back.png">Terug</a>
    <h2 class="center"><img src="/images/icons/library.png">COVERS</h2>
    <details <?php if (isset($_GET["o"])) {
        echo "open";
    } ?>>
        <summary><img src="/images/icons/add.png">Voeg cover toe</summary>
        <form class="center" action="index.php" method="post" enctype="multipart/form-data">
            <p class="center">Titel</p>
            <input type="text" name="title" value="" required>
            <br><br>
            <p class="center">Afbeelding uploaden</p>
            <table>
                <tr>
                    <td><input type="file" name="file"></td>
                </tr>
            </table>
            </fieldset>
            <input type="submit" name="submit" value="Toevoegen">
        </form>
    </details>
    <?php
    if (in_array(51, $_SESSION["permissions"])) {
        echo "<a href='fileManager' class='normal rounded full medium'><img src='/images/icons/settingsIcon.png'>Beheer cover-afbeeldingen</a>";
    }
    ?>
    <div class="covers">
        <?php
        $covers = mysqli_query($con, "SELECT ID, Titel, Bestandsnaam FROM Trolleys_Covers;");

        while ($cover = mysqli_fetch_assoc($covers)) {
            echo "<div class='cover'><div class='coverImage'><img src='/images/cartCovers/" . $cover["Bestandsnaam"] . "'></div>";
            echo "<div class='title'>" . $cover["Titel"] . "</div><a class='normal rounded full small' onclick='renameCover(" . $cover["ID"] . ")'><img src='/images/icons/passwordInput.png' >Hernoem</a><a class='normal rounded full small' onclick='changeImage(" . $cover["ID"] . ")'><img src='/images/icons/imageIcon.png'>Wijzig afbeelding</a><a class='normal rounded full small' onclick='deleteCover(" . $cover["ID"] . ")'><img src='/images/icons/trash.png'>Verwijder</a></div>";
        }
        ?>
    </div>
</div>
<script>
    //Create a new XMLHttpRequest object
    const xmlhttp = new XMLHttpRequest();

    function deleteCover(id) {
        if (confirm("Weet je zeker dat je deze cover wil verwijderen?")) {
            xmlhttp.onload = function () {
                if (this.responseText == "inUse") {
                    alert("Deze cover is momenteel in gebruik voor een ontwerp, en kan dus niet verwijderd worden. Verwijder eerst de gekoppelde ontwerpen.");
                } else if (this.responseText == "success") {
                    location.reload();
                } else {
                    alert("Er is iets misgegaan: " + this.responseText);
                }
            }

            xmlhttp.open("GET", "deleteCover.php?id=" + id);
            xmlhttp.send();
        }
    }

    function changeImage(id) {
        if (fileName = prompt("Geef een nieuwe bestandsnaam voor de afbeelding van deze cover:")) {
            xmlhttp.onload = function () {
                if (this.responseText == "success") {
                    location.reload();
                } else {
                    alert("Er is iets misgegaan: " + this.responseText);
                }
            }

            xmlhttp.open("GET", "changeImage.php?id=" + id + "&f=" + fileName);
            xmlhttp.send();
        }
    }

    function renameCover(id) {
        if (newName = prompt("Geef een nieuwe titel aan deze cover:")) {
            xmlhttp.onload = function () {
                if (this.responseText == "success") {
                    location.reload();
                } else {
                    alert("Er is iets misgegaan: " + this.responseText);
                }
            }

            xmlhttp.open("GET", "renameCover.php?id=" + id + "&t=" + newName);
            xmlhttp.send();
        }
    }
</script>

<?php

//Close database connection
$con->close();

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>