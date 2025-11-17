<?php

//Start session
session_start();

if ($_SESSION["loggedIn"] != true) {
    // header("Location: https://schelsgebied.rf.gd/account/inloggen");
    header("Location: ../nietAangemeld?ID=" . $_GET["id"]);
} else if (!in_array(9, $_SESSION["permissions"])) {
    header("Location: ../../");
}

if (!isset($_GET["id"])) {
    header("Location: ../");
} else {
    $cartID = $_GET["id"];
}

//Include header
include("/home/schelsge/public_html/includes/header.php");

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

$cart = mysqli_query($con, "SELECT Trolleys.ID, Trolleys.Plaats, Trolleys.OpslaglocatieID, Trolleys_Opslaglocaties.Naam AS Opslaglocatie, Trolleys.Opmerkingen, Trolleys_Covers.Bestandsnaam, Trolleys.OntwerpID, Trolleys_Ontwerpen.Naam AS OntwerpNaam, Trolleys_Status.Afbeelding AS StatusAfbeelding, Trolleys_Status.Titel AS Status, Trolleys.VerantwoordelijkeID, Trolleys.LaatsteOnderhoud, Accounts.Voornaam AS VerantwoordelijkeVoornaam, Accounts.Achternaam AS VerantwoordelijkeAchternaam, Trolleys.BezitterID, A2.Voornaam AS BezitterVoornaam, A2.Achternaam AS BezitterAchternaam, Trolleys.Voorraad FROM Trolleys INNER JOIN Trolleys_Ontwerpen ON Trolleys.OntwerpID = Trolleys_Ontwerpen.ID INNER JOIN Trolleys_Covers ON Trolleys_Ontwerpen.CoverID = Trolleys_Covers.ID INNER JOIN Trolleys_Status ON Trolleys.StatusID = Trolleys_Status.ID INNER JOIN Accounts ON Trolleys.VerantwoordelijkeID = Accounts.ID INNER JOIN Trolleys_Opslaglocaties ON Trolleys.OpslaglocatieID = Trolleys_Opslaglocaties.ID INNER JOIN Accounts A2 ON Trolleys.BezitterID = A2.ID WHERE Trolleys.ID = " . $_GET['id']);

$cart = mysqli_fetch_assoc($cart);

$numberOfCarts = mysqli_query($con, "SELECT COUNT(ID) AS Count FROM Trolleys;");
$numberOfCarts = mysqli_fetch_assoc($numberOfCarts);
$numberOfCarts = $numberOfCarts["Count"];

?>

<title>Trolley beheren - Schelsgebied</title>

