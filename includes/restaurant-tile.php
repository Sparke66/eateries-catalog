<!-- Partial for generating tiles for each restaurant -->
<!-- Consumer view: displays restaurant information in a card format -->
<!-- Expects variables: $restaurant, $id, $name, $address, $rating, $avg_price, $description -->

<div class="tile">
    <?php $file_ext = $restaurant["file_ext"] ?>
    <!-- Link to detailed reviews page -->
    <a href="/reviews?<?php echo http_build_query(array('id' => $id)); ?>">
        <figure>
            <!-- Display restaurant image from uploads directory -->
            <img src="/public/uploads/restaurants/<?php echo $id . '.' . htmlspecialchars($file_ext); ?>" alt="<?php echo htmlspecialchars($name); ?>" />
            <!-- <figcaption>Placeholder Image</figcaption> -->
        </figure>
    </a>

    <div class=tile-text>
        <!-- Restaurant name as heading -->
        <h3><?php echo htmlspecialchars($name) ?></h3>
        <!-- Location with emoji icon -->
        <p>📍<?php echo htmlspecialchars($address) ?></p>
        <!-- Star rating -->
        <p>⭐Rating: <?php echo htmlspecialchars($rating) ?></p>
        <!-- Average price per meal -->
        <p>💵Avg Price: $<?php echo htmlspecialchars($avg_price) ?></p>
        <!-- Brief description -->
        <p><?php echo htmlspecialchars($description) ?></p>
    </div>
</div>
