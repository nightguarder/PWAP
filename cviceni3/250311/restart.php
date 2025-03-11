<?php
session_start(); //bezi v HEADERU
session_unset(); //vymazani session
session_destroy(); //zruseni session
header("Location: session.php");