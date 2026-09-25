<?php

/**
 * Admin Entry Page - Add New Restaurant
 * Allows administrators to insert new restaurants into the database
 * Handles form submission, image upload, and database insertion
 */

// Checks if user is logged in; implement for every admin page
if (!is_user_logged_in()) {
    // Not logged in - redirect to login page
    header("Location: /login");
    exit;
}

require_once "includes/restaurant-validation.php";

// Initialize error message for form validation
$error_message = "";

// Sticky form values; refilled from the submission so nothing is lost on error
$form = array(
    'name' => '',
    'address' => '',
    'rating' => '',
    'avg_price' => '',
    'description' => ''
);

// Process form submission when "add-restaurant" button is clicked
if (isset($_POST["add-restaurant"])) {
    // Get the form data
    foreach (array_keys($form) as $field) {
        $form[$field] = trim($_POST[$field] ?? '');
    }
    // Handle file upload
    $upload_file = $_FILES['restaurant-image'] ?? NULL;

    // Nothing is inserted unless every field and the image pass validation
    $error_message = validate_restaurant_form($form, $upload_file, TRUE);

    if ($error_message == "") {
        $file_ext = strtolower(pathinfo(basename($upload_file['name']), PATHINFO_EXTENSION));

        try {
            // Insert new restaurant record into database
            exec_sql_query(
                $db,
                "INSERT INTO restaurants (name, address, rating, avg_price, description, file_ext)
                    VALUES (:name, :address, :rating, :avg_price, :description, :file_ext)",
                array(
                    ":name" => $form['name'],
                    ":address" => $form['address'],
                    ":rating" => $form['rating'],
                    ":avg_price" => $form['avg_price'],
                    ":description" => $form['description'],
                    ":file_ext" => $file_ext
                )
            );

            // Get the auto-generated ID of the newly inserted restaurant
            $restaurant_id = $db->lastInsertId();

            // Move uploaded file to permanent location with restaurant ID as filename
            $upload_path = "public/uploads/restaurants/" . $restaurant_id . "." . $file_ext;
            move_uploaded_file($upload_file['tmp_name'], $upload_path);

            // Success - redirect to admin home
            header("Location: /admin");
            exit;
        } catch (PDOException $exception) {
            // Database error occurred; names and addresses are UNIQUE in the database
            $error_message = "Failed to add restaurant. The name or address may already be in the catalog.";
        }
    }
}

?>
<!DOCTYPE html>
<!-- Handle CSS styling separately for admin pages -->
<html class="admin-page" lang="en">

<?php $pageTitle = "Add Restaurant"; include("includes/meta.php") ?>

<body>

    <?php include("includes/admin-header.php") ?>

    <main class="admin narrow">
        <a class="back-link" href="/admin">← Return to Admin Home</a>

        <div class="card form-card">
            <h1>Add New Restaurant</h1>

            <!-- Display error message if form submission failed -->
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

            <!-- Form for adding new restaurant entry -->
            <form method="post" enctype="multipart/form-data">

                <?php include "includes/restaurant-form-fields.php"; ?>

                <div class="form-actions">
                    <a class="button" href="/admin">Cancel</a>
                    <button class="button primary" type="submit" name="add-restaurant">Add Restaurant</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
