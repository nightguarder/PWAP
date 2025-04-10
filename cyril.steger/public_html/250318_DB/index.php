<?php
session_start();//pouzivame session
include "connect.php";//DB connection

//Fetch data from DB
if (isset($_SESSION["user_id"])) {//pokud existuje session, zjistime, kdo to je
    $query = "SELECT * FROM `uzivatel` WHERE `id` = '" . $_SESSION["user_id"] . "' ";
    $result = $db->query($query) or die($query);
    while($row = $result->fetch_assoc()){
        ?><p>Tvůj nick je <?=$row["nick"]?>. Jako známý uživatel se můžeš podívat na <a href="uzivatele.php">seznam uživatelů</a>. </p><?php
    }
    echo "<br>";
    echo "Další informace o uživateli.";
} else {
    ?>
        <p>Vítej neznámý uživateli. Abys mohl posílat zprávy, musíš se <a href="login.php">Login</a> nebo <a href="register.php">Register</a>. </p>
    <?php

}
include "layout.php"; //insert the menu