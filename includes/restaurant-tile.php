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
        </figure>
    </a>

    <div class="tile-text">
        <!-- Restaurant name as heading -->
        <h3><?php echo htmlspecialchars($name) ?></h3>
        <!-- Location -->
        <p class="muted">Address · <?php echo htmlspecialchars($address) ?></p>
        <!-- Rating out of 5 and average price per meal -->
        <p>
            Rating <?php echo htmlspecialchars($rating) ?>/5
            <span class="spaced">Avg price $<?php echo htmlspecialchars($avg_price) ?></span>
        </p>
        <!-- Brief description -->
        <p class="description"><?php echo htmlspecialchars($description) ?></p>
    </div>
</div>
