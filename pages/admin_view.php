<?php

const RATING_STARS = array(
    1 => "★☆☆☆☆",
    2 => "★★☆☆☆",
    3 => "★★★☆☆",
    4 => "★★★★☆",
    5 => "★★★★★"
);

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

    <title>Administrator Portal</title>
</head>

<body>

    <!-- Administrator Page shall be implemented for wide screen -->

    <h1>Ithaca Eateries Catalog</h1>
    <h2>Administrator Portal</h2>

    <!-- Display the list of tags; may need to port over to partial -->
    <div class="tags">
        <h3>Categories</h3>
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
        <h3>List of Restaurants</h3>
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
