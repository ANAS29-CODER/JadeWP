<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/global.css') ?>">

    <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/normalize.css') ?>">


    <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/home.css') ?>" />
    <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/qa-wrapper-section.css') ?>" />

    <?php if (strpos($_SERVER['REQUEST_URI'], 'jade_services') !== false) { ?>
        <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/service.css') ?>" />
    <?php } ?>

    <?php if (strpos($_SERVER['REQUEST_URI'], 'careers') !== false) { ?>
        <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/carers.css') ?>" />
    <?php } ?>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@700&display=swap">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <?php if (is_page('contacts')) : ?>
        <style>
            .custom-header-nav {
                position: unset !important;
            }

            #menu-header-menu li a {
                color: black;
            }

            input[value="Send Message"] {
                margin-top: 86px !important;
            }
        </style>

    <?php else : ?>
        <style>
            .custom-header-nav {
                position: absolute !important;
            }
        </style>

    <?php endif; ?>


    <?php if (is_page('home')) : ?>
        <style>
            .wp-block-cover .wp-block-cover__image-background {
                height: 772px !important;
            }
        </style>

    <?php endif; ?>

    <title>
        <?php bloginfo('name') ?>
    </title>
    <?php wp_head(); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>

    <div class="custom-header-nav py-lg-0 py-2">
        <nav class="navbar navbar-expand-lg navbar-light h-full">
            <div class="container">
                <a class="navbar-brand" href="/home">
                    <?php if (is_page('contacts')) : ?>

                        <img src="<?php echo get_theme_file_uri('assets/icons/black-logo.svg'); ?>" class="nav-bar-black-logo" style="width:142px;display:unset !important;" />
                    <?php else : ?>

                        <img src="<?php echo get_header_image(); ?>" class="nav-bar-logo" />

                    <?php endif; ?>


                </a>
                <button class="navbar-toggler collapse-color" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                        </svg>
                    </span>
                </button>
                <div class="collapse navbar-collapse h-full" id="navbarNav">

                    <?php wp_nav_menu(array(
                        'theme_location' => 'header-menu',
                        'menu_class' => 'navbar-nav ms-auto h-full', 'level' => 1, 'child_of' => 'About Us',

                    )); ?>

                </div>
            </div>
        </nav>
    </div>
    <main>