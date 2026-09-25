<?php

// Checks if user is logged in; implement for every admin page
if (!is_user_logged_in()) {
    // Not logged in - redirect to login page
    header("Location: /login");
    exit;
}

require_once "includes/restaurant-validation.php";

$error_message = "";                    // Initialize error message

$restaurant_id = $_GET["id"] ?? NULL;   // retrieve query string parameter for the restaurant

// validate form for restaurant deletion
// Handle confirmed deletion request
if (isset($_POST['delete-confirmed'])) {
    $restaurant_id = $_POST['restaurant_id'];

    // Get file extension before deleting from database
    $restaurant = exec_sql_query(
        $db,
        "SELECT file_ext FROM restaurants WHERE id = :id",
        array(':id' => $restaurant_id)
    )->fetch();

    // Delete the restaurant's tags first (foreign key constraint)
    // Required due to foreign key relationship
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

    // Delete associated image file from filesystem
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

// Unknown or missing ID: show the 404 page instead
if (!$restaurant) {
    http_response_code(404);
    require "pages/not-found.php";
    exit;
}

$id = $restaurant["id"]; // added as reference for parameter to be passed

// Get current tags for this restaurant (as IDs, not names)
// Used to pre-select current tags in the dropdown
$sql_current_tags = "SELECT tag_id FROM restaurant_tags WHERE restaurant_id = :id";
$current_tag_records = exec_sql_query($db, $sql_current_tags, array(':id' => $id))->fetchAll();
$current_tag_ids = array_column($current_tag_records, 'tag_id');

// Sticky form values start from the database record
$form = array(
    'name' => $restaurant['name'],
    'address' => $restaurant['address'],
    'rating' => $restaurant['rating'],
    'avg_price' => $restaurant['avg_price'],
    'description' => $restaurant['description']
);

// validate form for editing restaurant
// Process form submission when save button is clicked
if (isset($_POST["edit-restaurant"])) {
    // Get the form data
    foreach (array_keys($form) as $field) {
        $form[$field] = trim($_POST[$field] ?? '');
    }
    // An empty selection means every tag was removed
    $current_tag_ids = $_POST['tags'] ?? array();
    // Handle file upload (optional when editing)
    $upload_file = $_FILES['restaurant-image'] ?? NULL;

    $error_message = validate_restaurant_form($form, $upload_file, FALSE);

    if ($error_message == "") {
        // Keep the current image unless a new one was uploaded
        $new_image = ($upload_file['error'] ?? UPLOAD_ERR_NO_FILE) == UPLOAD_ERR_OK;
        $file_ext = $new_image
            ? strtolower(pathinfo(basename($upload_file['name']), PATHINFO_EXTENSION))
            : $restaurant['file_ext'];

        try {
            // Restaurant details and tags are saved together or not at all
            $db->beginTransaction();

            exec_sql_query(
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
                    ":id" => $id,
                    ":name" => $form['name'],
                    ":address" => $form['address'],
                    ":rating" => $form['rating'],
                    ":avg_price" => $form['avg_price'],
                    ":description" => $form['description'],
                    ":file_ext" => $file_ext
                )
            );

            // Update restaurant-tag associations
            // First, remove all existing tags for this restaurant
            exec_sql_query(
                $db,
                "DELETE FROM restaurant_tags WHERE restaurant_id = :id",
                array(':id' => $id)
            );

            // Then, insert the newly selected tags
            foreach ($current_tag_ids as $tag_id) {
                exec_sql_query(
                    $db,
                    "INSERT INTO restaurant_tags (restaurant_id, tag_id)
                        VALUES (:restaurant_id, :tag_id)",
                    array(
                        ':restaurant_id' => $id,
                        ':tag_id' => $tag_id
                    )
                );
            }

            $db->commit();

            if ($new_image) {
                // Remove the old image if the new one has a different extension
                $old_path = "public/uploads/restaurants/" . $id . "." . $restaurant['file_ext'];
                if ($file_ext != $restaurant['file_ext'] && file_exists($old_path)) {
                    unlink($old_path);
                }

                // Move uploaded file to permanent location, replacing old image
                $upload_path = "public/uploads/restaurants/" . $id . "." . $file_ext;
                move_uploaded_file($upload_file['tmp_name'], $upload_path);
            }

            // Redirect back to edit page to show updated data
            header("Location: /admin/edit?" . http_build_query(array('id' => $id)));
            exit;
        } catch (PDOException $exception) {
            // Database error occurred; names and addresses are UNIQUE in the database
            $db->rollBack();
            $error_message = "Failed to update restaurant. The name or address may already be in the catalog.";
        }
    }
}

