<?php

/**
 * Server-side validation shared by the admin insert and edit forms
 * Client-side checks (required, min/max, accept) can be bypassed, so every
 * submission is checked again here before anything is written to the database
 */

// Whitelist of image types and the maximum upload size (1 MB)
const ALLOWED_IMAGE_EXTS = array('jpg', 'jpeg', 'png');
const ALLOWED_IMAGE_MIMES = array('image/jpeg', 'image/png');
const MAX_IMAGE_SIZE = 1_000_000;

/**
 * Validate the restaurant form fields and optional image upload
 * $form: trimmed values for name, address, rating, avg_price, description
 * $upload_file: the entry from $_FILES (or NULL)
 * $image_required: TRUE when adding a restaurant, FALSE when editing
 * Returns the first error message found, or "" if the submission is valid
 */
function validate_restaurant_form($form, $upload_file, $image_required)
{
    if ($form['name'] === '') {
        return "Restaurant name is required.";
    }
    if ($form['address'] === '') {
        return "Address is required.";
    }

    // Rating must be from 0.0 to 10.0 with at most one decimal place
    if (!preg_match('/^\d+(\.\d)?$/', $form['rating']) || $form['rating'] > 10) {
        return "Rating must be between 0.0 and 10.0 with at most one decimal place.";
    }

    // Average price is stored as dollars and cents
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $form['avg_price'])) {
        return "Average price must be a dollar amount with at most two decimal places.";
    }

    // No file chosen: only an error when adding a new restaurant
    $upload_error = $upload_file['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($upload_error == UPLOAD_ERR_NO_FILE) {
        return $image_required ? "Please upload a restaurant image." : "";
    }

    // Files over MAX_FILE_SIZE (form) or upload_max_filesize (php.ini) arrive with an error code, not a size
    if ($upload_error == UPLOAD_ERR_FORM_SIZE || $upload_error == UPLOAD_ERR_INI_SIZE || $upload_file['size'] > MAX_IMAGE_SIZE) {
        return "Image must be 1 MB or smaller.";
    }
    if ($upload_error != UPLOAD_ERR_OK) {
        return "Image upload failed. Please try again.";
    }

    // Whitelist the file extension
    $file_ext = strtolower(pathinfo(basename($upload_file['name']), PATHINFO_EXTENSION));
    if (!in_array($file_ext, ALLOWED_IMAGE_EXTS, TRUE)) {
        return "Image must be a JPG or PNG file.";
    }

    // Verify that the file contents are actually an image, not just renamed
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $upload_file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, ALLOWED_IMAGE_MIMES, TRUE)) {
        return "File is not a valid image.";
    }

    return "";
}

/**
 * Get every tag in the database, grouped by category
 * Returns: array('Cuisine-Type' => array(array('id' => 1, 'name' => 'Italian'), ...), ...)
 */
function get_tag_groups($db)
{
    $sql_all_tags = "SELECT category, id, name FROM tags ORDER BY id";
    return exec_sql_query($db, $sql_all_tags)->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
}

/**
 * Validate the tag IDs submitted from the multi-select
 * SQLite does not enforce foreign keys here, so a forged tag ID would be stored as-is
 * $tag_ids: the raw $_POST['tags'] array (strings), possibly empty
 * $tag_groups: all tags grouped by category, from get_tag_groups()
 * Returns the first error message found, or "" if the selection is valid
 */
function validate_restaurant_tags($tag_ids, $tag_groups)
{
    // Tags are optional, but a forged form could send a single value instead of a list
    if (!is_array($tag_ids)) {
        return "Invalid tag selection.";
    }

    // The browser never sends the same option twice, so a repeat means a forged form
    if (count($tag_ids) != count(array_unique($tag_ids))) {
        return "Each tag can only be selected once.";
    }

    // A restaurant can't be both Inexpensive and Exquisite; cuisines and diets can combine
    $single_choice_categories = array('Affordability');

    $known_count = 0;
    foreach ($tag_groups as $category => $tags) {
        // Submitted IDs that belong to this category
        $picked = array_intersect($tag_ids, array_column($tags, 'id'));
        if (in_array($category, $single_choice_categories, TRUE) && count($picked) > 1) {
            return "Choose at most one " . strtolower($category) . " tag.";
        }
        $known_count += count($picked);
    }

    // Any ID left over did not match a category, so it isn't in the tags table
    if ($known_count != count($tag_ids)) {
        return "One or more selected tags do not exist.";
    }

    return "";
}
