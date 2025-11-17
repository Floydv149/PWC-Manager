<?php

session_start();

if ($_SESSION["loggedIn"] != true || !in_array(51, $_SESSION["permissions"])) {
    header("Location: ../");
}

include("/home/schelsge/public_html/includes/header.php");

$directory = "../../../../../../images/cartCovers/";

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

function fileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach ($arBytes as $arItem) {
        if ($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
            break;
        }
    }
    return $result;
}

?>

<style>
    table {
        overflow-x: auto;
        display: block;
    }

    table img {
        width: 24px;
    }
</style>

<title>Cover-afbeeldingen beheren - Schelsgebied</title>

<div id="container">
    <a class="normal small rounded full" href="../"><img src="/images/icons/back.png">Terug</a>
    <h2 align="center"><img src="/images/icons/settingsIcon.png">COVER-AFBEELDINGEN BEHEREN</h2>

    <fieldset>
        <legend>Bestand uploaden</legend>

        <form action="index.php" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><input type="file" name="files[]" multiple></td>
                </tr>
                <tr>
                    <td><input type="submit" name="submit" value="Uploaden"></td>
                </tr>
            </table>
        </form>
    </fieldset>

    <h3>Bestanden</h3>
    <table>
        <td width="24px"></td>
        <td width="37.5%"></td>
        <td width="37.5%"></td>
        <td width="12.5%"></td>
        <td width="12.5%"></td>

        <?php

        if (isset($_POST['submit'])) {
            foreach ($_FILES['files']['name'] as $i => $fileName) {
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                if (!move_uploaded_file($_FILES['files']['tmp_name'][$i], $directory . pathinfo($fileName, PATHINFO_FILENAME) . ".png")) {
                    echo "Fout bij het uploaden van het/de bestand(en).";
                }
            }
        }

        if ($dir = opendir($directory)) {
            $files = [];

            while (($file = readdir($dir)) !== false) {
                if ($file != "." && $file != "..") {
                    $files[] = $file;
                }
            }

            rsort($files);

            for ($i = 0; $i < count($files); $i++) {
                $extension = pathinfo($files[$i], PATHINFO_EXTENSION);
                $data = "";

                echo "<tr><td><img src='";
                if ($extension == "pdf") {
                    echo "/images/icons/pdfIcon.png";
                } else if ($extension == "png" || $extension == "jpg" || $extension == "jpeg") {
                    echo "/images/icons/imageIcon.png";
                    $data = "(" . getimagesize($directory . $files[$i])[0] . "x" . getimagesize($directory . $files[$i])[1] . ") ";
                }

                $data .= fileSizeConvert(filesize($directory . $files[$i]));

                echo "'></td>";

                echo "<td><a target='_blank' href='$directory$files[$i]'>$files[$i]</a></td>";
                echo "<td>$data</td>";
                echo "<td><a onclick='renameFile(&quot;$files[$i]&quot;);'>&#127991; Hernoemen</a></td>";
                echo "<td><a href='delete?f=$files[$i]'>&#128465; Verwijderen</a></td></tr>";
            }

            closedir($dir);
        }

        ?>

    </table>
</div>

</body>

<script>
    function renameFile(file) {
        if (newFileName = prompt("Geef een nieuwe naam aan het bestand '" + file + "'.", file)) {
            location.href = "rename?f=" + file + "&n=" + newFileName;
        }
    }
</script>

<?php include("/home/schelsge/public_html/includes/footer.html"); ?>