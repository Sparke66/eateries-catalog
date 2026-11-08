<?php

/**
 * Consumer Entry Page - Detailed Restaurant View
 * Displays comprehensive information about a single restaurant
 * Shows tags, ratings, description, and other details
 * Lists user reviews and lets logged-in users post one review per restaurant
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

// Get all tags for this restaurant, grouped by category
// Join with tags table to get tag names
$sql_tags_query = "SELECT tags.category, tags.name
                   FROM tags
                   INNER JOIN restaurant_tags ON tags.id = restaurant_tags.tag_id
                   WHERE restaurant_tags.restaurant_id = :id
                   ORDER BY tags.id";
$restaurant_tags = exec_sql_query($db, $sql_tags_query, array(':id' => $restaurant_id))->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);

// Extract restaurant details from database record
$id = $restaurant["id"]; // added as reference for parameter to be passed
$name = $restaurant["name"];
$address = $restaurant["address"];
$rating = $restaurant["rating"];
$avg_price = $restaurant["avg_price"];
$description = $restaurant["description"];
$file_ext = $restaurant["file_ext"];

// Longest review body accepted, in characters
const REVIEW_MAX_LENGTH = 2000;

$review_errors = array();

// Sticky review form values; refilled from the submission so nothing is lost on error
$review_form = array(
    'rating' => '',
    'body' => ''
);

// Process review submission when "add-review" button is clicked
if (isset($_POST["add-review"])) {
    // Ignore tampered fields (e.g. arrays) rather than erroring on them
    foreach (array_keys($review_form) as $field) {
        $review_form[$field] = is_string($_POST[$field] ?? NULL) ? trim($_POST[$field]) : '';
    }

    if (!is_user_logged_in()) {
        array_push($review_errors, "Sign in to leave a review.");
    } else if (!csrf_token_valid()) {
        array_push($review_errors, "Your form expired. Please submit it again.");
    } else {
        // Rating must be from 0.0 to 10.0 with at most one decimal place, same as the critic rating
        if (!preg_match('/^\d+(\.\d)?$/', $review_form['rating']) || $review_form['rating'] > 10) {
            array_push($review_errors, "Rating must be between 0.0 and 10.0 with at most one decimal place.");
        }
        if ($review_form['body'] === '') {
            array_push($review_errors, "Write a few words about your visit.");
        } else if (mb_strlen($review_form['body']) > REVIEW_MAX_LENGTH) {
            array_push($review_errors, "Reviews can be at most " . REVIEW_MAX_LENGTH . " characters long.");
        }
    }

    if (empty($review_errors)) {
        try {
            // UNIQUE(restaurant_id, user_id) allows only one review per user per restaurant
            exec_sql_query(
                $db,
                "INSERT INTO reviews (restaurant_id, user_id, rating, body)
                    VALUES (:restaurant_id, :user_id, :rating, :body)",
                array(
                    ":restaurant_id" => $id,
                    ":user_id" => current_user()["id"],
                    ":rating" => (float)$review_form['rating'],
                    ":body" => $review_form['body']
                )
            );

            // Success - reload with GET so refreshing doesn't resubmit the form
            header("Location: /reviews?" . http_build_query(array("id" => $id)) . "#reviews");
            exit;
        } catch (PDOException $exception) {
            // 23000 = integrity constraint violation; anything else is a real server error
            if ($exception->getCode() != '23000') {
                throw $exception;
            }
            array_push($review_errors, "You've already reviewed this restaurant.");
        }
    }
}

// Get all reviews for this restaurant, newest first
$sql_reviews_query = "SELECT reviews.user_id, reviews.rating, reviews.body, reviews.created_at, users.username
                      FROM reviews
                      INNER JOIN users ON reviews.user_id = users.id
                      WHERE reviews.restaurant_id = :id
                      ORDER BY reviews.created_at DESC, reviews.id DESC";
$reviews = exec_sql_query($db, $sql_reviews_query, array(':id' => $id))->fetchAll();

// User rating: average of all review ratings (NULL when there are none)
$user_rating = $reviews ? array_sum(array_column($reviews, "rating")) / count($reviews) : NULL;

// Find the current user's review, if any; they may only post one
$user_review = NULL;
if (is_user_logged_in()) {
    foreach ($reviews as $review) {
        if ($review["user_id"] == current_user()["id"]) {
            $user_review = $review;
        }
    }
}

// Hidden form field value; fetched before output because it may set a cookie
$csrf_token = (is_user_logged_in() && !$user_review) ? csrf_token() : NULL;

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
                        <dt>Critic rating</dt>
                        <dd><?php echo number_format($rating, 1); ?> / 10</dd>
                    </div>
                    <div class="card">
                        <dt>User rating</dt>
                        <dd><?php echo $user_rating === NULL ? "No reviews yet" : number_format($user_rating, 1) . " / 10"; ?></dd>
                    </div>
                    <div class="card">
                        <dt>Average price</dt>
                        <dd>$<?php echo number_format($avg_price, 2); ?></dd>
                    </div>
                    <div class="card">
                        <dt>Address</dt>
                        <dd><?php echo htmlspecialchars($address); ?></dd>
                    </div>
                </dl>

                <h2>Description</h2>
                <p><?php echo htmlspecialchars($description); ?></p>

                <!-- Tags listed by category; each tag links back to the catalog filtered by that tag -->
                <?php foreach ($restaurant_tags as $category => $category_tags) { ?>
                    <h2><?php echo htmlspecialchars($category); ?></h2>
                    <div class="tag-list">
                        <?php foreach ($category_tags as $tag) { ?>
                            <a class="tag" href="/?<?php echo http_build_query(array("filter" => $tag["name"])); ?>"><?php echo htmlspecialchars($tag["name"]); ?></a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- User reviews, then the form (or a note on why it isn't shown) -->
        <section class="reviews" id="reviews">
            <h2>Reviews</h2>

            <?php if (empty($reviews)) { ?>
                <p class="muted">No reviews yet.</p>
            <?php } else { ?>
                <ul class="review-list">
                    <?php foreach ($reviews as $review) { ?>
                        <li class="card">
                            <p class="review-meta">
                                <strong><?php echo htmlspecialchars($review["username"]); ?></strong>
                                · <?php echo number_format($review["rating"], 1); ?> / 10
                                · <time datetime="<?php echo htmlspecialchars($review["created_at"]); ?>"><?php echo htmlspecialchars(date("M j, Y", strtotime($review["created_at"] . " UTC"))); ?></time>
                            </p>
                            <p><?php echo nl2br(htmlspecialchars($review["body"])); ?></p>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>

            <?php if (!is_user_logged_in()) { ?>
                <p><a href="/login">Sign in</a> or <a href="/register">register</a> to leave a review.</p>
            <?php } else if ($user_review) { ?>
                <p class="muted">You've reviewed this restaurant.</p>
            <?php } else { ?>
                <div class="card form-card">
                    <h3>Leave a review</h3>

                    <!-- Display error messages if the review failed validation -->
                    <?php foreach ($review_errors as $message) { ?>
                        <p class="error"><?php echo htmlspecialchars($message); ?></p>
                    <?php } ?>

                    <form action="/reviews?<?php echo http_build_query(array("id" => $id)); ?>#reviews" method="post" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>" />

                        <div class="field">
                            <label for="review-rating">Rating</label>
                            <input type="number" id="review-rating" name="rating" min="0" max="10" step="0.1" value="<?php echo htmlspecialchars($review_form['rating']); ?>" required>
                        </div>

                        <div class="field">
                            <label for="review-body">Review</label>
                            <textarea id="review-body" name="body" rows="5" maxlength="<?php echo REVIEW_MAX_LENGTH; ?>" aria-describedby="review-body-hint" required><?php echo htmlspecialchars($review_form['body']); ?></textarea>
                            <p class="hint" id="review-body-hint">Up to <?php echo REVIEW_MAX_LENGTH; ?> characters</p>
                        </div>

                        <div class="form-actions">
                            <button class="button primary" type="submit" name="add-review">Post Review</button>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </section>
    </main>

</body>

</html>
