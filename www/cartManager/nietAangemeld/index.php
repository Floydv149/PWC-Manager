<?php

session_start();

if ($_SESSION["loggedIn"] == true) {
    if (isset($_GET["ID"])) {
        header("Location: ../details?id=" . $_GET["ID"]);
    } else {
        header("Location: ../");
    }
}

include ("/home/schelsge/public_html/includes/header.php");

?>

<title>Niet aangemeld - Schelsgebied</title>

<div id="container">
    <h2 class="center">Welkom bij trolley's!</h2>
    <p class="center">Gelieve u eerst aan te melden bij uw account.</p>
    <a class="normal full rounded small" href="/account">Aanmelden</a>
</div>

<?php include ("/home/schelsge/public_html/includes/footer.html"); ?>