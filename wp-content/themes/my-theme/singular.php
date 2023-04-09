<?php

get_header();
?>




<?php if (is_page('home')) :   ?>

    <?php get_template_part('loop-templates/page', 'slider'); ?>

    <?php

    if (get_field('vision-section-title') || get_field('vision-section-text')) {


        $vision_title = get_field('vision-section-title');

        $vision_text = get_field('vision-section-text');

    ?>
        <div class="home-content-container">
            <div class="jade-vision-section">
                <p>
                    <?php echo $vision_title; ?><br>
                    <?php echo $vision_text; ?>
                </p>
            </div>
        </div>


    <?php } ?>

    <?php if (have_rows('vision-swiper') && is_page('home')) get_template_part('loop-templates/page', 'swiper'); ?>




<?php else : ?>

    <div class="header-img-wrapper">
        <h1 style="color:white; z-index:2;top: 17.63vw;" class="title-feature-image"><?php the_field('title_feature_image'); ?></h1>
        <?php if (has_post_thumbnail()) : ?>

            <?php the_post_thumbnail('post-thumbnail', array('style' => 'width:100%;')); ?>
        <?php else : ?>

            <?php if (!is_page('contacts')) : ?>
                <div class="carers-blue-header" style="    width: 100%;
    height: 32.3vw;
    margin-bottom: 9.51vw;
    background-color: var(--primary-color);
    position: relative;">

                </div>
                <!-- <img src="<?php echo get_theme_file_uri('assets/images/about-us.png') ?>" alt="Default Photo" style="width: 100%;"> -->
            <?php endif; ?>




        <?php endif; ?>
    </div>



<?php endif; ?>



<?php the_content(); ?>

<?php if (is_page('home')) :

    //  dynamic_sidebar('sidebar');


?>



<?php endif; ?>

<?php








get_footer();
?>