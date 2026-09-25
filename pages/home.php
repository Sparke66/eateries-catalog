<?php

/**
 * Consumer Home Page - Restaurant Catalog
 * Displays all restaurants with filtering by tag/cuisine type
 * Main public-facing page for browsing restaurants
 */

// retrieve query string parameter for filtering
// If no filter is set, show all restaurants
$filter_param = $_GET["filter"] ?? NULL;

// query the database for list of tags
// Used to populate the filter buttons (cuisine, dietary, then price tags, in seed order)
$sql_tag_query = "SELECT * FROM tags ORDER BY id";
$tags = exec_sql_query($db, $sql_tag_query)->fetchAll();

// note we can use the tag names directly for the filter parameters
// conditionally building the restaurant query
if ($filter_param) {
  // If filter is active, join with tags to get only matching restaurants
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

$result_count = count($restaurants);

?>
<!DOCTYPE html>
<html lang="en">

<?php include("includes/meta.php") ?>

<body>

  <?php include("includes/header.php") ?>

  <main class="consumer">

    <!-- Tag filter bar allows users to filter by cuisine type -->
    <!-- The active filter is highlighted -->
    <section class="tags card">
      <h2>Select Cuisine Type</h2>
      <div class="tag-list">

        <!-- Reset Filter button -->
        <a class="tag<?php if (!$filter_param) echo " active"; ?>" href="/">All Restaurants</a>

        <?php
        // Generate a filter button for each tag
        foreach ($tags as $tag) {
          $tag_name = $tag["name"];
        ?>
          <a class="tag<?php if ($filter_param === $tag_name) echo " active"; ?>" href="/?<?php echo http_build_query(array(
                        "filter" => $tag_name
                      )); ?>"><?php echo htmlspecialchars($tag_name) ?></a>
        <?php
        }
        ?>
      </div>
    </section>

    <!-- Heading names the active filter and counts the results -->
    <div class="section-heading">
      <h1><?php echo htmlspecialchars($filter_param ?: "All Restaurants"); ?></h1>
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
