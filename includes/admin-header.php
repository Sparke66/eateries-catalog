<!-- Site header for administrator pages -->
<!-- Shows the portal badge, the signed-in user, and a logout button -->
<!-- Only include on pages that already require login -->

<header class="site-header">
  <a class="brand" href="/admin">
    <span class="logo" aria-hidden="true">🍴</span>
    Ithaca Eateries Catalog
  </a>
  <span class="badge">Administrator Portal</span>

  <div class="admin-login">
    <span>Signed in as <?php echo htmlspecialchars(current_user()["username"]); ?></span>
    <!-- Posting "logout" is handled by process_session_params() in includes/sessions.php -->
    <form method="post">
      <button class="button" type="submit" name="logout">Logout</button>
    </form>
  </div>
</header>
