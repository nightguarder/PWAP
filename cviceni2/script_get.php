<?php
// Display the heading
echo "Kalkulacka 2 cisel\n";
?>
<html>
<head>
    <title>Kalkulacka</title>
</head>
<body>
    <!-- Form to submit two numbers -->
    <form action="sum.php" method="get">
        <input type="number" name="cislo1" required><br>
        <input type="number" name="cislo2" required><br>
        <input type="submit" value="Result">
    </form>
</body>

</html>
<?php
print_r($_GET);

?>