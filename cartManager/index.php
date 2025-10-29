<?php

session_start();

if ($_SESSION["loggedIn"] != true) {
    header("Location: nietAangemeld");
} else if (!in_array(9, $_SESSION["permissions"])) {
    header("Location: ../");
}

//Apply custom favicons
$customFavicons = "trolleys";

//Include header
include("/home/schelsge/public_html/includes/header.php");

//Include database connection
include("/home/schelsge/public_html/includes/databaseConnection.php");

?>

<title>Trolley's - Schelsgebied</title>

<style>
    #cartsContainer {
        display: flex;
        flex-direction: row;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cartBox {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: top;
        margin: 10px;
        width: 200px;
        border: 2.5px solid black;
        border-radius: 10px;
        background: var(--color-content-background-secondary);
    }

    .cartBox:hover .cartHeader {
        text-decoration: underline;
    }

    .cartHeader {
        width: 100%;
        font-size: 20px;
        padding: 10px 0px;
        text-align: center;
        font-weight: bold;
    }

    .cartPicture {
        min-width: 144px;
        min-height: 320px;
        border-radius: 10px;
        margin: 0px;
    }

    .cartName {
        text-align: center;
        padding: 10px 0px;
    }

    @media only screen and (max-width: 500px) {
        .cartBox {
            width: 150px !important;
        }

        .cartPicture {
            min-width: 108px;
            min-height: 240px;
        }
    }
</style>

<div id="container">
    <a class="normal rounded full small" href="../"><img src="/images/icons/back.png">Terug</a>
    <h2 class="center"><img src="/images/icons/cart.png">TROLLEY'S</h2>
    <p class="center">Kies hieronder een trolley om er meer informatie over te bekijken.</p>
    <hr>
    <div id="cartsContainer">
        <?php
        $carts = mysqli_query($con, "SELECT Trolleys.ID, Trolleys.Plaats, Trolleys_Covers.Bestandsnaam FROM Trolleys INNER JOIN Trolleys_Ontwerpen ON Trolleys.OntwerpID = Trolleys_Ontwerpen.ID INNER JOIN Trolleys_Covers ON Trolleys_Ontwerpen.CoverID = Trolleys_Covers.ID ORDER BY Trolleys.ID ASC");
        if ($carts->num_rows == 0) {
            echo "<p class='center'>Er zijn momenteel nog geen trolley's.</p>";
        } else {
            while ($cart = mysqli_fetch_array($carts)) {
                echo "<div class='cartBox' onclick='location.href=&quot;details?id=" . $cart["ID"] . "&quot;;'><img class='cartPicture' src='/images/cartCovers/" . $cart["Bestandsnaam"] . "'><div class='cartHeader'>Trolley " . $cart["ID"] . "</div><div class='cartName'>" . $cart["Plaats"] . "</div></div>";
            }
        }
        ?>
    </div>
    <?php
    if (in_array(48, $_SESSION["permissions"])) {
        echo "<hr><a href='designs' class='normal rounded full medium'><img src='/images/icons/rulerPen.png'>Beheer
            ontwerpen</a>";
    }
    ?>
</div>
<script>

</script>

<?php

//Close database connection
$con->close();

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>