<?php

get_header();

// while (have_posts()) : the_post();
?>

    <div class="header-img-wrapper">
        <h1 style="color:white;" class="title-feature-image"><?php the_field('title_feature_image'); ?></h1>
        <?php if (has_post_thumbnail()) : ?>

            <?php the_post_thumbnail('post-thumbnail', array('style' => 'width:100%;')); ?>
        <?php else : ?>


            <?php if (!is_page('contacts')) : ?>
                <img src="<?php echo get_theme_file_uri('assets/images/about-us.png') ?>" alt="Default Photo" style="width: 100%;">
            <?php endif; ?>


        <?php endif; ?>
    </div>




    <div class="service-container" style="width: 96%;">
        <div class="service-body">
            <div class="service-body-row row">


                <?php 
                $image = get_field('service-image');
                if ($image != null) {
                    $image_url = $image['sizes']['large'];
                } else {
        
                    $image_url = null;
                }
                ?>

                <div class="service-content col-lg-3 col-md-6">
                    <div>
                        <?php if ($image_url !== null) : ?> <img src="<?php echo $image_url; ?>" class="qa-element-img"><?php endif; ?>
                        <h3>
                            <?php the_field('service-title'); ?>
                        </h3>
                        <?php if (have_rows('service-list')) : ?>
                            <ul>
                                <?php while (have_rows('service-list')) : the_row(); ?>
                                    <li><?php the_sub_field('service-list-info'); ?></li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div>
                        <a href="./capability.html"><img src="<?php echo get_theme_file_uri('assets/icons/service-page/add-icon.svg') ?>">Learn more</a>
                    </div>
                </div>


            </div>
        </div>

    </div>




<?php
//endwhile;
//     wp_reset_postdata();

// endif;
?>

<?php get_footer(); ?>