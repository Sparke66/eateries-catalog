<?php

// NOTE: do not need to implement form validation, sticky forms, and corrective feedback
// DO NEED to display error message if insert/update query fails


$error_message = "";                    // Initialize error message
$restaurant_id = $_GET["id"] ?? NULL;   // retrieve query string parameter for filtering

if (isset($_POST["edit-restaurant"])) {
    // Get the form data
    $restaurant_id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $rating = $_POST['rating'];
    $avg_price = $_POST['avg_price'];
    $description = $_POST['description'];
    // Handle file upload
    $upload_file = $_FILES['restaurant-image'];

    try {
        // If updating with new image
        if ($upload_file["error"] == UPLOAD_ERR_OK) {
            // Extract file information
            $file_name = basename($upload_file['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $result = exec_sql_query(
                $db,
                "UPDATE restaurants SET name = :name,
                                    address = :address,
                                    rating = :rating,
                                    avg_price = :avg_price,
                                    description = :description,
                                    file_ext = :file_ext
                                WHERE id = :id;",
                array(
                    // Note: UPDATE queries require id field to specify which record to update
                    ":id" => $restaurant_id,
                    ":name" => $name,
                    ":address" => $address,
                    ":rating" => $rating,
                    ":avg_price" => $avg_price,
                    ":description" => $description,
                    ":file_ext" => $file_ext
                )
            );

            $upload_path = "public/uploads/restaurants/" . $restaurant_id . "." . $file_ext;
            move_uploaded_file($upload_file['tmp_name'], $upload_path);
        }


        // If not updating with new image
        else {
            $result = exec_sql_query(
                $db,
                "UPDATE restaurants SET name = :name,
                                    address = :address,
                                    rating = :rating,
                                    avg_price = :avg_price,
                                    description = :description
                                WHERE id = :id;",
                array(
                    // Note: UPDATE queries require id field to specify which record to update
                    ":id" => $restaurant_id,
                    ":name" => $name,
                    ":address" => $address,
                    ":rating" => $rating,
                    ":avg_price" => $avg_price,
                    ":description" => $description
                )
            );
        }

        header("Location: /admin");
        exit;
    } catch (PDOException $exception) {
        $error_message = "Failed to update restaurant. Please try again.";
    }
}

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

            <?php $file_ext = $restaurant["file_ext"] ?>
            <figure>
                <img src="/public/uploads/restaurants/<?php echo $id . '.' . htmlspecialchars($file_ext); ?>" alt="<?php echo htmlspecialchars($name); ?>" />
                <figcaption>Placeholder Image</figcaption>
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
            <form method="post" enctype="multipart/form-data">
                <!-- restaurant ID field is hidden -->
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                <label for="name">Restaurant Name: </label>
                <input type="text" name="name" id="name"
                    value="<?php echo htmlspecialchars($name) ?>">

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
                <!-- Source: Mozilla Reference Documentation -->
                <textarea name="description" id="description" rows="3"> <?php echo htmlspecialchars($description) ?> </textarea>

                <label for="restaurant-image">Image: </label>
                <input type="file" name="restaurant-image" id="restaurant-image" accept=".jpeg, .jpg, .png">
                <p>uploading new image is optional</p>

                <!-- Will implement form submission functionality in future -->
                <button type="submit" name="edit-restaurant">
                    Save Changes
                </button>

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
