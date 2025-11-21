<?php

// Checks if user is logged in; implement for every admin page
if (!is_user_logged_in()) {
    // Not logged in - redirect to login page
    header("Location: /login");
    exit;
}

$error_message = "";                    // Initialize error message

$restaurant_id = $_GET["id"] ?? NULL;   // retrieve query string parameter for filtering

// validate form for editing restaurant
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

        // validate form for editing tags
        if (isset($_POST['tags'])) {
            // First, remove all existing tags for this restaurant
            $result = exec_sql_query(
                $db,
                "DELETE FROM restaurant_tags
                    WHERE restaurant_id = :id",
                array(':id' => $restaurant_id)
            );

            // Then, insert the newly selected tags
            foreach ($_POST['tags'] as $tag_id) {
                $result = exec_sql_query(
                    $db,
                    "INSERT INTO restaurant_tags (restaurant_id, tag_id)
                        VALUES (:restaurant_id, :tag_id)",
                    array(
                        ':restaurant_id' => $restaurant_id,
                        ':tag_id' => $tag_id
                    )
                );
            }
        }




        header("Location: /admin/edit?id=" . $restaurant_id);
        exit;
    } catch (PDOException $exception) {
        $error_message = "Failed to update restaurant.";
    }
}



// validate form for restaurant deletion
if (isset($_POST['delete-confirmed'])) {
    $restaurant_id = $_POST['restaurant_id'];

    // Optional: Delete the image file
    $restaurant = exec_sql_query(
        $db,
        "SELECT file_ext FROM restaurants WHERE id = :id",
        array(':id' => $restaurant_id)
    )->fetch();

    // Delete the restaurant's tags first (foreign key constraint)
    exec_sql_query(
        $db,
        "DELETE FROM restaurant_tags WHERE restaurant_id = :id",
        array(':id' => $restaurant_id)
    );

    // Delete the restaurant
    exec_sql_query(
        $db,
        "DELETE FROM restaurants WHERE id = :id",
        array(':id' => $restaurant_id)
    );

    if ($restaurant) {
        $image_path = "public/uploads/restaurants/" . $restaurant_id . "." . $restaurant['file_ext'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Redirect to admin home
    header("Location: /admin");
    exit;
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

// Get all existing tags in database
$sql_all_tags = "SELECT * FROM tags ORDER BY name";
$all_tags = exec_sql_query($db, $sql_all_tags)->fetchAll();

// Get current tags for this restaurant (as IDs, not names)
$sql_current_tags = "SELECT tag_id FROM restaurant_tags WHERE restaurant_id = :id";
$current_tag_records = exec_sql_query($db, $sql_current_tags, array(':id' => $restaurant_id))->fetchAll();

// Extract just the tag IDs into an array for easy checking
$current_tag_ids = array_column($current_tag_records, 'tag_id');


?>
<!DOCTYPE html>
<!-- Handle CSS styling separately for admin pages -->
<html class="admin-page" lang="en">

<!-- Will need to eventually turn this into a partial via meta.php -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Restaurant</title>

    <link rel="stylesheet" type="text/css" href="/styles/site.css">
</head>

<?php include("includes/meta.php") ?>

<body>

    <main class="admin">
        <!-- Administrator Page shall be implemented for wide screen -->

        <h1>Ithaca Eateries Catalog</h1>

        <h2>Administrator Portal</h2>

        <div class="admin-login">
            <?php if (is_user_logged_in()): ?>
                <!-- User IS logged in - show logout -->
                <form method="POST">
                    <button type="submit" name="logout">Logout</button>
                </form>
                <!-- Note: User redirected to login page if not logged in
                    Login form not implemented. -->
            <?php endif; ?>
        </div>

        <p> Return to admin view page:</p>
        <a href="/admin"> Return Admin Home</a>

        <div class="catalog">
            <h3>Edit Restaurant Information</h3>

            <!-- Try/Catch blocks sets the string values of $error_message -->
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

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

                <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
                <label for="restaurant-image">Image: </label>
                <input type="file" name="restaurant-image" id="restaurant-image" accept=".jpeg, .jpg, .png">

                <!-- Editing tags will require list of all tags -->
                <label for="tags">Tags:</label>
                <select multiple name="tags[]" id="tags">
                    <?php foreach ($all_tags as $tag): ?>
                        <option value="<?php echo $tag['id']; ?>"
                            <?php if (in_array($tag['id'], $current_tag_ids)): ?>
                            selected
                            <?php endif; ?>>
                            <?php echo htmlspecialchars($tag['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="edit-restaurant">
                    Save Changes
                </button>

            </form>

            <h3>Current Cuisine Types:</h3>
            <ul>
                <?php foreach ($restaurant_tags as $tag) { ?>
                    <li><?php echo htmlspecialchars($tag["name"]); ?></li>
                <?php } ?>
            </ul>

            <!-- Validate form for entry deletion confirmation -->
            <?php if (isset($_GET['delete']) && $_GET['delete'] == 'confirm'): ?>
                <p>Are you sure you want to delete this restaurant?</p>
                <form method="POST">
                    <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($id); ?>">
                    <!-- if yes, trigger actual deletion -->
                    <button type="submit" name="delete-confirmed">Yes, Delete</button>
                    <a href="/admin/edit?id=<?php echo $id; ?>">Cancel</a>
                </form>
            <?php else: ?>
                <!-- Show initial delete button -->
                <a href="/admin/edit?id=<?php echo $id; ?>&delete=confirm">
                    <button type="button">Delete Restaurant</button>
                </a>
            <?php endif; ?>


        </div>
    </main>
</body>

</html>
