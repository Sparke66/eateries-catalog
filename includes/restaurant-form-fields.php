<!-- Form fields shared by the admin insert and edit pages -->
<!-- Expects variables: $form (sticky field values), $image_hint (optional note under the image field) -->

<div class="field">
    <label for="name">Restaurant Name</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($form['name']); ?>" required>
</div>

<div class="field">
    <label for="address">Address</label>
    <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($form['address']); ?>" required>
</div>

<!-- Rating and price sit side by side -->
<div class="field-row">
    <div class="field">
        <label for="rating">Rating</label>
        <input type="number" name="rating" id="rating" min="1" max="5" step="1" value="<?php echo htmlspecialchars($form['rating']); ?>" required>
        <p class="hint">Whole number, 1–5</p>
    </div>

    <div class="field">
        <label for="avg_price">Average Price ($)</label>
        <input type="number" name="avg_price" id="avg_price" min="0" step="1" value="<?php echo htmlspecialchars($form['avg_price']); ?>" required>
        <p class="hint">Per person, in dollars</p>
    </div>
</div>

<div class="field">
    <label for="description">Description</label>
    <!-- Source: Mozilla Reference Documentation -->
    <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($form['description']); ?></textarea>
</div>

<div class="field">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
    <label for="restaurant-image">Image</label>
    <div class="file-drop">
        <!-- Enforces file upload validation on client side by restricting what files can be
                uploaded through the explorer -->
        <input type="file" name="restaurant-image" id="restaurant-image" accept=".jpeg, .jpg, .png">
        <span class="hint">JPG / PNG · max 1 MB</span>
    </div>
    <?php if (!empty($image_hint)): ?>
        <p class="hint"><?php echo htmlspecialchars($image_hint); ?></p>
    <?php endif; ?>
</div>
