<?php

session_start();

if ($_SESSION["loggedIn"] != true) {
    header("Location: https://schelsgebied.rf.gd/account/inloggen");
} else if (!in_array(48, $_SESSION["permissions"])) {
    header("Location: ../../../");
}

//Include header
include("/home/schelsge/public_html/includes/header.php");

$designID = $_GET["id"];

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

//Get design data
$design = mysqli_query($con, "SELECT Naam, Inhoud FROM Trolleys_Ontwerpen WHERE ID = " . $designID . ";");

$design = $design->fetch_assoc();

?>

<title>Ontwerp bewerken - Schelsgebied</title>
<style>
    form td {
        width: 50% !important;
    }

    .covers {
        display: flex;
        flex-direction: row;
        align-items: stretch;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin: 5px;
        max-height: 60vh;
        overflow-y: scroll;
    }

    .cover {
        min-width: 150px;
        max-width: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
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
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .coverImage img {
        border-radius: 10px;
        margin: 0px;
    }

    .title {
        text-align: center;
        padding: 10px;
    }

    #editingTable {
        width: calc(100% - 20px);
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        margin: 10px;
        flex-wrap: wrap;
        gap: 10px;
    }

    #cartContent {
        min-width: 288px;
        border: 2.5px solid #333333;
        border-radius: 10px;
        box-sizing: border-box;
        background: #1a1a1a;
        width: 288px;
        height: 640px;
        margin: 0px auto;
    }

    #cartContent .cartRow {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border-bottom: 5px solid #333333;
        padding-bottom: 10px;
        margin: 10px 0px;
    }

    #cartContent .cartRow:last-child {
        border-bottom: none;
        padding-bottom: 0px;
        margin-bottom: 0px;
    }

    #cartContent .cartRow .cartPublication {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 45%;
        border: 2.5px solid #ffffff00;
        border-radius: 5px;
        transition: padding .25s, border-color .25s;
    }

    #cartContent .cartRow .cartPublication img {
        width: 100%;
        max-height: 164.42px;
        height: 164.42px;
        transition: width .25s, height .25s, transform .25s;
    }

    #cartContent .cartRow .cartPublication.selected {
        border-color: #05abf7;
        padding: 6.5px 0px;
    }

    #cartContent .cartRow .cartPublication.selected img {
        width: calc(100% - 10px);
        height: 151.56px;
    }

    #library {
        min-width: 300px;
        width: 60%;
        background: var(--color-content-background-primary);
        max-height: 60vh;
        align-self: flex-start;
        overflow-y: scroll;
        border: 2.5px solid var(--color-button-background);
        border-radius: 10px;
        overflow-x: hidden;
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    #library::-webkit-scrollbar,
    .publicationRow::-webkit-scrollbar,
    .covers::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    #library,
    .publicationRow,
    .covers {
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
        transition: transform .25s;
    }

    #library .publication img {
        border-radius: 2.5px;
    }

    @media only screen and (max-width: 830px) {
        #library {
            width: 45%;
        }
    }

    @media only screen and (max-width: 680px) {
        #library {
            width: 100%;
        }
    }
