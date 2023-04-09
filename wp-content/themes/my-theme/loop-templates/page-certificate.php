<?php
$args = array(
    'post_type' => 'certificate',
    'orderby' => 'date',
    'order' => 'DESC',
);

$query = new WP_Query($args);
if ($query->have_posts()) :
    echo '  <div class="qa-wrapper">
    <h3 class="qa-section-title">
        Quality Asuurance
    </h3>
 ';
    while ($query->have_posts()) :

        $query->the_post();

        $image = get_field('certificate-image');
        if ($image != null) {
            $image_url = $image['sizes']['large'];
        }else{

            $image_url = null;
        }
?>



    <div class="qa-item-wrapper">
    <div class="qa-item-inner-wrapper">
       <?php if($image_url !==null): ?> <img src="<?php echo $image_url; ?>" class="qa-element-img"><?php endif;?>
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
                <?php if($image_url==!null):?>
                <div class="qa-download-option">

                    <a class="qa-download-option-one" href="#"><img src="<?php echo get_theme_file_uri('assets/icons/download.svg') ?>" class="qa-download-img">
                        <h3>Download 1</h3>
                    </a>


                    <a class="qa-download-option-one" href="#"><img src="<?php echo get_theme_file_uri('assets/icons/download.svg') ?>" class="qa-download-img">
                        <h3>Download 2</h3>
                    </a>

                </div>
                <?php endif;?>
            </div>
        </div>




        </div>
</div>
<?php
    endwhile;
    wp_reset_postdata();?>
      </div>

    <?php
else :
    echo 'No certificates found.';
endif;
?>
