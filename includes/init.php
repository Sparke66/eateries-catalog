<?php

/**
 * Initialization script
 * This file initializes the database connection and session handling
 * It should be included at the beginning of every page
 */

// initialize and open database
require_once "includes/db.php";
// Connect to SQLite database, creating it from init.sql if it doesn't exist
$db = init_sqlite_db("db/site.sqlite", "db/init.sql");

// Initialize session management for user authentication
require_once "includes/sessions.php";
$session_messages = array();
// Process any login/logout requests or restore user session from cookie
process_session_params($db, $session_messages);
