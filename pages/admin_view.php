<?php

/**
 * Administrator Portal - Main View
 * Displays all restaurants with edit controls
 * Requires user authentication - redirects to login if not authenticated
 */

// Checks if user is logged in; implement for every admin page
if (!is_user_logged_in()) {
    // Not logged in - redirect to login page
    header("Location: /login");
    exit;
}

// Constant array mapping numeric ratings to star display strings
const RATING_STARS = array(
    1 => "★☆☆☆☆",
    2 => "★★☆☆☆",
    3 => "★★★☆☆",
    4 => "★★★★☆",
    5 => "★★★★★"
);

// retrieve query string parameter for filtering
// Allows admins to filter restaurants by cuisine type
$filter_param = $_GET["filter"] ?? NULL;

// query the database for list of tags
$sql_tag_query = "SELECT * FROM tags ORDER BY name";
$tags = exec_sql_query($db, $sql_tag_query)->fetchAll();

// note we can use the tag names directly for the filter parameters
// conditionally building the restaurant query
if ($filter_param) {
    // Filter active: join with tags table to get matching restaurants
    $sql_select_clause = "SELECT restaurants.*
  FROM restaurants
  INNER JOIN restaurant_tags ON (restaurants.id = restaurant_tags.restaurant_id)
  INNER JOIN tags ON (restaurant_tags.tag_id = tags.id)";
    $sql_filter_clause = " WHERE tags.name = '" . $filter_param . "'";

    $sql_rest_query = $sql_select_clause . $sql_filter_clause;
    $restaurants = exec_sql_query($db, $sql_rest_query)->fetchAll();
} else {
    // No filter: show all restaurants alphabetically
    $sql_rest_query = "SELECT * FROM restaurants ORDER BY name";
    $restaurants = exec_sql_query($db, $sql_rest_query)->fetchAll();
}

?>
<!DOCTYPE html>
<!-- Handle CSS styling separately for admin pages -->
<html class="admin-page" lang="en">

<!-- Will need to eventually turn this into a partial via meta.php -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Administrator Portal</title>

    <link rel="stylesheet" type="text/css" href="/styles/site.css">
</head>

<?php include("includes/meta.php") ?>

<body>
    <!-- Header consists of the Website Title, Portal Identification, Login/Logout Controls, and List of Tags -->
    <header class="admin-header">
        <h1>Ithaca Eateries Catalog</h1>
        <div class="header-middle">
            <h2>Administrator Portal</h2>

            <div class="admin-login">
                <?php if (is_user_logged_in()): ?>
                    <!-- User IS logged in - show logout -->
                    <form method="POST">
                        <button type="submit" name="logout">Logout</button>
                    </form>
                    <!-- Note: User redirected to login page if not logged in
                    Login form not implemented. -->
                <?php endif; ?>
            </div>

        </div>

    </header>

    <!-- Two-column layout: sidebar with tags, main content with restaurant list -->
    <div class="admin-aside-main">
        <aside class="admin-tags">
            <!-- Display the list of tags; may need to port over to partial -->
            <!-- Tag filter sidebar for admin view -->
            <p> Select Cuisine Type: </p>
            <?php
            // Generate filter link for each tag
            foreach ($tags as $tag) {
                $tag_name = $tag["name"];
            ?>
                <a href="/admin?<?php echo http_build_query(array(
                                    "filter" => $tag_name
                                )); ?>">
                    <p class="tag"><?php echo htmlspecialchars($tag_name) ?></p>
                </a>
            <?php
            }
            ?>
        </aside>

        <main class="admin">
            <!-- Administrator Page shall be implemented for wide screen -->

            <!-- Link to add new restaurant -->
            <a href="/admin/entry"> Add New Restaurant</a>

            <div class="catalog">
                <h3>List of Restaurants</h3>
                <?php
                // Display each restaurant in admin tile format with edit button
                foreach ($restaurants as $restaurant) {
                    $id = $restaurant["id"]; // added as reference for parameter to be passed
                    $name = $restaurant["name"];
                    $address = $restaurant["address"];
                    $rating = RATING_STARS[$restaurant["rating"]]; // Convert numeric rating to stars
                    $avg_price = $restaurant["avg_price"];
                    $description = $restaurant["description"];

                    // insert partials for individual eateries
                    include "includes/admin-restaurant-tile.php";
                }

                ?>
            </div>
        </main>
    </div>


</body>

</html>
