<?php

//tady se spojime s DB
$server = "localhost";
$user = "cyril.steger";
$pass = "www";
$dbname = "cyril.steger";
$db = new mysqli($server, $user, $pass, $dbname);

if ($db->connect_errno) {
    echo $db->connect_error;
    //pokud je vse v poradku, nic se nestane
} else {

    echo "Connected successfully";
    echo "<br>";

}