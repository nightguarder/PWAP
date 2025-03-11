<?php
session_start();
//pristup k session poli

echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo "<pre> Session ID: " . session_id() . "</pre>";
echo "<pre> Session name: " . session_name() . "</pre>";
echo "<pre> Session save path: " . session_save_path() . "</pre>";
echo "<pre> Session status: " . session_status() . "</pre>";
echo "<pre> Session cookie params: ";
print_r(session_get_cookie_params());
echo "</pre>";
?>