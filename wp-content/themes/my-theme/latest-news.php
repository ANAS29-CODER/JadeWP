<?php
/*
Template Name: Latest News
*/
get_header();


?>



<div class="latest-news-container">
    <div class="flex-column">

        <h5 class="home-latest-news"><span>Home</span> / Latest News</h5>
        <div class="page-container">
            <div class="flex-column width-30">
                <!-- <div class="search-section">
                    <div class="search-container">
                        <input placeholder="Type your search">
                        <img src="assets/icons/search.svg">
                    </div>
                </div> -->
                <!-- <div class="about-us">
                    <h3>About Us</h3>
                    <div class="row">
                        <div class="col-lg-12 col-md-6">
                            <img src="assets/images/gallery.png" class="about-us-img">

                        </div>
                        <div class="col-lg-12 col-md-6">
                            <p>ADE provides full turnkey services to oil and gas majors operating offshore through our
                                diversified
                                portfolio.</p>

                        </div>

                    </div>
                </div> -->

                <div class="categories">
                    <h3>Categories</h3>
                    <div class="inner-categories">
                        <a href="#">
                            <p>Categorie (3)</p>
                        </a>
                        <?php
                        $args = array(
                            'post_type' => 'news',

                        );

                        $querys = new WP_Query($args);
                        while ($querys->have_posts()) {

                            $querys->the_post();
                            $categories = get_the_terms(get_the_ID(), 'category');

                            if ($categories) {
                                foreach ($categories as $category) {

                                    if ($category->name) {
                                        echo '<a href="#">
                                            <p>' . $category->name . '</p>
                                        </a> ';
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="related-posts">
                    <h3>Related Posts</h3>
                    <div class="row">
                        <?php
                        $args = array(
                            'post_type' => 'news',

                        );

                        $querys = new WP_Query($args);
                        $count = 0;
                        while ($querys->have_posts()) {
                            $querys->the_post();
                            $count += 1;
                            if ($count > 5) {
                                break;
                            }


                            $image = get_field('news-image');

                            $image_url = $image['sizes']['large'];
                        ?>
                            <div class="col-md-6 col-lg-12 col-12">
                                <a href="#" class="related-post flex-row">
                                    <img src="<?php echo  $image_url; ?>" class="related-posts-img">
                                    <div class="title-container">
                                        <p class="related-main-title">Repair</p>
                                        <p class="related-sub-title">Fresh Start</p>
                                    </div>
                                </a>
                            </div>

                        <?php
                        }
                        ?>

                    </div>



                </div>
            </div>
            <div class="flex-column width-70">
                <div class="flex-column  boarder-bottom">

                    <?php echo do_shortcode('[news]'); ?>

                </div>
                <?php
                $args = array(
                    'post_type' => 'news',
                    'posts_per_page' => 2,
                    'order' => 'ASC',
                );

                $query = new WP_Query($args);
                ?>

                <nav aria-label="Page navigation">
                    <div class="pagination-wrapper">

                        <ul class="pagination" id="pagination-news" style="position: relative;">
                            <?php

                            echo paginate_links(array(
                                'total' => $query->max_num_pages,
                             
                            )) ; ?>

                            

                        </ul>
                        <!-- <div class="next-btn-wrapper"><a class="page-link" href="#">Next
                                <img src="<?php echo get_theme_file_uri('assets/icons/small-black-next-icon.svg') ?>" class="r-180">
                            </a></div>
                    </div> -->
                </nav>
            </div>



        </div>
    </div>
</div>





<!-- Add your custom page content here -->

<?php get_footer(); ?>