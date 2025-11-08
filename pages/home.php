<?php

const RATING_STARS = array(
  1 => "★☆☆☆☆",
  2 => "★★☆☆☆",
  3 => "★★★☆☆",
  4 => "★★★★☆",
  5 => "★★★★★"
);

require_once "includes/init.php";

$sql_select_query = "SELECT * FROM restaurants ORDER BY name;";

// query the database
$records = exec_sql_query($db, $sql_select_query)->fetchAll();

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

  <div class="catalog">
    <?php

    foreach ($records as $record) {
      $name = $record["name"];
      $address = $record["address"];
      $rating = RATING_STARS[$record["rating"]];
      $avg_price = $record["avg_price"];
      $description = $record["description"];

      // insert partials for individual eateries
      include "includes/restaurant-tile.php";
    }

    ?>
  </div>

</body>

</html>
