<?php
    if (empty($image)) return;
?>

<article
    class="border border-black dark:border-white w-full aspect-[16/9] overflow-hidden"
    x-data="{
        img: '<?=$image?>',
    }"
>
    <img
        class="w-full h-full object-cover"
        x-ref="img"
        :src="img"
        @click="$refs.img.src = 'https://random.api.mikus.ink?' + new Date().getTime()"
    >
</article>