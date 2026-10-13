<!-- Site header for public pages (home, entry details, login, register, 404) -->
<!-- Logo and site title link back to the catalog; account links on the right -->

<header class="site-header">
  <a class="brand" href="/">
    <span class="logo" aria-hidden="true">🍴</span>
    Ithaca Eateries Catalog
  </a>
  <p class="tagline">Find a place to eat in Ithaca</p>

  <div class="account-nav">
    <?php if (is_user_logged_in()) { ?>
      <span>Signed in as <?php echo htmlspecialchars(current_user()["username"]); ?></span>
      <!-- Posting "logout" is handled by process_session_params() in includes/sessions.php -->
      <form method="post">
        <button class="button" type="submit" name="logout">Log out</button>
      </form>
    <?php } else { ?>
      <a href="/login">Sign in</a>
      <a class="button" href="/register">Register</a>
    <?php } ?>
  </div>
</header>
