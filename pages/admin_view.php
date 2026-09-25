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

// retrieve query string parameter for filtering
// Allows admins to filter restaurants by cuisine type
$filter_param = $_GET["filter"] ?? NULL;

// query the database for list of tags
$sql_tag_query = "SELECT * FROM tags ORDER BY id";
$tags = exec_sql_query($db, $sql_tag_query)->fetchAll();

// note we can use the tag names directly for the filter parameters
// conditionally building the restaurant query
if ($filter_param) {
    // Filter active: join with tags table to get matching restaurants
    $sql_select_clause = "SELECT restaurants.*
  FROM restaurants
  INNER JOIN restaurant_tags ON (restaurants.id = restaurant_tags.restaurant_id)
  INNER JOIN tags ON (restaurant_tags.tag_id = tags.id)";
    $sql_filter_clause = " WHERE tags.name = :filter ORDER BY restaurants.name";

    $sql_rest_query = $sql_select_clause . $sql_filter_clause;
    $restaurants = exec_sql_query(
        $db,
        $sql_rest_query,
        array(':filter' => $filter_param)
    )->fetchAll();
} else {
    // No filter: show all restaurants alphabetically
    $sql_rest_query = "SELECT * FROM restaurants ORDER BY name";
    $restaurants = exec_sql_query($db, $sql_rest_query)->fetchAll();
}

$entry_count = count($restaurants);

?>
<!DOCTYPE html>
<!-- Handle CSS styling separately for admin pages -->
<html class="admin-page" lang="en">

<?php $pageTitle = "Administrator Portal"; include("includes/meta.php") ?>

<body>
    <!-- Header consists of the Website Title, Portal Identification, and Login/Logout Controls -->
    <?php include("includes/admin-header.php") ?>

    <!-- Two-column layout: sidebar with tags, main content with restaurant list -->
    <div class="admin-aside-main">
        <aside class="admin-tags">
            <!-- Tag filter sidebar for admin view; the active filter is highlighted -->
            <h2>Select Cuisine Type</h2>
            <nav class="tag-nav">
                <a class="<?php if (!$filter_param) echo "active"; ?>" href="/admin">All Restaurants</a>
                <?php
                // Generate filter link for each tag
                foreach ($tags as $tag) {
                    $tag_name = $tag["name"];
                ?>
                    <a class="<?php if ($filter_param === $tag_name) echo "active"; ?>" href="/admin?<?php echo http_build_query(array(
                                        "filter" => $tag_name
                                    )); ?>"><?php echo htmlspecialchars($tag_name) ?></a>
                <?php
                }
                ?>
            </nav>
        </aside>

        <main class="admin">
            <div class="section-heading">
                <div>
                    <h1>List of Restaurants</h1>
                    <p class="muted">
                        Filter: <?php echo htmlspecialchars($filter_param ?: "All"); ?>
                        · <?php echo $entry_count . ($entry_count == 1 ? " entry" : " entries"); ?>
                    </p>
                </div>
                <!-- Link to add new restaurant -->
                <a class="button primary" href="/admin/entry">+ Add New Restaurant</a>
            </div>

            <div class="catalog">
                <?php
                // Display each restaurant in admin tile format with edit button
                foreach ($restaurants as $restaurant) {
                    $id = $restaurant["id"]; // added as reference for parameter to be passed
                    $name = $restaurant["name"];

                    // insert partials for individual eateries
                    include "includes/admin-restaurant-tile.php";
                }

                ?>
            </div>
        </main>
    </div>


</body>

</html>
