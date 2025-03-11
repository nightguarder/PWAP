<?php
// odstiny.php
session_start();
session_regenerate_id(true); // Security measure

// Handle color selection
if (isset($_GET["color"])) {
    // Sanitize and validate input
    $submitted_color = (int)$_GET["color"]; // Convert to integer
    $_SESSION["color"] = max(0, min(255, $submitted_color)); // Clamp to 0-255
    header("Location: pozadi.php");
    exit();
}
// Set default color
if (!isset($_SESSION['color'])) {
    $_SESSION['color'] = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Color Picker</title>
    <style>
        .color-block {
            display: inline-block;
            padding: 20px;
            margin: 5px;
            border: 1px solid #ccc;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Choose a Gray Scale Color</h2>
    
    <!-- Color blocks in steps of 10 -->
    <?php for ($i = 0; $i <= 255; $i += 10): ?>
        <a href="color.php?color=<?= $i ?>">
            <div class="color-block" style="background-color: rgb(<?= $i ?>,<?= $i ?>,<?= $i ?>)">
                <?= $i ?>
            </div>
        </a>
    <?php endfor; ?>

    <!-- Manual input form -->
    <h3>Or enter a custom value (0-255):</h3>
    <form method="GET">
        <input type="number" name="color" min="0" max="255" value="<?= $_SESSION['color'] ?>">
        <input type="submit" value="Set Custom Color">
    </form>

    <!-- Session debug info -->
    <h4>Session Information:</h4>
    <pre><?php print_r($_SESSION); ?></pre>
</body>
</html>