<?php


$args = array(
    'post_type' => 'news',
    'posts_per_page' => 2,
    'order' => 'ASC',
);

$query = new WP_Query($args);

if ($query->have_posts()) :


    while ($query->have_posts()) :
        $query->the_post();

        $image = get_field('news-image');

        $image_url = $image['sizes']['large'];
?>


        <div class="news-info">
            <div>
                <img src="<?php echo $image_url ?>" class="latestnews-img">
            </div>
            <div class="latestnews-data">
                <h5>
                    August 6, 2021 <?php
                                    $categories = get_the_category();
                                    foreach ($categories as $category) {
                                        echo '/ '  . $category->name;
                                    }
                                    ?>
                </h5>
                <h2><?php echo the_title(); ?></h2>
                <p><?php echo get_field('news-desc'); ?>
                </p>
                <a href="#">
                    <div class="read-btn-section">
                        <div class="add-btn"><img src="<?php echo get_theme_file_uri('assets/icons/white-add-icon.svg') ?>" class="btn-add-icon"></div>
                        <p>Read More
                            About this</p>
                    </div>
                </a>
            </div>
        </div>
    
<?php

    endwhile;
    wp_reset_postdata();





endif; ?>