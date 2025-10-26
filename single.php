<?php get_header(); ?>

<section class="container mx-auto px-6">
    <?php the_content(); ?>

    <button class="scroll-fade-up bg-blue-500 text-white rounded py-1 px-2 cursor-pointer" x-data="{ 
        msg: 'hello world',
        logger() {
            console.log(this.msg);
        }
    }" x-text="msg" @click="logger()"></button>
</section>

<?php get_footer(); ?>