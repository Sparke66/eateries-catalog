<?php

/**
 * Consumer Home Page - Restaurant Catalog
 * Displays all restaurants with filtering by tag/cuisine type
 * Main public-facing page for browsing restaurants
 */

// retrieve query string parameter for filtering
// If no filter is set, show all restaurants
// Several tags can be selected at once (filter[]=A&filter[]=B); a single filter=A also works
// Non-string values (tampered query strings) are ignored
$filters = array_values(array_filter((array)($_GET["filter"] ?? array()), "is_string"));

// query the database for list of tags, grouped by category
// Used to populate the filter buttons (cuisine, dietary, then affordability tags, in seed order)
$sql_tag_query = "SELECT category, id, name FROM tags ORDER BY id";
$tag_groups = exec_sql_query($db, $sql_tag_query)->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);

// note we can use the tag names directly for the filter parameters
// conditionally building the restaurant query
// user_rating is the average of all user review ratings (NULL when there are none)
if ($filters) {
  // If filter is active, join with tags to get only matching restaurants
  // OR logic: a restaurant matches if it has any of the selected tags; DISTINCT avoids duplicates
  $sql_select_clause = "SELECT DISTINCT restaurants.*,
    (SELECT AVG(reviews.rating) FROM reviews WHERE reviews.restaurant_id = restaurants.id) AS user_rating
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
  $sql_rest_query = "SELECT restaurants.*, (SELECT AVG(reviews.rating) FROM reviews WHERE reviews.restaurant_id = restaurants.id) AS user_rating FROM restaurants ORDER BY name";
  $restaurants = exec_sql_query($db, $sql_rest_query)->fetchAll();
}

$result_count = count($restaurants);

?>
<!DOCTYPE html>
<html lang="en">

<?php include("includes/meta.php") ?>

<body>

  <?php include("includes/header.php") ?>

  <main class="consumer">

    <!-- Tag filter bar allows users to filter by tag, one row per tag category -->
    <!-- Clicking a tag adds it to or removes it from the selection; selected tags are highlighted -->
    <section class="tags card">
      <div class="tag-list">
        <!-- Reset Filter button -->
        <a class="tag<?php if (!$filters) echo " active"; ?>" href="/">All Restaurants</a>
      </div>

      <?php foreach ($tag_groups as $category => $tags) { ?>
        <h2><?php echo htmlspecialchars($category) ?></h2>
        <div class="tag-list">
          <?php
          // Generate a filter button for each tag
          foreach ($tags as $tag) {
            $tag_name = $tag["name"];
            // Link toggles this tag in the current selection
            $is_active = in_array($tag_name, $filters, TRUE);
            $toggled = $is_active ? array_diff($filters, array($tag_name)) : array_merge($filters, array($tag_name));
          ?>
            <a class="tag<?php if ($is_active) echo " active"; ?>" href="/?<?php echo http_build_query(array(
                          "filter" => array_values($toggled)
                        )); ?>"><?php echo htmlspecialchars($tag_name) ?></a>
          <?php
          }
          ?>
        </div>
      <?php } ?>
    </section>

    <!-- Heading names the active filter and counts the results -->
    <div class="section-heading">
      <h1><?php echo htmlspecialchars($filters ? implode(" or ", $filters) : "All Restaurants"); ?></h1>
      <p class="muted"><?php echo $result_count . ($result_count == 1 ? " result" : " results"); ?></p>
    </div>

    <!-- Restaurant catalog grid -->
    <div class="catalog">
      <?php
      // Display each restaurant as a tile
      foreach ($restaurants as $restaurant) {
        $id = $restaurant["id"]; // added as reference for parameter to be passed
        $name = $restaurant["name"];
        $address = $restaurant["address"];
        $rating = $restaurant["rating"];
        $user_rating = $restaurant["user_rating"];
        $avg_price = $restaurant["avg_price"];
        $description = $restaurant["description"];

        // insert partials for individual eateries
        include "includes/restaurant-tile.php";
      }

      ?>
    </div>

    <?php if ($result_count == 0): ?>
      <p class="muted">No restaurants match this filter yet.</p>
    <?php endif; ?>
  </main>

</body>

</html>
