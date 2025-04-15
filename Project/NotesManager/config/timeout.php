<?php
session_start();
// If the user is not logged in redirect to the login page...
$currentTime = time();
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

// Function to check session timeout
// This function checks if the session has timed out
// If the session has timed out, destroy the session and redirect to the login page
// This function is called at the top of any page that requires authentication
function sessionTimeout($timeout) {
    if (isset($_SESSION['destroy']) && time() > $_SESSION['destroy']) {
        session_destroy();
        redirect();
        exit;
    }
}
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
