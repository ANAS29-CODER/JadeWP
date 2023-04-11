</main>
<footer>
    <div class="footer">
        <?php if (is_active_sidebar('footer_widget_logo')) : ?>

            <?php dynamic_sidebar('footer_widget_logo'); ?>

        <?php endif; ?>


        <div class="footer-content">
            <p class="sub-title">Subsea Services</p>
            <div class="footer-text">

                <?php if (is_active_sidebar('footer_widget_area')) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer_widget_area'); ?>
                    </div>
                <?php endif; ?>


            </div>



        </div>


        <div class="footer-content">
            <p class="sub-title">Jade Services</p>
            <?php wp_nav_menu(array(
                'theme_location' => 'footer-menu',

            )); ?>
        </div>


    </div>
    <div class="footer-copyright">
        <p>Copyright 2021, <span><?php bloginfo('name') ?></span>. All rights reserved.</p>
    </div>







</footer>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script src=" <?php echo get_theme_file_uri('assets/js/home.js') ?>"></script>

<script src="<?php echo get_theme_file_uri('assets/js/global.js') ?>"></script>

<script src="<?php echo get_theme_file_uri('assets/js/customization.js') ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous">
</script>



<?php wp_footer(); ?>
</body>

</html>