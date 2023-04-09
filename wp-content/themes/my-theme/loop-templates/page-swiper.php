<div class="home-content-container">
    <div class="swipper-section" style="position:relative;margin-bottom:50px;">
        <div class="background-blue-container">
        </div>
        <img src="<?php get_theme_file_uri('assets/icons/rectangle.svg') ?>" />
        <div class="swipper-section-wrapper">
            <div class="swiper-container">
                <div class="swiper-wrapper">

                    <?php while (have_rows('vision-swiper')) : the_row();
                        $image_url = get_sub_field('vision-swiper-image')['sizes']['large'];
                    ?>


                        <div class="swiper-slide">
                            <img src="<?php echo $image_url; ?>">
                            <div class="slide-text">
                                <p class="paragraph01 description-p"><?php echo get_sub_field('vision-swiper-desc') ?></p>
                                <a class="vision-container" href="<?php echo get_sub_field('vision-swiper-link') ?>">
                                    <img src="<?php echo get_sub_field('vision-swiper-image-link')['sizes']['large'] ?>" class="eyes-icon" />
                                    <p><?php echo get_sub_field('vision-swiper-text-link') ?></p>
                                </a>
                            </div>
                        </div>



                        
                    <?php endwhile; ?>
                </div>
                <div class="swiper-button-prev">
                    <img src="<?php echo get_theme_file_uri('assets/icons/small-prev-icon.svg') ?>" />
                </div>
                <div class="swiper-button-next">
                    <img src="<?php echo get_theme_file_uri('assets/icons/small-next-icon.svg') ?>" />
                </div>
            </div>
        </div>
    </div>
</div>