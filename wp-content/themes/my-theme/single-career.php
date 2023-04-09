<?php

get_header();


?>

<div class="header-img-wrapper">
    <h1 style="color:white; z-index:2;top: 17.63vw;" class="title-feature-image"><?php the_title(); ?></h1>
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

<div class="page-container">

    <div class="inner-page-container">

        <div class="inner-container header-section" style="margin-bottom: 5.28vw !important;">
            <h2><?php if (the_field('title')) : the_field('title');
                endif; ?></h2>
            <p><?php if (the_field('career-desc')) : the_field('career-desc');
                endif; ?> </p>

        </div>


        <div class="inner-container">
            <h2>Job Description</h2>
            <?php if (have_rows('job-desc')) : ?>
                <ul>
                    <?php while (have_rows('job-desc')) : the_row(); ?>
                        <li><?php the_sub_field('job-desc-list'); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>


        <div class="inner-container margin-bottom-140">
            <h2>Qualifications</h2>
            <?php if (have_rows('career-qualifications')) : ?>
                <ul>
                    <?php while (have_rows('career-qualifications')) : the_row(); ?>
                        <li><?php the_sub_field('career-qualifications-list'); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
            <form>
                <input type="file" id="pdfFile" accept=".pdf" style="display: none">
                <button type="submit" class="form-btn margin-top-54" id="uploadBtn"><img src="<?php echo get_theme_file_uri('assets/icons/white-add-icon.svg') ?>" class="btn-add-icon">Upload
                    CV</button>
            </form>
        </div>

        <div class="inner-container margin-zero">
            <div class="form-container">

                <?php echo do_shortcode('[contact-form-7 id="659" title="Untitled"]'); ?>
            </div>
        </div>




    </div>
</div>




<?php get_footer(); ?>