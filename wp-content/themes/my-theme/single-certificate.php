<?php

get_header();

while (have_posts()) : the_post();
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





    <div class="qa-wrapper">
        <h3 class="qa-section-title" >
            Quality Asuurance
        </h3>

        <div class="qa-item-wrapper">
            <div class="qa-item-inner-wrapper">

                <?php $image = get_field('certificate-image');
                $image_url = $image['sizes']['large'];
                ?>

                <img src="<?php echo $image_url; ?>" class="qa-element-img">
                <div class="qa-element-list">
                    <div>
                        <h3 class="list-title"><?php the_field('certificate-title'); ?></h3>
                        <?php if (have_rows('certificate-list')) : ?>
                            <ul>
                                <?php while (have_rows('certificate-list')) : the_row(); ?>
                                    <li><?php the_sub_field('certificate-list-info'); ?></li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                        <div class="qa-download-option">

                            <a class="qa-download-option-one" href="#"><img src="<?php echo get_theme_file_uri('assets/icons/download.svg') ?>" class="qa-download-img">
                                <h3>Download 1</h3>
                            </a>


                            <a class="qa-download-option-one" href="#"><img src="<?php echo get_theme_file_uri('assets/icons/download.svg') ?>" class="qa-download-img">
                                <h3>Download 2</h3>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    </div>

<?php
endwhile;
//     wp_reset_postdata();

// endif;
?>

<?php get_footer(); ?>