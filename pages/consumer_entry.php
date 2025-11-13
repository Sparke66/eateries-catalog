<?php

const RATING_STARS = array(
    1 => "★☆☆☆☆",
    2 => "★★☆☆☆",
    3 => "★★★☆☆",
    4 => "★★★★☆",
    5 => "★★★★★"
);

// TODO: change to make the query based on what was passed

// retrieve query string parameter for filtering
$restaurant_id = $_GET["id"] ?? NULL;

// query the database for the restaurant record
$sql_rest_query = "SELECT * FROM restaurants WHERE id = :id";
$restaurant = exec_sql_query($db, $sql_rest_query, array(':id' => $restaurant_id))->fetch();
// TODO: need to ask about this


// Get all tags for this restaurant
$sql_tags_query = "SELECT tags.name
                   FROM tags
                   INNER JOIN restaurant_tags ON tags.id = restaurant_tags.tag_id
                   WHERE restaurant_tags.restaurant_id = :id";
$restaurant_tags = exec_sql_query($db, $sql_tags_query, array(':id' => $restaurant_id))->fetchAll();

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
        <h2>Restaurant Information</h2>

        <div class="catalog">
            <h3>List of Restaurants</h3>
            <?php

            $id = $restaurant["id"]; // added as reference for parameter to be passed
            $name = $restaurant["name"];
            $address = $restaurant["address"];
            $rating = RATING_STARS[$restaurant["rating"]];
            $avg_price = $restaurant["avg_price"];
            $description = $restaurant["description"];
            ?>


            // TODO: change
            <div class="restaurant-details">
                <figure>
                    <img src="/images/placeholder.jpg" alt="Restaurant Image" />
                </figure>
                <h2><?php echo htmlspecialchars($name); ?></h2>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                <p><strong>Rating:</strong> <?php echo htmlspecialchars($rating); ?></p>
                <p><strong>Average Price:</strong> $<?php echo htmlspecialchars($avg_price); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>

                <h3>Cuisine Types:</h3>
                <ul>
                    <?php foreach ($restaurant_tags as $tag) { ?>
                        <li><?php echo htmlspecialchars($tag["name"]); ?></li>
                    <?php } ?>
                </ul>
            </div>

        </div>
    </main>

</body>

</html>
