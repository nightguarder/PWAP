<?php
// Display the heading
echo "Kalkulacka 2 cisel\n";

	$cislo1 = filter_input(INPUT_POST,"cislo1");
	$cislo2 = filter_input(INPUT_POST,"cislo2");
	$soucet = $cislo1 + $cislo2;
?>
<html>
<head>
    <title>Kalkulacka</title>
</head>
<body>
    <!-- Form to submit two numbers -->
    <form action="script_post.php" method="post">
        <input type="number" name="cislo1" required><br>
        <input type="number" name="cislo2" required><br>
        <input type="submit" value="Result">
    </form>
</body>
</html>
<?php
echo "The sum of $cislo1 and $cislo2 is: $soucet";
echo "\n\nPOST: ";
print_r($_POST);

echo "\n\nGET: ";
print_r($_GET);

echo "\n\nREQUEST: ";
print_r($_REQUEST);
?>
