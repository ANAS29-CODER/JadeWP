<?php
$args = array(
    'post_type' => 'jade_services',
    'orderby' => 'date',
    'order' => 'ASC',
);

$query = new WP_Query($args);
if ($query->have_posts()) :
    echo '  
    <div class="service-container" style="width: 96%;">
    <div class="service-body">
    <div class="service-body-row row">
 ';
    while ($query->have_posts()) :

        $query->the_post();

        $image = get_field('service-image');
        if ($image != null) {
            $image_url = $image['sizes']['large'];
        } else {

            $image_url = null;
        }
?>


        <div class="service-content col-lg-3 col-md-6" style="    display: flex;flex-direction: column; justify-content: space-between;
    height: 450px;
    padding-top: 50px;
    ">
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
                <a href="./capability.html" style=" display: flex; font-weight: 700; font-size: 1.125rem;
    line-height: 1.563rem;
    padding-left: 12px;"><img src="<?php echo get_theme_file_uri('assets/icons/service-page/add-icon.svg') ?>">Learn more</a>
            </div>
        </div>



    <?php
    endwhile;
    wp_reset_postdata(); ?>
    </div>
    </div>

    </div>
<?php
else :
    echo 'No Services found.';
endif;
?>