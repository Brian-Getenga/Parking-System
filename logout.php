<?php
session_start();

// Destroy all session data
$_SESSION = array(); // Clear the session variables

// If you want to delete the session cookie, you can do this
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], 
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session
session_destroy();

// Redirect to the login page or homepage
header("Location: index.php"); // Change to 'login.php' if preferred
exit();
?>
