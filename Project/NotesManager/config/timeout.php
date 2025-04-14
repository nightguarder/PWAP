<?php
session_start();
// If the user is not logged in redirect to the login page...
function redirect() {
	header("refresh:3; url=logout.php");
    print '<p>Redirecting you to the Login page... If not, click <a href="../index.html">here</a>.</p>';
}

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    redirect();
    exit;
}

// Check for session timeout
$currentTime = time();
if (isset($_SESSION['destroy']) && $currentTime > $_SESSION['destroy']) {
    print '<p>Session timeout.</p>';
    redirect();
    exit;
}

// Reset timeout for another 10 minutes
$_SESSION['destroy'] = $currentTime + 600;

// Return user info for verification
$user_display = $_SESSION['name'];
?>
