<!-- Partial for generating tiles for each restaurant -->





<div class="tile">
    <?php $file_ext = $restaurant["file_ext"] ?>
    <a href="/reviews?<?php echo http_build_query(array('id' => $id)); ?>">
        <figure>
            <img src="/public/uploads/restaurants/<?php echo $id . '.' . htmlspecialchars($file_ext); ?>" alt="<?php echo htmlspecialchars($name); ?>" />
            <!-- <figcaption>Placeholder Image</figcaption> -->
        </figure>
    </a>

    <div class=tile-text>
        <h3><?php echo htmlspecialchars($name) ?></h3>
        <p>📍<?php echo htmlspecialchars($address) ?></p>
        <p>⭐Rating: <?php echo htmlspecialchars($rating) ?></p>
        <p>💵Avg Price: $<?php echo htmlspecialchars($avg_price) ?></p>
        <p><?php echo htmlspecialchars($description) ?></p>
    </div>
</div>
