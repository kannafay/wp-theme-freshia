<?php get_header(); ?>

<div class="prose">
    <?php the_content(); ?>
</div>

<button class="scroll-fade-up bg-blue-500 text-white rounded py-1 px-2 cursor-pointer" x-data="{ 
    msg: 'hello world',
    logger() {
        console.log(this.msg);
    }
}" x-text="msg" @click="logger()"></button>

<?php get_footer(); ?>