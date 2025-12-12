<?php

//Start session
session_start();

if ($_SESSION["loggedIn"] != true) {
    // header("Location: https://schelsgebied.rf.gd/account/inloggen");
    header("Location: ../../nietAangemeld?ID=" . $_GET["id"]);
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

//Get number of rows
$numberOfRows = mysqli_query($con, "SELECT COUNT(ID) AS Aantal FROM Trolleys_Activiteit WHERE TrolleyID = " . $cartID . ";");
$numberOfRows = mysqli_fetch_assoc($numberOfRows);
$numberOfRows = $numberOfRows["Aantal"];

?>

<title>Activiteit trolley <?php echo $cartID; ?> - Schelsgebied</title>
<style>
    table img {
        width: 20px;
        margin-bottom: -3px;
        margin-right: 5px;
        margin-top: 0px;
    }

    .table {
        overflow-x: scroll;
    }

    table tr td:nth-child(2) {
        min-width: 150px;
        max-width: 150px;
    }

    table tr td:nth-child(3) {
        min-width: 175px;
        max-width: 175px;
    }

    table tr td:nth-child(4) {
        min-width: 350px;
    }
</style>
<div id="container">
    <a class="normal rounded small full" href="../?id=<?php echo $cartID; ?>"><img
            src="/images/icons/back.png">Terug</a>
    <h2 class="center"><img src="/images/icons/history.png">ACTIVITEIT TROLLEY <?php echo $cartID; ?></h2>
    <p class='center'>Hieronder vind je een overzicht van alle activiteit rond deze trolley.</p>
    <hr>
    <div id="activityContainer">
        <div id="activityList">
        </div>
        <br>
        <a class="center normal rounded medium" id="loadMoreButton" onclick="loadMore()"><img
                src="/images/icons/more.png">Meer laden</a>
    </div>
</div>
<script>
    let maxLoad = 50;
    const loadMoreButton = document.getElementById("loadMoreButton");
    const xmlhttpTableLoad = new XMLHttpRequest();

    function loadTable(reset) {
        if (reset) {
            maxLoad = 50;
        }

        xmlhttpTableLoad.onload = function () {
            if (reset) {
                document.getElementById("activityList").innerHTML = "";
            }
            document.getElementById("activityList").innerHTML += this.responseText;
            loadMoreButton.firstChild.src = "/images/icons/more.png";
            if (maxLoad >= <?php echo $numberOfRows ?>) {
                loadMoreButton.style.display = "none";
            }
        }
        xmlhttpTableLoad.open("GET", "getActivity.php?ID=" + <?php echo $cartID ?> + "&m=" + maxLoad);
        xmlhttpTableLoad.send();
    }

    loadTable(true);

    function loadMore() {
        loadMoreButton.firstChild.src = "/images/icons/loading.gif";
        maxLoad += 50;
        loadTable(false);
    }

</script>

<?php

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>