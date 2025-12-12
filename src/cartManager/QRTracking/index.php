<?php

//Start session
session_start();

//Get cart data
if (isset($_GET["ID"])) {
    $cartID = $_GET["ID"];
} else {
    header("Location: ../");
}

if ($_SESSION["loggedIn"] != true) {
    header("Location: nietAangemeld?ID=" . $cartID);
    die();
} else if (!in_array("9", $_SESSION["permissions"])) {
    header("Location: geenToegang");
    die();
}

//Include header
include("/home/schelsge/public_html/includes/header.php");

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

$cartData = mysqli_query($con, "SELECT T.VerantwoordelijkeID, T.Voorraad, T.Plaats, T.BezitterID, TC.Bestandsnaam, T.StatusID FROM Trolleys AS T INNER JOIN Trolleys_Ontwerpen AS TD ON T.OntwerpID = TD.ID INNER JOIN Trolleys_Covers AS TC ON TD.CoverID = TC.ID WHERE T.ID = " . $cartID);

$cartData = mysqli_fetch_assoc($cartData);

?>

<title>QR-code opslaglocatie trolley <?php echo $cartID; ?> - Schelsgebied</title>
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

    #cartContents.realistic {
        animation: cartContentSlideUp .5s forwards;
    }

    .publicationSupplies {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;
        margin: 10px;
    }

    .publicationSupplies img {
        max-width: 100px;
        max-height: 128.66px !important;
        flex: 1;
        border-radius: 5px;
        margin: 0px;
    }

    .publicationSupplies .countControl {
        flex: 1;
        /* max-width: fit-content; */
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .publicationSupplies .countControl img {
        margin: 0px;
        display: inline;
        max-width: 25px;
    }

    .publicationSupplies .countControl p {
        padding: 0px 10px;
    }

    .publication h4 {
        /* max-width: 38vw; */
    }

    .split {
        display: flex;
        flex-direction: row;
        align-items: stretch;
        justify-content: space-evenly;
        flex-wrap: wrap;
    }

    .split .publication {
        max-width: 48%;
        min-width: 48%;
        transition: min-width .25s, max-width .25s;
        padding: 5px;
        box-sizing: border-box;
        border: 2.5px solid var(--color-button-hover-background);
        border-radius: 10px;
        margin: 5px;
    }

    form a {
        border: 2.5px solid transparent;
        transition: border .25s, background .25s;
    }

    a.selected {
        background: #25a100 !important;
        border: 2.5px solid white;
    }

    @media only screen and (max-width: 650px) {
        .split .publication {
            min-width: 100%;
            max-width: 100%;
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

    @media only screen and (max-width: 340px) {
        #cartContents {
            animation: cartContentSlideUp .5s forwards;
        }
    }
</style>
<style>
    header,
    footer {
        display: none;
    }
</style>
<div id="container">
    <?php
    if ($cartData["BezitterID"] != 0 && $cartData["BezitterID"] != $_SESSION["ID"]) {
        $updateOwner = mysqli_query($con, "UPDATE Trolleys SET BezitterID = 0 WHERE ID = " . $cartID);

        //Add activity
        $addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 3, 'Trolley ingeleverd door iemand anders, en terug op opslaglocatie');");
        ?>

        <h2 class="center"><img src="/images/icons/garage.png">Trolley inleveren voor iemand anders</h2>

        <?php

    } else {

        if ($cartData["BezitterID"] == 0) {
            $updateOwner = mysqli_query($con, "UPDATE Trolleys SET BezitterID = " . $_SESSION["ID"] . " WHERE ID = " . $cartID);

            //Add activity
            $addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 3, 'Trolley meegenomen');");
            ?>
            <h2 class="center"><img src="/images/icons/pogg.png">Trolley meenemen</h2>
            <h3 class="center">Trolley <?php echo $cartID; ?> - <?php echo $cartData["Plaats"]; ?></h3>
            <?php
        } else {
            $updateOwner = mysqli_query($con, "UPDATE Trolleys SET BezitterID = 0 WHERE ID = " . $cartID);

            //Add activity
            $addActivity = mysqli_query($con, "INSERT INTO Trolleys_Activiteit (TrolleyID, AccountID, Tijdstip, Type, Bericht) VALUES ($cartID, " . $_SESSION["ID"] . ", '" . date("Y-m-d H:i:s") . "', 3, 'Trolley ingeleverd, en terug op opslaglocatie');");
            ?>
            <h2 class="center"><img src="/images/icons/garage.png">Trolley inleveren</h2>
            <h3 class="center">Trolley <?php echo $cartID; ?> - <?php echo $cartData["Plaats"]; ?></h3>
            <?php
        }
    }
    ?>
    <div id="cartAppearance">
        <img src="/images/cartCovers/<?php echo $cartData["Bestandsnaam"]; ?>" id="cartCover">
        <div id="cartContents"></div>
    </div>
    <?php
    if ($cartData["BezitterID"] == 0) {
        ?>
        <h3 class="center"><img src="/images/icons/check.png">Geregistreerd als meegenomen.<br>Veel success!</h3>
        <a class="normal rounded full medium" onclick="location.reload();"><img src="/images/icons/inbox.png">Trolley laten
            staan of al terugbrengen</a>
        <?php

    } else {
        ?>
        <h3 class="center"><img src="/images/icons/check.png">Geregistreerd als ingeleverd.<br>Hartelijk bedankt!</h3>
        <details open>
            <summary class="center"><img src="/images/icons/eye.png">Trolley-controle</summary>
            <form class="center">
                <h3 class="center" id="voorraad"><img src="/images/icons/shelf.png">Is er iets veranderd aan de
                    voorraad?</h3>
                <?php
                if ($cartData["Voorraad"] == "") {
                    echo "<h4 class='center'>Nog geen voorraad ingegeven.</h4>";
                    if (in_array(49, $_SESSION["permissions"]) || $_SESSION["ID"] === $cartData["VerantwoordelijkeID"]) {
                        echo "<a class='normal rounded full small' onclick='createSupplies();'><img src='/images/icons/add.png'>Voorraad toevoegen</a>";
                    }
                } else {
                    echo "<div class='split'>";
                    $cartContent = (array) json_decode($cartData["Voorraad"]);
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
                        echo "<div class='countControl'><img src='/images/icons/remove.png' onclick='removeSupplies(this, &quot;" . $publicationID . "&quot;);'><p>" . $count . "</p><img src='/images/icons/add.png' onclick='addSupplies(this, &quot;" . $publicationID . "&quot;);'></div>";
                        echo "</div></div>";
                    }
                    echo "</div>";
                }
                ?>
                <hr>
                <h3><img src="/images/icons/clean.png">Zou de trolley eens schoongemaakt mogen worden?</h3>
                <div class="divide50">
                    <a class="normal rounded full small <?php
                    if ($cartData["StatusID"] > 1) {
                        echo "selected";
                    }
                    ?>" onclick="selectOption(this); updateStatus(2);" name="needsCleaning">Ja</a>
                </div>
                <div class="divide50">
                    <a class="normal rounded full small" onclick="selectOption(this); updateStatus(1);"
                        name="needsCleaning">Nee</a>
                </div>
                <br clear="left">
                <hr>
                <a class="normal rounded full medium" href="../details/?id=<?php echo $cartID; ?>#opmerkingen"><img
                        src="/images/icons/note.png"> Voeg een opmerking toe</a>
            </form>
        </details>
        <?php
    }
    ?>
    <a class="normal rounded full medium" href="../details/?id=<?php echo $cartID; ?>"><img
            src="/images/icons/close.png">Sluit</a>
</div>
<script>
    const cartContents = document.getElementById("cartContents");
    const cartID = <?php echo $cartID; ?>

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
        xhr.open("GET", "../details/addRemoveSupplies.php?cID=" + cartID + "&pID=" + publicationID + "&q=" + quantityChange);
        xhr.send();
    }

    function selectOption(element) {
        if (element.classList.contains("selected")) {
            element.classList.remove("selected");
        } else {
            elements = document.getElementsByName(element.name);

            for (let i = 0; i < elements.length; i++) {
                if (elements[i].classList.contains("selected")) {
                    elements[i].classList.remove("selected");
                }
            }
            element.classList.add("selected");
        }
    }

    function updateStatus(statusID) {
        let xhr = new XMLHttpRequest();
        xhr.onload = function () {
            if (this.responseText != "Success") {
                alert("Er is iets misgegaan tijdens het wijzigen van de trolley status. Meld het volgende aan de verantwoordelijke: " + this.responseText);
            }
        };
        xhr.open("GET", "../details/setStatus.php?cid=" + cartID + "&sid=" + statusID + "&r=0");
        xhr.send();
    }
</script>

<?php

//Close database connection
$con->close();

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>