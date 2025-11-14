<!-- Partial for generating tiles for each restaurant -->
<a href="/reviews?<?php echo http_build_query(array('id' => $id)); ?>">
    <div class="tile">
        <?php $file_ext = $restaurant["file_ext"]?>
        <figure>
            <img src="/public/uploads/restaurants/<?php echo $id . '.' . htmlspecialchars($file_ext); ?>" alt="<?php echo htmlspecialchars($name); ?>" />
            <figcaption>Placeholder Image</figcaption>
        </figure>
        <h3><?php echo htmlspecialchars($name) ?></h3>
        <p><?php echo htmlspecialchars($address) ?></p>
        <p><?php echo htmlspecialchars($rating) ?></p>
        <p>Average Price: $<?php echo htmlspecialchars($avg_price) ?></p>
        <p><?php echo htmlspecialchars($description) ?></p>
    </div>
</a>
