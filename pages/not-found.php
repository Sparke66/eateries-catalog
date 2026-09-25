<!DOCTYPE html>
<!-- 404 Error Page -->
<!-- Displayed when requested page/resource is not found -->
<html lang="en">

<?php $pageTitle = "Page Not Found"; include("includes/meta.php") ?>

<body>

  <?php include("includes/header.php") ?>

  <main class="not-found">
    <p class="error-code">404</p>
    <h1>Page not found</h1>
    <p class="muted">The page you’re looking for doesn’t exist or may have moved.</p>
    <a class="button primary" href="/">Back to the catalog</a>
  </main>

</body>

</html>
