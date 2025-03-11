
<?php
//RGB
$color = 0;
//do
do {
    echo "<div style='background-color: rgb($color, $color, $color); height: 100vh;'>";
    echo "<pre>RGB color: $color, $color, $color</pre>";
    echo "<pre>HEX color: " . dechex($color) . dechex($color) . dechex($color) . "</pre>";
    echo "</div>";
    $color += 25;
} while ($color <= 255);
?>
