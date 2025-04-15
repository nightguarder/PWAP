<?php
// Central database configuration to avoid duplication
$DATABASE_HOST = 'sql107.epizy.com';
$DATABASE_USER = 'epiz_31121495';
$DATABASE_PASS = 'zV5I0lWGioAExLi';
$DATABASE_NAME = 'epiz_31121495_PwdManager';

// Create a reusable function for database connections
function getDbConnection() {
    global $DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME;
    
    $conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    
    if (!$conn) {
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    
    return $conn;
}
?>
