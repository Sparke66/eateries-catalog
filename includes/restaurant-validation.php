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

    // Rating must be a whole number from 1 to 5
    $rating_range = array('options' => array('min_range' => 1, 'max_range' => 5));
    if (filter_var($form['rating'], FILTER_VALIDATE_INT, $rating_range) === FALSE) {
        return "Rating must be a whole number between 1 and 5.";
    }

    // Average price is stored as whole dollars
    $price_range = array('options' => array('min_range' => 0));
    if (filter_var($form['avg_price'], FILTER_VALIDATE_INT, $price_range) === FALSE) {
        return "Average price must be a whole number of dollars.";
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
