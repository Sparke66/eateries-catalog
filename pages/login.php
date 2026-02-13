<?php

/**
 * Admin Login Page
 * Handles administrator authentication
 * Redirects to admin portal if already logged in
 */

// If user is already logged in, redirect to admin page
if (is_user_logged_in()) {
    header("Location: /admin");
    exit;
}
?>

<!DOCTYPE html>
<!-- Handle CSS styling separately for admin pages -->
<html class="admin-page" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Page</title>
</head>

<body>

    <h1>ADMIN LOGIN PAGE</h1>

    <!-- Display login form with any error messages -->
    <?php echo login_form("/login", $session_messages); ?>

</body>

</html>
