<?php
// Check if the form values exist
if (isset($_GET['cislo1']) && isset($_GET['cislo2'])) {
    // Retrieve the values from the form
    $var1 = $_GET['cislo1'];
    $var2 = $_GET['cislo2'];

    // Sum the two variables
    $sum = $var1 + $var2;

    // Print the result
    echo "The sum of $var1 and $var2 is: $sum";
    echo "\n";
    echo "<a href='http://perun.nti.tul.cz/~cyril.steger/250218/script_sum.php'>Back to script_sum.php</a>";
    echo "\n";
    exit;
} else {
    echo "Sorry, an error has occurred. \n";
    //wait
    sleep(5);
    //back to script_get.php
    header("Location: http://perun.nti.tul.cz/~cyril.steger/250218/script_sum.php"); // Redirects to the form
    exit;
}

?>
