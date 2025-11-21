<!-- Partial for generating tiles for each restaurant FOR ADMINS -->

<div class="admin-tile">
    <?php $file_ext = $restaurant["file_ext"] ?>
    <figure>
        <img src="/public/uploads/restaurants/<?php echo $id . '.' . htmlspecialchars($file_ext); ?>" alt="<?php echo htmlspecialchars($name); ?>" />
    </figure>
    <h1><?php echo htmlspecialchars($name) ?></h1>
    <a href="/admin/edit?<?php echo http_build_query(array('id' => $id)); ?>">
        <img class="edit-button" src="public/images/edit-button.svg" alt="Edit Entry <?php echo htmlspecialchars($name); ?>"/>
    </a>
</div>
