<?php
session_start(); //bezi v HEADERU
echo "<pre> Session ID: " . session_id() . "</pre>";

//pridani prvku do SESSION pole
//$_SESSION je superglobalni pole 
//modify the time using isset so it doesn't get reset every time the page is refreshed
//TIME is set to current time
if(!isset($_SESSION["time"])){
    $_SESSION["time"] = date("Y-M-d H:i:s");
}
print_r($_SESSION);
    $new_color = 0;
    do{
?>
<div style= 'background-color: rgb(<?= $new_color ?>,<?= $new_color ?>,<?= $new_color ?>);'>
    <br>
    <p>RGB je: <?= $new_color ?> <?= $new_color ?> <?= $new_color ?></p>
</div>
<?php
    $new_color += 10;
    } while ($new_color <= 255);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odstiny.php</title>
</head>
<body>
    <form action="odstiny.php" method="get">
        <!-- Form to submit a color -->
        <input type="number" name="color" min="0" max="255" value="<?=$new_color?>"><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>