<style>
    #cartAppearance {
        display: flex;
        flex-direction: row;
        align-items: stretch;
        justify-content: center;
        flex-wrap: wrap;
        width: 100%;
        gap: 10px;
    }

    #cartCover {
        border-radius: 10px;
        width: 144px;
        height: 320px;
        margin: 0px;
    }

    #cartContents {
        border: 2.5px solid #333333;
        border-radius: 10px;
        box-sizing: border-box;
        background: #1a1a1a;
        transition: margin .5s;
        width: 144px;
        height: 320px;
    }

    #cartContents .cartRow {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border-bottom: 5px solid #333333;
    }

    #cartContents .cartRow:last-child {
        border-bottom: none;
    }

    #cartContents .cartRow img {
        width: 46%;
        max-height: 82.84px;
    }

    #cartDetails {}

    #modals {
        position: relative;
        z-index: 10;
    }

    .interactionProtection {
        position: fixed;
        top: 0;
        left: 0;
        width: 200vw;
        height: 200vh;
        background: rgba(0, 0, 0, 0);
        transition: background-color .5s;
    }

    .modal {
        position: fixed;
        top: 5vh;
        width: 90%;
        max-width: 740px;
        overflow-y: scroll;
        max-height: 85vh;
        background: var(--color-body-background);
        border: 2.5px solid var(--color-button-background);
        border-radius: 10px;
        left: 5%;
        padding: 10px;
        transform: scale(0);
        transition: transform .25s;
        box-sizing: border-box;
    }

    .modal textarea {
        width: 80%;
        min-height: 100px;
        resize: vertical;
    }

    #designs {
        margin: 10px 0px;
        display: flex;
        flex-direction: row;
        align-items: stretch;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .design {
        width: min-content;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        background: var(--color-content-background-primary);
        border-radius: 10px;
        border: 2.5px solid var(--color-button-hover-background);
        transition: border-color .25s, background .25s;
    }

    .design:hover {
        background: var(--color-content-background-secondary);
        border-color: var(--color-button-background);
    }

    .looks {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .cover {
        justify-self: left;
        width: 96px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cover img {
        border-radius: 10px;
        margin: 0px;
    }

    .cart {
        border: 2.5px solid #333333;
        border-radius: 10px;
        box-sizing: border-box;
        background: #1a1a1a;
        width: 96px;
        height: 213.333px;
    }

    .cart .cartRow {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border-bottom: 5px solid #333333;
    }

    .cart .cartRow:last-child {
        border-bottom: none;
    }

    .cart .cartRow img {
        width: 40%;
        max-height: 47.34px;
    }

    .title {
        text-align: center;
        padding: 10px;
    }

    #editButtons {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        margin: 5px;
        flex-wrap: wrap;
    }

    #editButtons a {
        width: fit-content;
    }

    #widgets {
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: space-evenly;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .widget {
        min-width: 200px;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        justify-content: flex-start;
        background: var(--color-content-background-primary);
        border-radius: 10px;
        border: 2.5px solid var(--color-button-hover-background);
        padding: 5px;
    }

    .widget.full {
        width: 100%;
    }

    .widget h3 {
        border-radius: 10px;
        margin: 5px;
    }

    .widget h4 {
        border-radius: 10px;
        margin: 5px;
        background: var(--color-header-button-hover);
        border: 2.5px solid var(--color-button-hover-background);
    }

    .widget>img {
        width: 75px;
        padding: 5px;
        border-radius: 10px;
        border: 2.5px solid var(--color-button-hover-background);
    }

    .widget p {
        text-align: center;
        margin: 2.5px;
        padding: 2.5px;
    }

    #statusList {
        margin: 10px 0px;
        display: flex;
        flex-direction: row;
        align-items: stretch;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .status {
        width: fit-content;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--color-content-background-primary);
        border-radius: 10px;
        border: 2.5px solid var(--color-button-hover-background);
        transition: border-color .25s, background .25s;
    }

    .status:hover {
        background: var(--color-content-background-secondary);
        border-color: var(--color-button-background);
    }

    .status img {
        width: 50px;
    }

    .status .title {
        text-align: center;
    }

    #cartContents.realistic {
        animation: cartContentSlideUp .5s forwards;
    }

    #cartAppearance:has(.realistic) {
        width: 200px;
        margin: 0px auto;
    }

    #realisticViewToggle {
        transition: opacity .25s;
    }

    .red {
        color: #ff5f57;
        filter: drop-shadow(0px .5px 1px #e1473f);
    }

    .widget .publicationSupplies {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;
        margin: 10px;
    }

    .widget .publicationSupplies img {
        max-width: 100px;
        max-height: 128.66px !important;
        flex: 1;
        border-radius: 5px;
        margin: 0px;
    }

    .widget .publicationSupplies .countControl {
        flex: 1;
        /* max-width: fit-content; */
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .widget .publicationSupplies .countControl img {
        margin: 0px;
        display: inline;
        max-width: 25px;
    }

    .widget .publicationSupplies .countControl p {
        padding: 0px 10px;
    }

    @media only screen and (min-width: 830px) {
        .modal {
            left: calc((100% - 740px) / 2);
        }
    }

    .widget .publication h4 {
        /* max-width: 38vw; */
    }

    .widget .split {
        display: flex;
        flex-direction: row;
        align-items: stretch;
        justify-content: space-evenly;
        flex-wrap: wrap;
    }

    .widget .split .publication {
        max-width: 48%;
        min-width: 48%;
        transition: min-width .25s, max-width .25s;
        padding: 5px;
        box-sizing: border-box;
        border: 2.5px solid var(--color-button-hover-background);
        border-radius: 10px;
        margin: 5px;
    }

    @media only screen and (max-width: 650px) {
        .widget .split .publication {
            min-width: 100%;
            max-width: 100%;
        }
    }

    @media only screen and (max-width: 340px) {
        #cartContents {
            animation: cartContentSlideUp .5s forwards;
        }

        #realisticViewToggle {
            opacity: 0;
        }
    }

    @media only screen and (max-width: 490px) {
        .widget {
            width: 100%;
        }
    }

    @keyframes cartContentSlideUp {
        0% {
            margin-top: 0px;
        }

        100% {
            margin-top: -100px;
        }
    }
