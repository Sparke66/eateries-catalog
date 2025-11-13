<?php

const RATING_STARS = array(
    1 => "★☆☆☆☆",
    2 => "★★☆☆☆",
    3 => "★★★☆☆",
    4 => "★★★★☆",
    5 => "★★★★★"
);

// retrieve query string parameter for filtering
$filter_param = $_GET["filter"] ?? NULL;

// query the database for list of tags
$sql_tag_query = "SELECT * FROM tags ORDER BY name;";
$tags = exec_sql_query($db, $sql_tag_query)->fetchAll();

// building the restaurant query
// is this even the proper way?
$sql_select_clause = "SELECT restaurants.*
FROM restaurants
INNER JOIN restaurant_tags ON (restaurants.id = restaurant_tags.restaurant_id)
INNER JOIN tags ON (restaurant_tags.tag_id = tags.id)";
$sql_filter_clause = ""; // No filter by default


// note we can use the tag names directly for the filter parameters
if ($filter_param) {
    $sql_filter_clause = " WHERE tags.name = '" . $filter_param . "'";
}

// query the database for list of restaurants
$sql_rest_query = $sql_select_clause . $sql_filter_clause;
$restaurants = exec_sql_query($db, $sql_rest_query)->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<!-- Will need to eventually turn this into a partial via meta.php -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Administrator Portal</title>

    <link rel="stylesheet" type="text/css" href="/styles/site.css">
</head>

<?php include("includes/meta.php") ?>

<body>

    <main class="admin">
        <!-- Administrator Page shall be implemented for wide screen -->

        <h1>Ithaca Eateries Catalog</h1>
        <h2>Administrator Portal</h2>

        <!-- Display the list of tags; may need to port over to partial -->
        <div class="tags">
            <p> Select Cuisine Type: </p>
            <?php
            foreach ($tags as $tag) {
                $tag_name = $tag["name"];
            ?>
                <a href="/?<?php echo http_build_query(array(
                                "filter" => $tag_name
                            )); ?>">
                    <p class="tag"><?php echo htmlspecialchars($tag_name) ?></p>
                </a>
            <?php
            }
            ?>

        </div>

        <div class="catalog">
            <h3>List of Restaurants</h3>
            <?php

            foreach ($restaurants as $restaurant) {
                $id = $restaurant["id"]; // added as reference for parameter to be passed
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
