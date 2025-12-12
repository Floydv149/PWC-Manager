<?php

function test_input($input)
{
    return trim(htmlspecialchars(stripslashes(strip_tags($input))));
}

$fileName = test_input($_GET["f"]);

unlink("../../../../../../../images/cartCovers/$fileName");

header("Location: ../");

?>