<!-- //pozadi.php
//sets the color of div based on color which is in _$SESSION array -->

<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Display Color</title>
</head>
<body>
    <h2>Current Color from Session</h2>
    <?php
    // Get the color from the session, default to 0 if not set
    $color = isset($_SESSION['color']) ? $_SESSION['color'] : 0;
    ?>
    <div style="background-color: rgb(<?= $color ?>,<?= $color ?>,<?= $color ?>); height: 300px; display: flex; align-items: center; justify-content: center;">
        <p style="color: white; font-size: 24px;">RGB: <?= $color ?>, <?= $color ?>, <?= $color ?></p>
    </div>

    <!-- Option to return to the color picker -->
    <p><a href="color.php">Change Color</a></p>

    <!-- Debug session info -->
    <h4>Session Information:</h4>
    <pre><?php print_r($_SESSION); ?></pre>
</body>
</html>