<?php

const RATING_STARS = array(
  1 => "★☆☆☆☆",
  2 => "★★☆☆☆",
  3 => "★★★☆☆",
  4 => "★★★★☆",
  5 => "★★★★★"
);

// query the database for list of tags
$sql_tag_query = "SELECT * FROM tags ORDER BY name";
$tags = exec_sql_query($db, $sql_tag_query)->fetchAll();

// building the restaurant query
// is this even the proper way?
$sql_select_clause = "SELECT restaurants.*
FROM restaurants
INNER JOIN restaurant_tags ON (restaurants.id = restaurant_tags.restaurant_id)
INNER JOIN tags ON (restaurant_tags.tag_id = tags.id)";
$sql_filter_clause = ""; // No filter by default


// TODO: complete the rest of this code
if (in_array($filter_param, array("american", "chinese", "exquisite", "french", "gluten-free", "halal", "indian", "inexpensive", "italian", "japanese", "korean", "kosher", "mexican", "moderate", "thai", "vegan"))) {
  if ($filter_param == "american") {
    $sql_filter_field = " 'American' ";
  } elseif ($filter_param == "chinese") {
    $sql_filter_field = " 'Chinese' ";
  } elseif ($filter_param == "exquisite") {
    $sql_filter_field = " 'Exquisite' ";
  } elseif ($filter_param == "french") {
    $sql_filter_field = " 'French' ";
  } elseif ($filter_param == "gluten_free") {
    $sql_filter_field = " 'Gluten-Free' ";
  } elseif ($filter_param == "halal") {
    $sql_filter_field = " 'Halal' ";
  } elseif ($filter_param == "indian") {
    $sql_filter_field = " 'Indian' ";
  } elseif ($filter_param == "inexpensive") {
    $sql_filter_field = " 'Inexpensive' ";
  } elseif ($filter_param == "italian") {
    $sql_filter_field = " 'Italian' ";
  } elseif ($filter_param == "japanese") {
    $sql_filter_field = " 'Japanese' ";
  } elseif ($filter_param == "korean") {
    $sql_filter_field = " 'Korean' ";
  } elseif ($filter_param == "kosher") {
    $sql_filter_field = " 'Kosher' ";
  } elseif ($filter_param == "mexican") {
    $sql_filter_field = " 'Mexican' ";
  } elseif ($filter_param == "moderate") {
    $sql_filter_field = " 'Moderate' ";
  } elseif ($filter_param == "thai") {
    $sql_filter_field = " 'Thai' ";
  } elseif ($filter_param == "vegan") {
    $sql_filter_field = " 'Vegan' ";
  }
}

$sql_filter_field = " 'Inexpensive' ";
$sql_filter_clause = "WHERE tags.name = " . $sql_filter_field;

// query the database for list of restaurants
$sql_rest_query = $sql_select_clause . $sql_filter_clause;
$restaurants = exec_sql_query($db, $sql_rest_query)->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">


<!-- Will need to eventually turn this into a partial -->

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
    <div class="tags">
      <?php
      foreach ($tags as $tag) {
        $tag_name = $tag["name"];
      ?>
      <a href="/?<?php echo http_build_query(array(
        "filter" => $tag_name
      ))
        <p><?php echo htmlspecialchars($tag_name) ?></p>
      <?php
      }
      ?>

      Filter By:
      <a class

        <!-- American
        Chinese
        Exquisite
        French
        Gluten-Free
        Halal
        Indian
        Inexpensive
        Italian
        Japanese
        Korean
        Kosher
        Mexican
        Moderate
        Thai
        Vegan -->


    </div>

    <div class="catalog">
      <?php

      foreach ($restaurants as $restaurant) {
        $name = $restaurant["name"];
        $address = $restaurant["address"];
        $rating = RATING_STARS[$restaurant["rating"]];
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
