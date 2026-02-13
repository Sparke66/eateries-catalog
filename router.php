<?php

/**
 * Main Router
 * Handles all incoming HTTP requests and routes them to appropriate page files
 * Manages static file serving, error handling, and 404 pages
 */

// return 500 server error for unhandled exceptions
// Global exception handler to prevent PHP errors from exposing sensitive information
set_exception_handler(function ($ex) {
  // try to purge content sent so far
  // Clear any output buffer to ensure clean error page
  ob_end_clean();

  // return 500 server error for unhandled exceptions
  http_response_code(500);
  echo "<!DOCTYPE html>
        <html>
        <head>
          <title>500 Internal Server Error</title>
        </head>
        <body>
          <h1>500 Internal Server Error</h1>
          <p>Sorry, something went wrong on the server.</p>
        </body>
        </html>";

  throw $ex;
});

/**
 * Match static file requests
 * Checks if URI points to a static resource (CSS, JS, images, etc.)
 * Returns file path if found, NULL otherwise
 */
function match_static($uri)
{
  // Match route as is; match "/public"
  // Check if request explicitly includes /public/ prefix
  if (preg_match("/^\/public\//", $uri) && file_exists("." . $uri)) {
    return $uri;
  }

  // Look for static resource in public folder;
  // match /favicon.ico, etc.
  // Try prepending /public/ to find file
  $public_path = "./public" . $uri;
  if (file_exists($public_path)) {
    return $public_path;
  }
  return NULL;
}

/**
 * Match dynamic page routes
 * Looks up URI in routes array to find corresponding PHP page file
 * Returns page file path if route exists, NULL otherwise
 */
function match_routes($uri, $routes)
{
  // If the URI ends with /, remove it
  // Normalize URIs by removing trailing slashes
  if (preg_match("/^\/.+\/$/", $uri)) {
    $uri = preg_replace("/\/$/", "", $uri);
  }

  // Look up URI in routes array
  if (array_key_exists($uri, $routes)) {
    return $routes[$uri];
  } else {
    return NULL;
  }
}

/**
 * Determine MIME type for a file
 * Uses file extension mapping for common web files
 * Falls back to libmagic for unknown extensions
 */
function mime_type($filename)
{
  // PHP: libmagic cannot accurately determine the MIME type of a file for common web files.
  // Use the file extension for common web files.
  $mime_types = array(
    "txt" => "text/plain",
    "html" => "text/html",
    "css" => "text/css",
    "js" => "application/javascript",
    "json" => "application/json",
  );

  $ext = pathinfo($filename, PATHINFO_EXTENSION);
  if (array_key_exists($ext, $mime_types)) {
    return $mime_types[$ext];
  } else {
    // Fall back to libmagic for unknown file types
    $finfo = finfo_open(FILEINFO_MIME);
    $mimetype = finfo_file($finfo, $filename);
    finfo_close($finfo);
    return $mimetype;
  }
}

// Routes mapping: URI paths to their corresponding PHP page files
const ROUTES = array(
  "/"            => "pages/home.php",           // consumer view all / filter by tag
  "/reviews"     => "pages/consumer_entry.php", // consumer entry details
  "/admin"       => "pages/admin_view.php",     // admin view all / filter by tag
  "/admin/entry" => "pages/admin_insert.php",   // admin insert entry
  "/admin/edit"  => "pages/admin_edit.php",     // admin edit entry / tag / untag
  "/login"       => "pages/login.php"           // login
);

// Grabs the URI and separates it from query string parameters
// Main routing logic starts here
error_log("");
error_log("HTTP Request: " . $_SERVER["REQUEST_URI"]);
// Extract just the path portion, ignoring query string
$request_uri = explode("?", $_SERVER["REQUEST_URI"], 2)[0];

// Try to match request to a dynamic route
if ($php_file = match_routes($request_uri, ROUTES)) {
  // Include PHP file from route look-up
  // Initialize database and session, then render the page
  require_once "includes/init.php";
  require $php_file;
  // Try to match request to a static file
} else if ($file_path = match_static($request_uri)) {
  if ($file_path == $request_uri) {
    // let the web server respond for static resources
    // Return false to let built-in PHP server handle /public/ files
    return False;
  } else {
    // Serve up file from public folder
    // Manually serve file with correct MIME type
    header("Content-Type: " . mime_type($file_path));
    readfile($file_path);
  }
} else {
  // No route or static file matched - show 404
  error_log("  404 Not Found: " . $request_uri);
  http_response_code(404);
  require "includes/init.php";
  require "pages/not-found.php";
}
