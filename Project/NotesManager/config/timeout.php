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
    global $currentTime;
    // If session destroy time is not set, set it now using the provided timeout
    if (!isset($_SESSION['destroy'])) {
        $_SESSION['destroy'] = $currentTime + $timeout;
    }
    
    // Check if the session has expired
    if (isset($_SESSION['destroy']) && $currentTime > $_SESSION['destroy']) {
        session_destroy();
        print '<p>Session timeout.</p>';
        redirect();
        exit;
    }
    
    // Reset timeout for the specified period
    $_SESSION['destroy'] = $currentTime + $timeout;
}

// Default timeout check if not using the function directly
if (isset($_SESSION['destroy']) && $currentTime > $_SESSION['destroy']) {
    print '<p>Session timeout.</p>';
    redirect();
    exit;
}

// Reset timeout for another 10 minutes by default
$_SESSION['destroy'] = $currentTime + 600;

// Return user info for verification
$user_display = $_SESSION['name'];
?>
