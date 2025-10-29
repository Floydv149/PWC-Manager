<?php

session_start();

if ($_SESSION["loggedIn"] != true) {
    header("Location: https://schelsgebied.rf.gd/account/inloggen");
} else if (!in_array(48, $_SESSION["permissions"])) {
    header("Location: ../");
}

//Include header
include("/home/schelsge/public_html/includes/header.php");

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

?>

<title>Ontwerpen - Schelsgebied</title>

<style>
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
</style>

<div id="container">
    <a class="normal rounded full small" href="../"><img src="/images/icons/back.png">Terug</a>
    <h2 class="center"><img src="/images/icons/rulerPen.png">ONTWERPEN</h2>
    <details>
        <summary><img src="/images/icons/add.png">Maak nieuw ontwerp</summary>
        <form class="center" action="addDesign.php" method="post">
            <p class="center">Naam</p>
            <input type="text" name="name" value="" required>
            <input type="submit" value="Aanmaken">
        </form>
    </details>
    <div id="designs">
        <?php
        $designs = mysqli_query($con, "SELECT Trolleys_Ontwerpen.ID, Trolleys_Ontwerpen.Naam, Trolleys_Ontwerpen.Inhoud, Trolleys_Covers.Bestandsnaam FROM Trolleys_Ontwerpen INNER JOIN Trolleys_Covers ON Trolleys_Ontwerpen.CoverID = Trolleys_Covers.ID ORDER BY Aanmaakdatum DESC, Trolleys_Ontwerpen.ID DESC;");

        while ($design = mysqli_fetch_assoc($designs)) {
            echo "<div class='design' onclick='location.href=&quot;editor?id=" . $design["ID"] . "&quot;'><div class='looks'><div class='cover'><img src='/images/cartCovers/" . $design["Bestandsnaam"] . "'></div><div class='cart'>";
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

        ?>
    </div>
    <?php
    if (in_array(50, $_SESSION["permissions"])) {
        echo "<a class='normal rounded full medium' href='publications'><img src='/images/icons/library.png'>Publicaties beheren</a>";
    }
    if (in_array(51, $_SESSION["permissions"])) {
        echo "<a class='normal rounded full medium' href='covers'><img src='/images/icons/cover.png'>Covers beheren</a>";
    }
    ?>
</div>
<script>
    //Define constants
    const cartContents = document.getElementById("cartContents");

    //Create a new XMLHttpRequest object
    const xmlhttp = new XMLHttpRequest();

    function getCartContent() {
        xmlhttp.onload = function () {
            cartContents.innerHTML = this.responseText;
        }

        xmlhttp.open("GET", "../contentRenderer/index.php?id=");
        xmlhttp.send();
    }
</script>

<?php

//Close database connection
$con->close();

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>