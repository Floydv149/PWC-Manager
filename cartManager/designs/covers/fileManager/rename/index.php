<?php

function test_input($input)
{
    return trim(htmlspecialchars(stripslashes(strip_tags($input))));
}

$fileName = test_input($_GET["f"]);
$newFileName = test_input($_GET["n"]);

rename("/home/schelsge/public_html/images/cartCovers/$fileName", "/home/schelsge/public_html/images/cartCovers/$newFileName");

header("Location: ../");

?>