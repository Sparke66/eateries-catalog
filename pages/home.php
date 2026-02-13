<?php

/**
 * Consumer Home Page - Restaurant Catalog
 * Displays all restaurants with filtering by tag/cuisine type
 * Main public-facing page for browsing restaurants
 */

// Constant array mapping numeric ratings to star display strings
const RATING_STARS = array(
  1 => "★☆☆☆☆",
  2 => "★★☆☆☆",
  3 => "★★★☆☆",
  4 => "★★★★☆",
  5 => "★★★★★"
);

// retrieve query string parameter for filtering
// If no filter is set, show all restaurants
// If no filter is set, show all restaurants
$filter_param = $_GET["filter"] ?? NULL;

// query the database for list of tags
// Used to populate the filter buttons
$sql_tag_query = "SELECT * FROM tags ORDER BY name";
$tags = exec_sql_query($db, $sql_tag_query)->fetchAll();

// note we can use the tag names directly for the filter parameters
// conditionally building the restaurant query
if ($filter_param) {
  // If filter is active, join with tags to get only matching restaurants
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
<html lang="en">


<!-- Will need to eventually turn this into a partial via meta.php -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Ithaca Eateries Catalog</title>

  <link rel="stylesheet" type="text/css" href="/styles/site.css">
</head>

<?php include("includes/meta.php") ?>

<body>

  <main class="consumer">
    <!-- Consumer Page shall be implemented for narrow screen -->

    <h1>Ithaca Eateries Catalog</h1>

    <!-- Display the list of tags; may need to port over to partial -->
    <!-- Tag filter bar allows users to filter by cuisine type -->
    <div class="tags">
      <p> Select Cuisine Type: </p>
      <div class="tag-list">

        <!-- Reset Filter button -->
        <a href="/">
          <button class="tag">All Restaurants</button>
        </a>

        <?php
        // Generate a filter button for each tag
        foreach ($tags as $tag) {
          $tag_name = $tag["name"];
        ?>
          <a href="/?<?php echo http_build_query(array(
                        "filter" => $tag_name
                      )); ?>">
            <button class="tag"><?php echo htmlspecialchars($tag_name) ?></button>
          </a>
        <?php
        }
        ?>
      </div>


    </div>

    <!-- Restaurant catalog grid -->
    <div class="catalog">
      <?php
      // Display each restaurant as a tile
      foreach ($restaurants as $restaurant) {
        $id = $restaurant["id"]; // added as reference for parameter to be passed
        $name = $restaurant["name"];
        $address = $restaurant["address"];
        $rating = RATING_STARS[$restaurant["rating"]]; // Convert numeric rating to stars
        $avg_price = $restaurant["avg_price"];
        $description = $restaurant["description"];

        // insert partials for individual eateries
        include "includes/restaurant-tile.php";
      }

      ?>
    </div>
  </main>

</body>

</html>
