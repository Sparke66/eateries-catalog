<?php

/**
 * Consumer Entry Page - Detailed Restaurant View
 * Displays comprehensive information about a single restaurant
 * Shows tags, ratings, description, and other details
 */

// Get restaurant ID from URL parameter
$restaurant_id = $_GET["id"] ?? NULL;

// query the database for the restaurant record
$sql_rest_query = "SELECT * FROM restaurants WHERE id = :id";
$restaurant = exec_sql_query($db, $sql_rest_query, array(':id' => $restaurant_id))->fetch();

// Unknown or missing ID: show the 404 page instead
if (!$restaurant) {
    http_response_code(404);
    require "pages/not-found.php";
    exit;
}

// Get all tags for this restaurant
// Join with tags table to get tag names
$sql_tags_query = "SELECT tags.name
                   FROM tags
                   INNER JOIN restaurant_tags ON tags.id = restaurant_tags.tag_id
                   WHERE restaurant_tags.restaurant_id = :id
                   ORDER BY tags.id";
$restaurant_tags = exec_sql_query($db, $sql_tags_query, array(':id' => $restaurant_id))->fetchAll();

// Extract restaurant details from database record
$id = $restaurant["id"]; // added as reference for parameter to be passed
$name = $restaurant["name"];
$address = $restaurant["address"];
$rating = $restaurant["rating"];
$avg_price = $restaurant["avg_price"];
$description = $restaurant["description"];
$file_ext = $restaurant["file_ext"];

?>
<!DOCTYPE html>
<html lang="en">

<?php $pageTitle = $name . " | Ithaca Eateries Catalog"; include("includes/meta.php") ?>

<body>

    <?php include("includes/header.php") ?>

    <main class="consumer">
        <a class="back-link" href="/">← Return to all restaurants</a>

        <!-- Two columns: photo on the left, details on the right -->
        <div class="entry">
            <figure class="photo">
                <img src="/public/uploads/restaurants/<?php echo $id . '.' . htmlspecialchars($file_ext); ?>" alt="<?php echo htmlspecialchars($name); ?>" />
            </figure>

            <div class="entry-info">
                <p class="eyebrow">Restaurant Information</p>
                <h1><?php echo htmlspecialchars($name); ?></h1>

                <!-- Key facts shown as small cards -->
                <dl class="stats">
                    <div class="card">
                        <dt>Rating</dt>
                        <dd><?php echo htmlspecialchars($rating); ?> / 5</dd>
                    </div>
                    <div class="card">
                        <dt>Average price</dt>
                        <dd>$<?php echo htmlspecialchars($avg_price); ?></dd>
                    </div>
                    <div class="card">
                        <dt>Address</dt>
                        <dd><?php echo htmlspecialchars($address); ?></dd>
                    </div>
                </dl>

                <h2>Description</h2>
                <p><?php echo htmlspecialchars($description); ?></p>

                <!-- Each tag links back to the catalog filtered by that tag -->
                <h2>Cuisine Types</h2>
                <div class="tag-list">
                    <?php foreach ($restaurant_tags as $tag) { ?>
                        <a class="tag" href="/?<?php echo http_build_query(array("filter" => $tag["name"])); ?>"><?php echo htmlspecialchars($tag["name"]); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
