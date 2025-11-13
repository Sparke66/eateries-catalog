<!-- Partial for generating tiles for each restaurant -->

<div class="tile">
    <figure>
        <img src="/images/placeholder.jpg" alt="Placeholder" />
        <figcaption>Placeholder Image</figcaption>
    </figure>
    <h3><?php echo htmlspecialchars($name) ?></h3>
    <p><?php echo htmlspecialchars($address) ?></p>
    <p><?php echo htmlspecialchars($rating) ?></p>
    <p>Average Price: $<?php echo htmlspecialchars($avg_price) ?></p>
    <p><?php echo htmlspecialchars($description) ?></p>
</div>
