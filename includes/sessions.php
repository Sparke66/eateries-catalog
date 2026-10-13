<?php

/**
 * Session and Authentication Management
 * Handles user login, logout, session tracking, and account creation
 * Uses cookie-based sessions with database storage for persistence
 */
include_once('includes/db.php');

// User Messages
$session_messages = array();
$signup_messages = array();

// cookie duration expiration time in seconds
// Sessions expire after 1 hour of inactivity
define('SESSION_COOKIE_DURATION', 60 * 60 * 1); // 1 hour = 60 sec * 60 min * 1 hr

// Cookie that holds the CSRF token for form submissions
define('CSRF_COOKIE', 'csrf_token');

/**
 * Find user record by user ID
 * Returns user record from database or NULL if not found
 */
function find_user($db, $user_id)
{
  $records = exec_sql_query(
    $db,
    "SELECT * FROM users WHERE id = :user_id;",
    array(':user_id' => $user_id)
  )->fetchAll();
  if ($records) {
    // users are unique, there should only be 1 record
    return $records[0];
  }
  return NULL;
}

/**
 * Find session record by session hash
 * Looks up active session in database using session cookie value
 * Returns session record or NULL if not found
 */
function find_session($db, $session)
{
  if (isset($session)) {
    $records = exec_sql_query(
      $db,
      "SELECT * FROM sessions WHERE session = :session;",
      array(':session' => $session)
    )->fetchAll();
    if ($records) {
      // sessions are unique, so there should only be 1 record
      return $records[0];
    }
  }
  return NULL;
}

/**
 * Get current logged-in user
 * Provides a function alternative to accessing $current_user global
 */
function current_user()
{
  global $current_user;
  return $current_user;
}

/**
 * Check if a user is currently logged in
 * Returns true if user is authenticated, false otherwise
 */
function is_user_logged_in()
{
  global $current_user;

  // if $current_user is not NULL, then a user is logged in!
  return ($current_user != NULL);
}

/**
 * Check if the current user is an administrator
 * Returns true only for logged-in users whose role is 'admin'
 */
function is_admin()
{
  global $current_user;
  return ($current_user != NULL) && ($current_user['role'] === 'admin');
}

/**
 * Authenticate user with username and password
 * Verifies credentials and creates a new session if valid
 * Returns user record on success, NULL on failure
 */
function password_login($db, &$messages, $username, $password)
{
  global $current_user;
  global $sticky_login_username;

  // Trim whitespace from credentials
  $username = trim($username);
  $password = trim($password);
  // Store username for sticky forms (repopulate on error)
  $sticky_login_username = $username;

  if (isset($username) && isset($password)) {
    // Does this username even exist in our database?
    $records = exec_sql_query(
      $db,
      "SELECT * FROM users WHERE username = :username;",
      array(':username' => $username)
    )->fetchAll();
    if ($records) {
      // Username is UNIQUE, so there should only be 1 record.
      $user = $records[0];

      // Check password against hash in DB
      // Using password_verify for secure bcrypt comparison
      if (password_verify($password, $user['password'])) {
        // Generate session
        $session = session_create_id();

        // Store session ID in database
        $result = exec_sql_query(
          $db,
          "INSERT INTO sessions (user_id, session, last_login) VALUES (:user_id, :session, datetime());",
          array(
            ':user_id' => $user['id'],
            ':session' => $session
          )
        );
        if ($result) {
          // Success, session stored in DB

          // Send this back to the user.
          // Cookie will be used for subsequent requests to maintain login
          setcookie("session", $session, time() + SESSION_COOKIE_DURATION, '/');

          error_log("  login via password successful");
          $current_user = $user;
          return $current_user;
        } else {
          array_push($messages, "Log in failed.");
        }
      } else {
        array_push($messages, "Invalid username or password.");
      }
    } else {
      array_push($messages, "Invalid username or password.");
    }
  } else {
    array_push($messages, "No username or password given.");
  }

  error_log("  failed to login via password");
  $current_user = NULL;
  return $current_user;
}

/**
 * Restore user session from cookie
 * Validates session token and checks expiration
 * Renews cookie if session is still valid
 */
