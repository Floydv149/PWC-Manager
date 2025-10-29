<?php

//Start session
session_start();

if ($_SESSION["loggedIn"] == true) {
    if (isset($_GET["ID"])) {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Location: ../?ID=" . $_GET["ID"]);
    } else {
        header("Location: ../");
    }
}

//Include header
include("/home/schelsge/public_html/includes/header.php");

?>

<title>Niet aangemeld - Schelsgebied</title>

<div id="container">
    <h2 class="center"><img src="/images/icons/qr.png">QR-code gescand</h2>
    <p class="center">We proberen je aan te melden om deze trolley op je naam te registreren.</p>
    <p class="center">Ben je hier na 10 seconden nog steeds? Meld je eerst hieronder aan en scan daarna de QR code
        opnieuw.</p>
    <a class="normal full rounded small" href="/account">Aanmelden</a>
</div>

<?php

//Include footer
include("/home/schelsge/public_html/includes/footer.html");

?>