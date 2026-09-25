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
<html lang="en">

<?php $pageTitle = "Administrator Sign In"; include("includes/meta.php") ?>

<body>

    <?php include("includes/header.php") ?>

    <main class="login">
        <div class="card form-card">
            <p class="eyebrow">Administrator</p>
            <h1>Sign in</h1>

            <!-- Login feedback messages set by password_login() in includes/sessions.php -->
            <?php foreach ($session_messages as $message): ?>
                <p class="error"><?php echo htmlspecialchars($message); ?></p>
            <?php endforeach; ?>

            <!-- Field names match what process_session_params() expects -->
            <form action="/login" method="post" novalidate>
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="login_username" value="<?php echo htmlspecialchars($sticky_login_username ?? ""); ?>" required />
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="login_password" required />
                </div>

                <button class="button primary full" name="login" type="submit">Sign In</button>
            </form>

            <p class="centered"><a href="/">← Back to public catalog</a></p>
        </div>
    </main>

</body>

</html>
