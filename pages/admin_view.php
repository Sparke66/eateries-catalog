<?php

/**
 * Administrator Portal - Main View
 * Displays all restaurants with edit controls
 * Requires user authentication - redirects to login if not authenticated
 */

// Checks if user is an administrator; implement for every admin page
if (!is_user_logged_in()) {
    // Not logged in - redirect to login page
    header("Location: /login");
    exit;
} else if (!is_admin()) {
    // Logged in without the admin role - send back to the public catalog
    header("Location: /");
    exit;
}

// retrieve query string parameter for filtering
// Allows admins to filter restaurants by tag
// Several tags can be selected at once (filter[]=A&filter[]=B); a single filter=A also works
// Non-string values (tampered query strings) are ignored
$filters = array_values(array_filter((array)($_GET["filter"] ?? array()), "is_string"));

// query the database for list of tags, grouped by category
$sql_tag_query = "SELECT category, id, name FROM tags ORDER BY id";
$tag_groups = exec_sql_query($db, $sql_tag_query)->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);

// note we can use the tag names directly for the filter parameters
// conditionally building the restaurant query
if ($filters) {
    // Filter active: join with tags table to get matching restaurants
    // OR logic: a restaurant matches if it has any of the selected tags; DISTINCT avoids duplicates
    $sql_select_clause = "SELECT DISTINCT restaurants.*
  FROM restaurants
  INNER JOIN restaurant_tags ON (restaurants.id = restaurant_tags.restaurant_id)
  INNER JOIN tags ON (restaurant_tags.tag_id = tags.id)";
    $placeholders = implode(", ", array_fill(0, count($filters), "?"));
    $sql_filter_clause = " WHERE tags.name IN (" . $placeholders . ") ORDER BY restaurants.name";

    $sql_rest_query = $sql_select_clause . $sql_filter_clause;
    $restaurants = exec_sql_query(
        $db,
        $sql_rest_query,
        $filters
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
            <!-- Tag filter sidebar for admin view; clicking a tag toggles it, selected tags are highlighted -->
            <nav class="tag-nav">
                <a class="<?php if (!$filters) echo "active"; ?>" href="/admin">All Restaurants</a>
                <?php foreach ($tag_groups as $category => $tags) { ?>
                    <h2><?php echo htmlspecialchars($category) ?></h2>
                    <?php
                    // Generate filter link for each tag
                    foreach ($tags as $tag) {
                        $tag_name = $tag["name"];
                        // Link toggles this tag in the current selection
                        $is_active = in_array($tag_name, $filters, TRUE);
                        $toggled = $is_active ? array_diff($filters, array($tag_name)) : array_merge($filters, array($tag_name));
                    ?>
                        <a class="<?php if ($is_active) echo "active"; ?>" href="/admin?<?php echo http_build_query(array(
                                            "filter" => array_values($toggled)
                                        )); ?>"><?php echo htmlspecialchars($tag_name) ?></a>
                    <?php
                    }
                    ?>
                <?php } ?>
            </nav>
        </aside>

        <main class="admin">
            <div class="section-heading">
                <div>
                    <h1>List of Restaurants</h1>
                    <p class="muted">
                        Filter: <?php echo htmlspecialchars($filters ? implode(" or ", $filters) : "All"); ?>
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