</style>
<div id="container">
    <a class="normal rounded full small" href="../"><img src="/images/icons/back.png">Terug</a>
    <h2 class="center"><img src="/images/icons/pen.png">Ontwerp
        <?php echo '"' . $design["Naam"] . '"'; ?>
        bewerken
    </h2>
    <details id="settingsFold">
        <summary><img src="/images/icons/settingsIcon.png">Instellingen</summary>
        <details>
            <summary><img src="/images/icons/passwordInput.png">Wijzig naam</summary>
            <form class="center" action="updateName.php" method="post">
                <p class="center">Nieuwe naam</p>
                <input type="text" name="name" value="" required>
                <input type="hidden" name="id" value="<?php echo $designID; ?>">
                <input type="submit" value="Wijzigen">
            </form>
        </details>
        <a class="normal rounded full medium" href="duplicateDesign.php?id=<?php echo $designID; ?>"><img
                src="/images/icons/duplicate.png">Dupliceer ontwerp</a>
        <a class="normal rounded full medium" onclick="deleteDesign()"><img src="/images/icons/trash.png">Verwijder
            ontwerp</a>
    </details>
    <details>
        <summary><img src="/images/icons/cover.png" id="coverFold">Cover</summary>
        <h3><img src="/images/icons/checkBox.png">Huidige cover</h3>
        <div class="covers">
            <?php
            $covers = mysqli_query($con, "SELECT Trolleys_Covers.Titel, Trolleys_Covers.Bestandsnaam FROM Trolleys_Ontwerpen INNER JOIN Trolleys_Covers ON Trolleys_Ontwerpen.CoverID = Trolleys_Covers.ID WHERE Trolleys_Ontwerpen.ID = " . $designID . ";");

            while ($cover = mysqli_fetch_assoc($covers)) {
                echo "<div class='cover' onclick='location.href=&quot;setCover.php?did=" . $designID . "&cid=" . $cover["ID"] . "&quot;'><div class='coverImage'><img src='/images/cartCovers/" . $cover["Bestandsnaam"] . "'></div>";
                echo "<div class='title'>" . $cover["Titel"] . "</div></div>";
            }
            ?>
        </div>
        <h3><img src="/images/icons/listIcon.png">Kies een nieuwe cover</h3>
        <div class="covers">
            <?php
            $covers = mysqli_query($con, "SELECT ID, Titel, Bestandsnaam FROM Trolleys_Covers;");

            while ($cover = mysqli_fetch_assoc($covers)) {
                echo "<div class='cover' onclick='location.href=&quot;setCover.php?did=" . $designID . "&cid=" . $cover["ID"] . "&quot;'><div class='coverImage'><img src='/images/cartCovers/" . $cover["Bestandsnaam"] . "'></div>";
                echo "<div class='title'>" . $cover["Titel"] . "</div></div>";
            }
            ?>
        </div>
        <?php
        if (in_array(51, $_SESSION["permissions"])) {
            echo "<hr><a class='normal rounded full small' href='../covers'><img src='/images/icons/cover.png'>Covers beheren</a>";
        }
        ?>
    </details>
    <div id="editingTable">
        <div id="cartContent">
            <?php

            $cartContent = json_decode($design["Inhoud"]);
            for ($i = 0; $i < count($cartContent); $i++) {
                echo "<div class='cartRow'>";
                for ($j = 0; $j < count($cartContent[$i]); $j++) {
                    $publication = mysqli_query($con, "SELECT AfbeeldingURL FROM Publicaties WHERE ID = " . $cartContent[$i][$j] . ";");
                    echo "<div class='cartPublication' onclick='selectPublication(this, [" . $i . ", " . $j . "])'><img src='" . mysqli_fetch_assoc($publication)['AfbeeldingURL'] . "'></div>";
                }
                echo "</div>";
            }

            ?>
        </div>
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
                    echo "<div class='publication' onclick='replacePublication(this, " . $publication["ID"] . ", \"" . $publication["AfbeeldingURL"] . "\")'>";
                    echo "<img src='" . $publication["AfbeeldingURL"] . "'></a>";
                    echo "</div>";
                }

                echo "</div>";

                if (in_array(50, $_SESSION["permissions"])) {
                    echo "<a class='normal rounded full small' href='../publications'><img src='/images/icons/settingsIcon.png'>Publicaties beheren</a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    //Define constants
    const designID = <?php echo $designID; ?>;
    const cartContent = <?php echo json_encode($cartContent); ?>;
    const settingsFold = document.getElementById("settingsFold");
    const coverFold = document.getElementById("coverFold");

    //Create a new XMLHttpRequest object
    const xmlhttp = new XMLHttpRequest();

    //Define variables
    var selectedElement = null;
    var selectedIndex = [-1, -1];

    function deleteDesign() {
        if (confirm("Weet je zeker dat je dit ontwerp wilt verwijderen?")) {
            xmlhttp.onload = function () {
                if (this.responseText == "inUse") {
                    alert("Dit ontwerp is momenteel in gebruik, en kan dus niet verwijderd worden.");
                } else if (this.responseText == "success") {
                    location.href = "../";
                } else {
                    alert("Er is iets misgegaan: " + this.responseText);
                }
            }

            xmlhttp.open("GET", "deleteDesign.php?id=" + designID);
            xmlhttp.send();
        }
    }

    function selectPublication(element, index) {
        const cartPublications = document.getElementsByClassName("cartPublication")

        let deselect = false;

        if (element.classList.contains("selected")) {
            selectedIndex = [-1, -1];
            selectedElement = null;
            deselect = true;
        }

        for (let i = 0; i < cartPublications.length; i++) {
            cartPublications[i].classList.remove("selected");
        }

        if (!deselect) {
            selectedElement = element;
            selectedIndex = index;
            element.classList.add("selected");
            settingsFold.removeAttribute("open");
            coverFold.removeAttribute("open");
            window.scrollTo({ top: 1000, behavior: 'smooth' });
        }
    }

    function replacePublication(element, id, url) {
        if (selectedElement != null) {
            cartContent[selectedIndex[0]][selectedIndex[1]] = id;
            setTimeout(function () {
                element.style.transform = "scale(0.9)";
                setTimeout(function () {
                    element.style.transform = "scale(1)";
                }, 250);
            }, 0);
            settingsFold.removeAttribute("open");
            coverFold.removeAttribute("open");
            window.scrollTo({ top: 350, behavior: 'smooth' });
            setTimeout(function () {
                selectedElement.firstChild.style.transform = "scaleY(0)";
                setTimeout(function () {
                    selectedElement.firstChild.src = url;
                    selectedElement.firstChild.style.transform = "scaleY(1)";
                }, 250);
            }, 0);
            saveChanges();
        }
    }

    function saveChanges() {
        xmlhttp.onload = function () {
            if (this.responseText != "success") {
                alert("Er is iets misgegaan: " + this.responseText);
                console.log(this.responseText);
            }
        }

        xmlhttp.open("GET", "updateDesign.php?id=" + designID + "&c=" + encodeURIComponent(JSON.stringify(cartContent)));
        xmlhttp.send();
    }
</script>

<?php

//Close database connection
$con->close();

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>