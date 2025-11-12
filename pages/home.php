<?php

const RATING_STARS = array(
  1 => "★☆☆☆☆",
  2 => "★★☆☆☆",
  3 => "★★★☆☆",
  4 => "★★★★☆",
  5 => "★★★★★"
);

require_once "includes/init.php";

// query the database for list of tags
$sql_tag_query = "SELECT * FROM tags ORDER BY name;";
$tags = exec_sql_query($db, $sql_tag_query)->fetchAll();

// query the database for list of restaurants
$sql_rest_query = "SELECT * FROM restaurants ORDER BY name;";
$restaurants = exec_sql_query($db, $sql_rest_query)->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Ithaca Eateries Catalog</title>
</head>

<body>

  <h1>Ithaca Eateries Catalog</h1>

  <!-- Display the list of tags; may need to port over to partial -->
  <div class="tags">
    <?php
    foreach ($tags as $tag) {
      $tag_name = $tag["name"];

    ?>
      <p><?php echo htmlspecialchars($tag_name) ?></p>
    <?php
    }
    ?>


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

</body>

</html>