</style>

<div id="container">
    <a class="normal rounded full small" href="../"><img src="/images/icons/back.png">Terug</a>
    <hr>
    <div class="divide50">
        <a id="next" class="normal rounded full small" <?php
        if ($cartID <= 1) {
            echo "style='opacity: 0'";
        } else {
            echo "href='?id=" . ($cartID - 1) . "'";
        } ?>><img src="/images/icons/previous.png">Vorige</a>
    </div>
    <div class="divide50">
        <a id="next" class="normal rounded full small" <?php
        if ($cartID >= $numberOfCarts) {
            echo "style='opacity: 0'";
        } else {
            echo "href='?id=" . ($cartID + 1) . "'";
        } ?>><img src="/images/icons/next.png">Volgende</a>
    </div>
    <br clear="left">
    <h2 class="center"><img src="/images/icons/cart.png"><?php echo $cart["ID"] . ". "; ?>TROLLEY
        <?php echo strtoupper($cart["Plaats"]); ?>
    </h2>
    <div id="cartAppearance">
        <img onclick="fullScreen(this)" src="/images/cartCovers/<?php echo $cart["Bestandsnaam"]; ?>" id="cartCover">
        <div id="cartContents"></div>
    </div>
    <div id="cartDetails">
    </div>
    <div id="editButtons">
        <?php

        ini_set('display_errors', 1);
        if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] == $cart["VerantwoordelijkeID"]) {
            echo "<a class='normal rounded full medium' onclick='openDesignChooser()'><img src='/images/icons/listIcon.png'>Kies ontwerp</a>";
        }
        if (in_array(48, $_SESSION["permissions"])) {
            echo "<a href='../designs/editor?id=" . $cart["OntwerpID"] . "' class='normal rounded full medium'><img src='/images/icons/rulerPen.png'>Wijzig ontwerp</a>";
        }
        if (in_array(49, $_SESSION["permissions"])) {
            echo "<a class='normal rounded full medium' onclick='changeCartLocation()'><img src='/images/icons/passwordInput.png'>Wijzig trolley-plaats</a>";
        }
        ?>
        <a class="normal rounded full medium" onclick="toggleRealisticView()" id='realisticViewToggle'><img
                src="/images/icons/eye.png">Toon
            werkelijke weergave</a>
    </div>
    <div id="widgets">
        <div class="widget full">
            <h3 class="center" id="huidigeLocatie"><img src="/images/icons/mapIcon.png">Huidige locatie</h3>
            <?php

            $meeting = mysqli_query($con, "SELECT VBA.Organisator, VBA.Begintijd, VBA.Eindtijd FROM Velddienstbijeenkomsten_Afspraken AS VBA RIGHT JOIN Velddienstbijeenkomsten_DienstTakken AS VBDT ON VBA.ID = VBDT.AfspraakID WHERE VBDT.TakNr = 6 AND VBA.Datum = CURDATE() AND '" . date("H:i:s") . "' >= VBA.Begintijd AND '" . date("H:i:s") . "' <= VBA.Eindtijd AND VBA.Locatie = '" . $cart["Plaats"] . "' ORDER BY VBA.Begintijd ASC");
            if ($meeting->num_rows > 0) {
                $meeting = mysqli_fetch_assoc($meeting);
                echo "<p>" . $cart["Plaats"] . " van " . date("H:i", strtotime($meeting["Begintijd"])) . " - " . date("H:i", strtotime($meeting["Eindtijd"])) . "</p>";
            } else {
                echo "<p>Momenteel niet in gebruik</p>";
            }

            if ($cart["BezitterID"]) {
                echo "<p>In bezit van " . $cart["BezitterVoornaam"] . " " . $cart["BezitterAchternaam"] . "</p>";
            } else {
                echo "<p>" . $cart["Opslaglocatie"] . "</p><a class='normal rounded full small' href='claimCart.php?ID=" . $cart["ID"] . "'><img src='/images/icons/person.png'>Melden dat ik deze trolley heb</a>";
            }
            if ($_SESSION["ID"] == $cart["VerantwoordelijkeID"] || in_array(49, $_SESSION["permissions"])) {
                echo "<a class='normal rounded full small' onclick='changeCartCurrentLocation()'><img src='/images/icons/question.png'>Klopt dit niet?</a>";
            }
            ?>
        </div>
        <div class="widget">
            <h3 class="center"><img src="/images/icons/info.png">Algemene informatie</h3>
            <h4 class="center" id="opslaglocatie" <?php if (in_array(49, $_SESSION["permissions"])) {
                echo "onclick='changeStorageLocation();'";
            } ?>><img src="/images/icons/garage.png">Opslaglocatie
                <?php
                if (in_array(49, $_SESSION["permissions"])) {
                    echo "<img src='/images/icons/pen.png'>";
                }
                ?>
                <br><br>
                <a class="normal rounded full small" target="_blank"
                    href="https://www.google.com/maps/place/<?php echo $cart["Opslaglocatie"]; ?>"><img
                        src="/images/icons/googleMaps.png">
                    <?php echo $cart["Opslaglocatie"]; ?>
                </a>
            </h4>
            <h4 class="center" id="verantwoordelijke" <?php if (in_array(49, $_SESSION["permissions"])) {
                echo "onclick='changeResponsible();'";
            } ?>><img src="/images/icons/person.png">Verantwoordelijke:
                <?php echo $cart["VerantwoordelijkeVoornaam"] . " " . $cart["VerantwoordelijkeAchternaam"]; ?>
                <?php
                if (in_array(49, $_SESSION["permissions"])) {
                    echo "<img src='/images/icons/pen.png'>";
                }
                ?>
            </h4>
            <h4 class="center"><img src="/images/icons/rulerPen.png">Ontwerp:
                <?php echo $cart["OntwerpNaam"]; ?>
            </h4>
            <h4 class="center"><img src="/images/icons/hashtag.png">Nummer
                <?php echo $cart["ID"]; ?>
            </h4>
        </div>
        <div class="widget">
            <h3 class="center" id="status"><img src="/images/icons/status.png">Status</h3>
            <img src="/images/icons/<?php echo $cart["StatusAfbeelding"]; ?>">
            <h4 class="center" <?php if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] === $cart["VerantwoordelijkeID"]) {
                echo "onclick='changeCartStatus()';";
            } ?>>
                <?php echo $cart["Status"];
                if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] === $cart["VerantwoordelijkeID"]) {
                    echo " <img src='/images/icons/pen.png'>";
                }
                ?>
            </h4>
            <h4 class="center" id="laatsteOnderhoud" <?php if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] === $cart["VerantwoordelijkeID"]) {
                echo "onclick='changeLastMaintenance()';";
            } ?>>
                <img src="/images/icons/clean.png">Laatste onderhoud:<br>
                <?php if ($cart["LaatsteOnderhoud"] == "0000-00-00") {
                    echo "<span class='red'>Geen datum bekend</span>";
                } else {
                    //If longer that a month ago
                    if (time() - strtotime($cart["LaatsteOnderhoud"]) > 2592000) {
                        echo "<span class='red'>" . date("d-m-Y", strtotime($cart["LaatsteOnderhoud"])) . "</span>";
                    } else {
                        echo date("d-m-Y", strtotime($cart["LaatsteOnderhoud"]));
                    }
                }
                if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] === $cart["VerantwoordelijkeID"]) {
                    echo " <img src='/images/icons/pen.png'>";
                }
                ?>
            </h4>
        </div>
        <div class="widget" <?php
        $opmerkingen = isset($cart["Opmerkingen"]) ? $cart["Opmerkingen"] : ""; // Get the value of $cart["Opmerkingen"], or an empty string if it's not set
        $escaped_opmerkingen = htmlspecialchars($opmerkingen, ENT_QUOTES); // Escape special characters including quotes
        echo "onclick='editComments(this);'";
        ?>>
            <h3 class="center" id="opmerkingen"><img src="/images/icons/note.png">Opmerkingen</h3>
            <p>
                <?php
                if ($cart["Opmerkingen"] != "") {
                    echo $cart["Opmerkingen"];
                } else {
                    echo "Geen opmerkingen";
                }
                ?>
            </p>
            <p><img src='/images/icons/pen.png'></p>
        </div>
        <div class="widget full">
            <h3 class="center" id="voorraad"><img src="/images/icons/shelf.png">Voorraad</h3>
            <?php
            if ($cart["Voorraad"] == "") {
                echo "<h4 class='center'>Nog geen voorraad ingegeven.</h4>";
                if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] === $cart["VerantwoordelijkeID"]) {
                    echo "<a class='normal rounded full small' onclick='createSupplies();'><img src='/images/icons/add.png'>Voorraad toevoegen</a>";
                }
            } else {
                echo "<div class='split'>";
                $cartContent = (array) json_decode($cart["Voorraad"]);
                foreach ($cartContent as $publicationID => $count) {
                    $publication = [];
                    if ($publicationID == "RJ") {
                        $publication["AfbeeldingURL"] = "https://cms-imgp.jw-cdn.org/img/p/rj/O/pt/rj_O_lg.jpg";
                        $publication["Titel"] = "Kom terug bij Jehovah";
                    } else if ($publicationID == "OL") {
                        $publication["AfbeeldingURL"] = "/images/icons/unknownLanguage.png";
                        $publication["Titel"] = "Andere talen";
                    } else {
                        $publication = mysqli_query($con, "SELECT AfbeeldingURL, Titel FROM Publicaties WHERE ID = " . $publicationID . ";");
                        $publication = mysqli_fetch_assoc($publication);
                    }
                    echo "<div class='publication'><h4 class='center'>" . $publication["Titel"] . " </h4>";
                    echo "<div class='publicationSupplies'>";
                    echo '<img onclick="fullScreen(this)" src="' . $publication["AfbeeldingURL"] . '">';
                    if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] === $cart["VerantwoordelijkeID"]) {
                        echo "<div class='countControl'><img src='/images/icons/remove.png' onclick='removeSupplies(this, &quot;" . $publicationID . "&quot;);'><p>" . $count . "</p><img src='/images/icons/add.png' onclick='addSupplies(this, &quot;" . $publicationID . "&quot;);'></div>";
                    } else {
                        echo "<div class='countControl'><p>" . $count . " stuk";

                        if ($count > 1 || $count == 0) {
                            echo "s";
                        }

                        echo "</p></div>";
                    }
                    echo "</div></div>";
                }
                echo "</div>";
                if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] === $cart["VerantwoordelijkeID"]) {
                    echo "<a class='normal rounded full small' onclick='createSupplies();'><img src='/images/icons/trash.png'>Voorraad resetten</a>";
                }
            }
            ?>
        </div>
        <div class="widget full">
            <h3 class="center"><img src="/images/icons/history.png">Activiteit</h3>
            <a class="normal rounded fullm medium" href="activiteit?id=<?php echo $cartID; ?>"><img
                    src="/images/icons/eye.png">Bekijk activiteit</a>
        </div>
    </div>
    <div id="modals"></div>
