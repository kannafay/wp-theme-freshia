<?php
if (empty($images))
    return;
?>

<ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($images as $image): ?>
        <li>
            <article class="border border-black dark:border-white w-full aspect-[3/2] overflow-hidden"
                x-data="{img: '<?= $image ?>'}">
                <img class="w-full h-full object-cover" x-ref="img" :src="img"
                    @click="$refs.img.src = 'https://random.api.mikus.ink?' + new Date().getTime()" />
            </article>
        </li>
    <?php endforeach; ?>
</ul>