function cookie_login($db, $session)
{
  global $current_user;

  // Did we find the existing session?
  if ($session) {

    // has the session expired?
    // Calculate when this session should expire based on last login
    $login_expiration = new DateTime($session['last_login']);
    $login_expiration->modify('+ ' . SESSION_COOKIE_DURATION . ' seconds');
    $current_datetime = new DateTime();
    if ($login_expiration >= $current_datetime) {
      // session has not expired

      $current_user = find_user($db, $session['user_id']);

      // update the last login in the DB
      exec_sql_query(
        $db,
        "UPDATE sessions SET last_login = datetime() WHERE (id = :session_id);",
        array(':session_id' => $session['id'])
      );

      // Renew the cookie for 1 more hour
      setcookie("session", $session['session'], time() + SESSION_COOKIE_DURATION, '/');

      error_log("  login via cookie successful");
      return $current_user;
    } else {
      // session has expired
      error_log("  session expired");
      logout($db, $session);
    }
  }

  error_log("  failed to login via cookie");
  $current_user = NULL;
  return NULL;
}

/**
 * Log out the current user
 * Removes session from database and clears session cookie
 * Redirects back to current page
 */
function logout($db, $session)
{
  if ($session) {
    // Delete session from database.
    // Note: You probably also need a "cron" job that cleans up expired sessions.
    exec_sql_query(
      $db,
      "DELETE FROM sessions WHERE (session = :session_id);",
      array(':session_id' => $session['session'])
    );
  }

  // Remove the session from the cookie and force it to expire (go back in time).
  setcookie('session', '', time() - SESSION_COOKIE_DURATION, '/');

  // $current_user keeps track of logged in user, set to NULL to forget.
  global $current_user;
  $current_user = NULL;

  error_log("  logout successful");

  // Remove logout query string parameter
  $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

  // Remove a logout query string parameter
  $params = $_GET;
  unset($params['logout']);

  // Add logout param to current page URL.
  $redirect_url = htmlspecialchars($request_uri) . '?' . http_build_query($params);

  // Send the user back to the same page
  // This prevents logout parameter from appearing in URL after logout
  header('Location: ' . $redirect_url);
  exit();
}

/**
 * Generate logout URL for current page
 * Returns URL with logout parameter appended
 */
function logout_url()
{
  $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

  // Add a logout query string parameter
  $params = $_GET;
  $params['logout'] = '';

  // Add logout param to current page URL.
  $logout_url = htmlspecialchars($request_uri) . '?' . http_build_query($params);

  return $logout_url;
}

/**
 * Render HTML login form
 * Displays login form with username and password fields
 * Shows validation messages if login fails
 */
function login_form($action, $messages)
{
  global $sticky_login_username;
  ob_start();
?>
  <!-- Login feedback messages -->
  <ul class="login">
    <?php
    foreach ($messages as $message) {
      echo "<li class=\"feedback\"><strong>" . htmlspecialchars($message) . "</strong></li>\n";
    } ?>
  </ul>

  <!-- Login form with username and password inputs -->
  <form class="login" action="<?php echo htmlspecialchars($action) ?>" method="post" novalidate>
    <div class="label-input">
      <label for="username">Username:</label>
      <input id="username" type="text" name="login_username" value="<?php echo htmlspecialchars($sticky_login_username ?? ""); ?>" required />
    </div>

    <div class="label-input">
      <label for="password">Password:</label>
      <input id="password" type="password" name="login_password" required />
    </div>

    <div class="align-right">
      <button name="login" type="submit">Sign In</button>
    </div>
  </form>
<?php
  $html = ob_get_clean();
  return $html;
}

/**
 * Process login, logout, and session requests
 * Check for login, logout requests. Or check to keep the user logged in.
 * Should be called on every page that requires authentication
 */
function process_session_params($db, &$messages)
{
  // Is there a session? If so, find it!
  $session = NULL;
  if (isset($_COOKIE["session"])) {
    $session_hash = $_COOKIE["session"];

    // Look up session in database
    $session = find_session($db, $session_hash);
  }

  if (isset($_GET['logout']) || isset($_POST['logout'])) { // Check if we should logout the user
    error_log("  attempting to logout...");
    logout($db, $session);
  } else if (isset($_POST['login'])) { // Check if we should login the user
    error_log("  attempting to login with username and password...");
    password_login($db, $messages, $_POST['login_username'], $_POST['login_password']);
  } else if ($session) { // check if logged in already via cookie
    error_log("  attempting to login via cookie...");
    cookie_login($db, $session);
  }
}

/**
 * Alias for process_session_params
 * Alternative function name for backwards compatibility
 */
function process_login_params($db, &$messages)
{
  process_session_params($db, $messages);
}

