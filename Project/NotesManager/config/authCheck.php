<?php
// This file serves as a convenient way to include authentication checks
// Include this at the top of any page that requires authentication

// Check if the file is accessed directly
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header('Location: ../index.html');
    exit;
}

// Calculate the relative path to the config directory based on the including file
$includingFile = debug_backtrace()[0]['file'];
$configPath = '';

// If the file is in the lib directory
if (strpos($includingFile, '/lib/') !== false) {
    $configPath = '../config/';
} else {
    $configPath = './config/';
}

// Include the timeout check
require_once($configPath . 'timeout.php');
?>