// Get all tags for this restaurant
// Fetch tag names for display
$sql_tags_query = "SELECT tags.name
                   FROM tags
                   INNER JOIN restaurant_tags ON tags.id = restaurant_tags.tag_id
                   WHERE restaurant_tags.restaurant_id = :id
                   ORDER BY tags.id";
$restaurant_tags = exec_sql_query($db, $sql_tags_query, array(':id' => $id))->fetchAll();

// Get all existing tags in database
// For populating the multi-select dropdown
$sql_all_tags = "SELECT * FROM tags ORDER BY id";
$all_tags = exec_sql_query($db, $sql_all_tags)->fetchAll();

$edit_url = "/admin/edit?" . http_build_query(array('id' => $id));
$image_hint = "Leave empty to keep the current image";

?>
<!DOCTYPE html>
<!-- Handle CSS styling separately for admin pages -->
<html class="admin-page" lang="en">

<?php $pageTitle = "Edit Restaurant"; include("includes/meta.php") ?>

<body>

    <?php include("includes/admin-header.php") ?>

    <main class="admin">
        <a class="back-link" href="/admin">← Return to Admin Home</a>

        <!-- Two columns: current photo, tags and delete on the left, edit form on the right -->
        <div class="edit-layout">
            <div class="edit-sidebar">
                <!-- Display current restaurant image -->
                <figure class="photo">
                    <img src="/public/uploads/restaurants/<?php echo $id . '.' . htmlspecialchars($restaurant["file_ext"]); ?>" alt="<?php echo htmlspecialchars($restaurant["name"]); ?>" />
                </figure>

                <!-- Display current tags for reference -->
                <section class="card">
                    <h2>Current Cuisine Types</h2>
                    <div class="tag-list">
                        <?php foreach ($restaurant_tags as $tag) { ?>
                            <span class="tag"><?php echo htmlspecialchars($tag["name"]); ?></span>
                        <?php } ?>
                    </div>
                </section>

                <!-- Two-step deletion: first shows confirmation, then executes -->
                <?php if (isset($_GET['delete']) && $_GET['delete'] == 'confirm'): ?>
                    <!-- Confirmation step: are you sure? -->
                    <section class="card confirm">
                        <h2>Are you sure you want to delete this restaurant?</h2>
                        <form method="post">
                            <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($id); ?>">
                            <!-- if yes, trigger actual deletion -->
                            <button class="button primary" type="submit" name="delete-confirmed">Yes, Delete</button>
                            <a class="button" href="<?php echo htmlspecialchars($edit_url); ?>">Cancel</a>
                        </form>
                    </section>
                <?php else: ?>
                    <!-- First step: link to confirmation -->
                    <section class="card">
                        <h2>Delete this restaurant</h2>
                        <p class="muted">Removes the entry, its tags and its photo.</p>
                        <a class="button full" href="<?php echo htmlspecialchars($edit_url . "&delete=confirm"); ?>">Delete Restaurant</a>
                    </section>
                <?php endif; ?>
            </div>

            <div class="card form-card">
                <h1>Edit Restaurant Information</h1>

                <!-- Display error message if update failed -->
                <?php if (!empty($error_message)): ?>
                    <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>

                <!-- Edit form with all restaurant fields pre-populated -->
                <form method="post" enctype="multipart/form-data">

                    <?php include "includes/restaurant-form-fields.php"; ?>

                    <!-- Multi-select for tag management; current tags are pre-selected -->
                    <div class="field">
                        <label for="tags">Tags</label>
                        <select multiple name="tags[]" id="tags">
                            <?php foreach ($all_tags as $tag): ?>
                                <option value="<?php echo htmlspecialchars($tag['id']); ?>" <?php if (in_array($tag['id'], $current_tag_ids)) echo "selected"; ?>>
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="hint">Ctrl/Cmd-click to tag or untag</p>
                    </div>

                    <div class="form-actions">
                        <a class="button" href="/admin">Cancel</a>
                        <button class="button primary" type="submit" name="edit-restaurant">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
