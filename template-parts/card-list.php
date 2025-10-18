<?php
    if (empty($images)) return;
?>

<ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php  foreach ($images as $image) : ?>
        <li>
            <?php
                get_part('card', [
                    'image' => $image
                ]);
            ?>
        </li>
    <?php endforeach; ?>
</ul>