/**
 * Get the CSRF token for this browser, creating it if needed
 * Double-submit cookie: the token is kept in a cookie and repeated in a hidden
 * form field. Another site can't read the cookie, so it can't forge the field.
 * Call before any page output, since it may send a cookie.
 */
function csrf_token()
{
  static $token = NULL;

  if ($token === NULL) {
    $token = $_COOKIE[CSRF_COOKIE] ?? '';

    // Missing or malformed cookie: issue a fresh random token
    if (!is_string($token) || !preg_match('/^[0-9a-f]{64}$/', $token)) {
      $token = bin2hex(random_bytes(32));
      setcookie(CSRF_COOKIE, $token, array(
        'path' => '/',
        'httponly' => True,
        'samesite' => 'Strict'
      ));
    }
  }
  return $token;
}

/**
 * Check the CSRF token submitted with a POST form
 * Returns true only if the hidden "csrf_token" field matches the cookie
 */
function csrf_token_valid()
{
  $cookie_token = $_COOKIE[CSRF_COOKIE] ?? '';
  $form_token = $_POST['csrf_token'] ?? '';

  return is_string($cookie_token) && is_string($form_token)
    && $cookie_token !== '' && hash_equals($cookie_token, $form_token);
}

/**
 * Create a new user account
 * Validates username and password, creates new user record in database
 * Automatically logs in the user if account creation succeeds
 * Returns true on success; on failure, adds reasons to $signup_messages
 */
function create_account($db, $username, $password, $password_confirmation)
{
  global $signup_messages;
  global $sticky_signup_username;

  // Passwords are trimmed to match password_login(), which trims before verifying
  $username = trim($username);
  $password = trim($password);
  $password_confirmation = trim($password_confirmation);

  $sticky_signup_username = $username;

  $account_valid = True;

  // Username: 3-30 letters, numbers, or underscores
  if ($username === '') {
    $account_valid = False;
    array_push($signup_messages, "Provide a username.");
  } else if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
    $account_valid = False;
    array_push($signup_messages, "Username must be 3 to 30 characters long and use only letters, numbers, and underscores.");
  }

  // Password: at least 8 characters
  if ($password === '') {
    $account_valid = False;
    array_push($signup_messages, "Provide a password.");
  } else if (strlen($password) < 8) {
    $account_valid = False;
    array_push($signup_messages, "Password must be at least 8 characters long.");
  }

  // Check if passwords match
  if ($password !== $password_confirmation) {
    $account_valid = False;
    array_push($signup_messages, "Password confirmation doesn't match your password. Reenter your password.");
  }

  if (!$account_valid) {
    return False;
  }

  // Insert new user; the UNIQUE constraint on username rejects duplicates,
  // which also covers two people registering the same name at the same time
  try {
    exec_sql_query(
      $db,
      "INSERT INTO users (username, password) VALUES (:username, :password);",
      array(
        ':username' => $username,
        // Using bcrypt for secure password storage
        ':password' => password_hash($password, PASSWORD_DEFAULT)
      )
    );
  } catch (PDOException $exception) {
    // 23000 = integrity constraint violation; anything else is a real server error
    if ($exception->getCode() != '23000') {
      throw $exception;
    }
    array_push($signup_messages, "Username is already taken, pick another username.");
    return False;
  }

  // account creation was successful. Login.
  return password_login($db, $signup_messages, $username, $password) != NULL;
}

/**
 * Process signup form submission
 * Check for sign up request and create account if form submitted
 * Returns true if an account was created and the new user is logged in
 */
function process_signup_params($db)
{
  global $signup_messages;
  global $sticky_signup_username;

  if (!isset($_POST['signup'])) {
    return False;
  }

  // Ignore tampered fields (e.g. arrays) rather than erroring on them
  $username = is_string($_POST['signup_username'] ?? NULL) ? $_POST['signup_username'] : '';
  $password = is_string($_POST['signup_password'] ?? NULL) ? $_POST['signup_password'] : '';
  $password_confirmation = is_string($_POST['signup_confirm_password'] ?? NULL) ? $_POST['signup_confirm_password'] : '';

  // Only accept the form from our own registration page
  if (!csrf_token_valid()) {
    $sticky_signup_username = trim($username);
    array_push($signup_messages, "Your form expired. Please submit it again.");
    return False;
  }

  return create_account($db, $username, $password, $password_confirmation);
}
