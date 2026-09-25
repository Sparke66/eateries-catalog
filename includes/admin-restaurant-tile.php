<!-- Partial for generating tiles for each restaurant FOR ADMINS -->
<!-- Admin view: simplified display with edit functionality -->
<!-- Expects variables: $restaurant, $id, $name -->

<div class="admin-tile">
    <?php $file_ext = $restaurant["file_ext"] ?>
    <figure>
        <!-- Smaller restaurant image for admin list view -->
        <img src="/public/uploads/restaurants/<?php echo $id . '.' . htmlspecialchars($file_ext); ?>" alt="<?php echo htmlspecialchars($name); ?>" />
    </figure>
    <div class="admin-tile-text">
        <!-- Restaurant name -->
        <h2><?php echo htmlspecialchars($name) ?></h2>
        <!-- Edit button links to admin edit page -->
        <a class="icon-button" href="/admin/edit?<?php echo http_build_query(array('id' => $id)); ?>">
            <img class="edit-button" src="/public/images/edit-button.svg" alt="Edit Entry <?php echo htmlspecialchars($name); ?>" />
        </a>
    </div>
</div>
