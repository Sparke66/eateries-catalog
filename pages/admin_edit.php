<?php

// Admin Form, do not need key value coding for ratings
/* const RATING_STARS = array(
    1 => "★☆☆☆☆",
    2 => "★★☆☆☆",
    3 => "★★★☆☆",
    4 => "★★★★☆",
    5 => "★★★★★"
); */

// retrieve query string parameter for filtering
$restaurant_id = $_GET["id"] ?? NULL;

// query the database for the restaurant record
$sql_rest_query = "SELECT * FROM restaurants WHERE id = :id";
$restaurant = exec_sql_query($db, $sql_rest_query, array(':id' => $restaurant_id))->fetch();

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

        <div class="catalog">
            <h3>Edit Restaurant Information</h3>
            <?php

            $id = $restaurant["id"]; // added as reference for parameter to be passed
            $name = $restaurant["name"];
            $address = $restaurant["address"];
            $rating = $restaurant["rating"];
            $avg_price = $restaurant["avg_price"];
            $description = $restaurant["description"];
            ?>

            <!-- Will implement image functionality in the future -->
            <figure>
                <img src="/images/placeholder.jpg" alt="Restaurant Image" />
            </figure>

            <!--
            Implement form. Admin should be able to change:
                - Restaurant Name
                - Image
                - Address
                - Rating
                - Average Price
                - Description
            -->
            <!-- Will need to add form functionality in the future -->
            <form>

                <label for="name">Restaurant Name: </label>
                <input type="text" name="name" id="name"
                    value="<?php echo htmlspecialchars($name) ?>">

                <!-- Will implement image upload in the future -->
                <!-- <label for ="image">Image: </label>
                <input type="image" name="image" id="image"
                        value=""> -->

                <label for="address">Address: </label>
                <input type="text" name="address" id="address"
                    value="<?php echo htmlspecialchars($address) ?>">
                <label for="rating">Rating: </label>
                <input type="number" name="rating" id="rating"
                    value="<?php echo htmlspecialchars($rating) ?>">
                <label for="avg_price">Average Price: </label>
                <input type="number" name="avg_price" id="avg_price"
                    value="<?php echo htmlspecialchars($avg_price) ?>">
                <label for="description">Description: </label>

                <!-- Need to explore textarea for longer blocks of text -->
                <input type="text" name="description" id="description"
                    value="<?php echo htmlspecialchars($description) ?>">

                <!-- Will implement form submission functionality in future -->
                <!-- <button type="submit">
                    Save Changes
                </button> -->

            </form>

            <h3>Cuisine Types:</h3>
            <ul>
                <?php foreach ($restaurant_tags as $tag) { ?>
                    <li><?php echo htmlspecialchars($tag["name"]); ?></li>
                <?php } ?>
            </ul>


        </div>
    </main>

</body>

</html>