</div>
<script>
    //Define constants
    const cartID = <?php echo $cartID; ?>;
    const cartContents = document.getElementById("cartContents");
    const modals = document.getElementById("modals");
    const peopleList =
        <?php

        ini_set("display_errors", 1);

        $people = mysqli_query($con, "SELECT ID, Voornaam, Achternaam FROM Accounts WHERE ID != 0 AND Machtigingen LIKE '%9%' ORDER BY Voornaam ASC, Achternaam ASC;");

        $peopleList = "";

        while ($person = mysqli_fetch_assoc($people)) {
            $peopleList .= "<option value='" . $person["ID"] . "'>" . $person["Voornaam"] . " " . $person["Achternaam"] . "</option>";
        }

        echo '"' . $peopleList . '"';

        ?>;
    const realisticViewToggle = document.getElementById("realisticViewToggle");

    //Define variables
    let isRealisticView = false;

    //Create a new XMLHttpRequest object
    const xmlhttp = new XMLHttpRequest();

    function getCartContent() {
        xmlhttp.onload = function () {
            cartContents.innerHTML = this.responseText;
        }

        xmlhttp.open("GET", "../contentRenderer/index.php?id=" + <?php echo $cartID; ?>);
        xmlhttp.send();
    }

    getCartContent();

    function openModal(content) {
        interactionProtection = document.createElement("div");
        interactionProtection.className = "interactionProtection";
        modals.appendChild(interactionProtection);

        modal = document.createElement("div");
        modal.innerHTML = content;
        modal.className = "modal";
        interactionProtection.appendChild(modal);

        setTimeout(function () {
            interactionProtection.style.background = "rgba(0, 0, 0, 0.7)";
            modal.style.transform = "scale(1)";
        }, 1);
    }

    function closeModal(element) {
        element.style.transform = "scale(0)";
        element.parentElement.style.background = "rgba(0, 0, 0, 0)";
        setTimeout(function () {
            element.parentElement.remove();
        }, 1000);
    }

    async function openDesignChooser() {
        let content = "<h2 class='center'><img src='/images/icons/listIcon.png'>Kies het ontwerp</h2><p class='center'>Kies hieronder één van de beschikbare ontwerpen om toe te passen op deze trolley.</p><hr><a class='normal rounded full medium' onclick='closeModal(this.parentElement)'><img src='/images/icons/close.png'>Annuleer</a><hr>";
        let designList = await makeRequest("GET", "getDesignsList.php");
        content += designList;

        // content += "";

        setTimeout(() => {
            openModal(content);
        }, 250);
    }

    async function changeCartStatus() {
        let content = "<h2 class='center'><img src='/images/icons/status.png'>Kies een ander status</h2><p class='center'>Kies een passend status voor deze trolley, waaraan anderen kunnen zien hoe het staat met deze trolley.</p><hr>";
        let statusList = await makeRequest("GET", "getStatusList.php");
        content += statusList;

        content += "<a class='normal rounded full medium' onclick='closeModal(this.parentElement)'><img src='/images/icons/close.png'>Annuleer</a>";

        setTimeout(() => {
            openModal(content);
        }, 100);
    }

    function changeLastMaintenance() {
        openModal("<h2 class='center'><img src='/images/icons/clean.png'>Wijzig laatste onderhoudsdatum</h2><form class='center' method='post' action='changeLastMaintenance.php'><input type='hidden' name='cartID' value='" + cartID + "'><p>Nieuwe laatste onderhoudsdatum</p><input type='date' name='newDate'><br><br><a class='normal rounded full medium' onclick='setTimeout(() => { submitForm(this.parentElement); }, 500); closeModal(this.parentElement.parentElement);'><img src='/images/icons/save.png'>Wijzigen</a></form><a class='normal rounded full medium' onclick='closeModal(this.parentElement)'><img src='/images/icons/close.png'>Annuleer</a>");
    }

    function makeRequest(method, url) {
        return new Promise(function (resolve, reject) {
            let xhr = new XMLHttpRequest();
            xhr.open(method, url);
            xhr.onload = function () {
                if (this.status >= 200 && this.status < 300) {
                    resolve(xhr.response);
                } else {
                    reject({
                        status: this.status,
                        statusText: xhr.statusText
                    });
                }
            };
            xhr.onerror = function () {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            };
            xhr.send();
        });
    }

    function setDesign(designID) {
        location.href = "setDesign.php?cid=" + cartID + "&did=" + designID;
    }

    function changeCartLocation() {
        openModal("<h2 class='center'><img src='/images/icons/passwordInput.png'>Wijzig trolley-plaats</h2><p class='center'>Geef de locatie op waar deze trolley bij gebruik moet staan.</p><hr><form class='center' method='post' action='changeCartLocation.php'><input type='hidden' name='cartID' value='" + cartID + "'><p>Nieuwe locatie</p><input type='text' name='newLocation'><br><br><a class='normal rounded full medium' onclick='setTimeout(() => { submitForm(this.parentElement); }, 500); closeModal(this.parentElement.parentElement);'><img src='/images/icons/save.png'>Wijzigen</a></form><a class='normal rounded full medium' onclick='closeModal(this.parentElement)'><img src='/images/icons/close.png'>Annuleer</a>");
    }

    function changeCartCurrentLocation() {
        modalContent = "<h2 class='center'><img src='/images/icons/mapIcon.png'>Wijzig huidige locatie</h2><form class='center' method='post' action='changeCurrentLocation.php'><input type='hidden' name='cartID' value='" + cartID + "'><p>Kies nieuwe huidige locatie</p><select name='newLocation'><?php

        $poggers = mysqli_query($con, "SELECT ID, Voornaam, Achternaam FROM Accounts WHERE Machtigingen LIKE '%9%' ORDER BY Voornaam ASC, Achternaam ASC;");

        echo "<option value='" . $cart["BezitterID"] . "'>Kies hier een nieuwe huidige locatie</option>";

        echo "<option value='0'>Opslaglocatie: " . $cart["Opslaglocatie"] . "</option>";

        while ($row = mysqli_fetch_assoc($poggers)) {
            echo "<option value='" . $row["ID"] . "'>" . $row["Voornaam"] . " " . $row["Achternaam"] . "</option>;";
        }

        ?>";

        modalContent += "</select><br><br><a class='normal rounded full medium' onclick='setTimeout(() => { submitForm(this.parentElement); }, 500); closeModal(this.parentElement.parentElement);'><img src='/images/icons/save.png'>Wijzigen</a></form><a class='normal rounded full medium' onclick='closeModal(this.parentElement)'><img src='/images/icons/close.png'>Annuleer</a>";
        openModal(modalContent);
    }

    function changeStorageLocation() {
        modalContent = "<h2 class='center'><img src='/images/icons/garage.png'>Wijzig opslaglocatie</h2><form class='center' method='post' action='changeStorageLocation.php'><input type='hidden' name='cartID' value='" + cartID + "'><p>Kies nieuwe opslaglocatie</p><select name='newLocation'><?php

        $storageLocations = mysqli_query($con, "SELECT ID, Naam FROM Trolleys_Opslaglocaties WHERE NOT ID = 0 ORDER BY ID ASC");

        echo "<option value='" . $cart["OpslaglocatieID"] . "'>Kies hier een nieuwe opslaglocatie</option>";

        while ($row = mysqli_fetch_array($storageLocations)) {
            echo "<option value='" . $row["ID"] . "'>" . $row["Naam"] . "</option>;";
        }

        ?>";

        modalContent += "</select><br><br><a class='normal rounded full medium' onclick='setTimeout(() => { submitForm(this.parentElement); }, 500); closeModal(this.parentElement.parentElement);'><img src='/images/icons/save.png'>Wijzigen</a></form><a class='normal rounded full medium' onclick='closeModal(this.parentElement)'><img src='/images/icons/close.png'>Annuleer</a>";
        openModal(modalContent);
    }

    function changeResponsible() {
        openModal("<h2 class='center'><img src='/images/icons/person.png'>Wijzig verantwoordelijke</h2><form class='center' method='post' action='changeResponsible.php'><input type='hidden' name='cartID' value='" + cartID + "'><p>Nieuwe verantwoordelijke</p><select name='newResponsible'><option value='0'>Kies nieuwe verantwoordelijke</option>" + peopleList + "</select><br><br><a class='normal rounded full medium' onclick='setTimeout(() => { submitForm(this.parentElement); }, 500); closeModal(this.parentElement.parentElement);'><img src='/images/icons/save.png'>Wijzigen</a></form><a class='normal rounded full medium' onclick='closeModal(this.parentElement)'><img src='/images/icons/close.png'>Annuleer</a>");
    }

    function setStatus(id) {
        location.href = "setStatus.php?cid=" + cartID + "&sid=" + id;
    }

    function editComments(element) {
        let content = "<h2 class='center'><img src='/images/icons/note.png'>";
        comments = element.firstElementChild.nextElementSibling.innerHTML.trim();

        if (comments == "Geen opmerkingen") {
            comments = "";
        }

        if (comments != "") {
            content += "Wijzig opmerkingen";
        } else {
            content += "Voeg opmerkingen toe";
        }

        content += "</h2><p class='center'>Meld hier problemen of ontbrekende onderdelen verbonden aan deze trolley.</p><hr><form class='center' method='post' action='editComments.php'><input type='hidden' name='cartID' value='" + cartID + "'><p>";

        if (comments != "") {
            content += "Wijzig opmerking";
        } else {
            content += "Nieuwe opmerking";
        }

        content += "</p><textarea name='updatedComment'>" + comments.replace(/<br\s*\/?>/gi, '\n') + "</textarea><br><br><a class='normal rounded full medium' onclick='setTimeout(() => { submitForm(this.parentElement); }, 500); closeModal(this.parentElement.parentElement);'><img src='/images/icons/save.png'>Wijzigen</a></form><a class='normal rounded full medium' onclick='closeModal(this.parentElement)'><img src='/images/icons/close.png'>Annuleer</a>";

        openModal(content);
    }

    function submitForm(form) {
        form.submit();
    }

    if (window.location.hash) {
        // Get the element with the ID corresponding to the hashtag
        var targetElement = document.querySelector(window.location.hash);
        if (targetElement) {
            // Scroll to the target element
            targetElement.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function toggleRealisticView() {
        isRealisticView = !isRealisticView;

        console.log(isRealisticView);

        if (isRealisticView) {
            cartContents.classList.add("realistic");
            realisticViewToggle.innerHTML = "<img src='/images/icons/eyeClosed.png'>Verberg werkelijke weergave";
        } else {
            cartContents.classList.remove("realistic");
            realisticViewToggle.innerHTML = "<img src='/images/icons/eye.png'>Toon werkelijke weergave";
        }
    }

    function createSupplies() {
        location.href = "createSupplies.php?id=" + cartID;
    }

    function addSupplies(element, publicationID) {

        numberElement = element.parentElement.firstChild.nextSibling
        numberElement.innerHTML = parseInt(numberElement.innerHTML) + 1;

        updateSupplies(publicationID, 1);
    }

    function removeSupplies(element, publicationID) {

        numberElement = element.parentElement.firstChild.nextSibling

        if (parseInt(numberElement.innerHTML) > 0) {
            numberElement.innerHTML = parseInt(numberElement.innerHTML) - 1;
            updateSupplies(publicationID, -1);
        }
    }

    function updateSupplies(publicationID, quantityChange) {
        let xhr = new XMLHttpRequest();
        xhr.onload = function () {
            if (this.responseText != "Success") {
                alert("Er is iets misgegaan tijdens het wijzigen van de voorraad. Meld het volgende aan de verantwoordelijke: " + this.responseText);
            }
        };
        xhr.open("GET", "addRemoveSupplies.php?cID=" + cartID + "&pID=" + publicationID + "&q=" + quantityChange);
        xhr.send();
    }
</script>

<?php

//Close database connection
$con->close();

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>