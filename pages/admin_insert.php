<?php

// Checks if user is logged in; implement for every admin page
if (!is_user_logged_in()) {
    // Not logged in - redirect to login page
    header("Location: /login");
    exit;
}

$error_message = "";                    // Initialize error message

if (isset($_POST["add-restaurant"])) {
    // Get the form data
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
                "INSERT INTO restaurants (name, address, rating, avg_price, description, file_ext)
                    VALUES (:name, :address, :rating, :avg_price, :description, :file_ext)",
                array(
                    // Note: UPDATE queries require id field to specify which record to update
                    ":name" => $name,
                    ":address" => $address,
                    ":rating" => $rating,
                    ":avg_price" => $avg_price,
                    ":description" => $description,
                    ":file_ext" => $file_ext
                )
            );

            $restaurant_id = $db->lastInsertId();

            $upload_path = "public/uploads/restaurants/" . $restaurant_id . "." . $file_ext;
            move_uploaded_file($upload_file['tmp_name'], $upload_path);

            header("Location: /admin");
            exit;
        } else {
            $error_message = "Please upload a restaurant image.";
        }
    } catch (PDOException $exception) {
        $error_message = "Failed to add restaurant.";
    }
}

?>
<!DOCTYPE html>
<!-- Handle CSS styling separately for admin pages -->
<html class="admin-page" lang="en">

<!-- Will need to eventually turn this into a partial via meta.php -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Add Restaurant</title>

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

        <a href="/admin"> Return to Admin Homepage</a>

        <div class="catalog">
            <h3>Add New Restaurant</h3>

            <!-- Try/Catch blocks sets the string values of $error_message -->
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

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

                <label for="name">Restaurant Name: </label>
                <input type="text" name="name" id="name">

                <label for="address">Address: </label>
                <input type="text" name="address" id="address">

                <label for="rating">Rating: </label>
                <input type="number" name="rating" id="rating">

                <label for="avg_price">Average Price: </label>
                <input type="number" name="avg_price" id="avg_price">

                <label for="description">Description: </label>
                <!-- Source: Mozilla Reference Documentation -->
                <textarea name="description" id="description" rows="3"></textarea>

                <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
                <label for="restaurant-image">Image: </label>
                <input type="file" name="restaurant-image" id="restaurant-image" accept=".jpeg, .jpg, .png">

                <!-- Will implement form submission functionality in future -->
                <button type="submit" name="add-restaurant">
                    Save Changes
                </button>
            </form>
        </div>
    </main>
</body>

</html>
