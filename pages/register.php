<?php

/**
 * Registration Page
 * Lets visitors create a regular (non-admin) account
 * Logs the new user in and returns them to the catalog on success
 */

// Already signed in - nothing to register
if (is_user_logged_in()) {
    header("Location: /");
    exit;
}

// Create the account if the form was submitted; reasons for failure go in $signup_messages
if (process_signup_params($db)) {
    header("Location: /");
    exit;
}

// Hidden form field value; fetched before output because it may set a cookie
$csrf_token = csrf_token();
?>

<!DOCTYPE html>
<html lang="en">

<?php $pageTitle = "Create an Account"; include("includes/meta.php") ?>

<body>

    <?php include("includes/header.php") ?>

    <main class="login">
        <div class="card form-card">
            <p class="eyebrow">Account</p>
            <h1>Create an account</h1>

            <!-- Signup feedback messages set by create_account() in includes/sessions.php -->
            <?php foreach ($signup_messages as $message): ?>
                <p class="error"><?php echo htmlspecialchars($message); ?></p>
            <?php endforeach; ?>

            <!-- Field names match what process_signup_params() expects -->
            <form action="/register" method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>" />

                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="signup_username" value="<?php echo htmlspecialchars($sticky_signup_username ?? ""); ?>" autocomplete="username" minlength="3" maxlength="30" pattern="[A-Za-z0-9_]+" aria-describedby="username-hint" required />
                    <p class="hint" id="username-hint">3–30 characters: letters, numbers, and underscores</p>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="signup_password" autocomplete="new-password" minlength="8" aria-describedby="password-hint" required />
                    <p class="hint" id="password-hint">At least 8 characters</p>
                </div>

                <div class="field">
                    <label for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" type="password" name="signup_confirm_password" autocomplete="new-password" required />
                </div>

                <button class="button primary full" name="signup" type="submit">Create Account</button>
            </form>

            <p class="centered">Already have an account? <a href="/login">Sign in</a></p>
            <p class="centered"><a href="/">← Back to public catalog</a></p>
        </div>
    </main>

</body>

</html>
