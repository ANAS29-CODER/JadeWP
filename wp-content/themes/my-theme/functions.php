<?php

//add custom feature image to page
add_theme_support('post-thumbnails');



//add menue customize
register_nav_menu('primary-menu', 'Top Menu');
function register_my_menus()
{
  register_nav_menus(
    array(
      'header-menu' => __('Header Menu')
    )
  );
}

add_action('init', 'register_my_menus');

//adding custom-logo in main menue
add_theme_support('custom-logo');


//adding custom-logo in main menue
add_theme_support('custom-header');


//adding custom menue
register_sidebar(
  array(
    'name' => 'Sidebar Location',
    'id' => 'sidebar',
  )
);




function wpse_custom_theme_widgets_init()
{
  register_sidebar(array(
    'name'          => 'Footer Widget Area',
    'id'            => 'footer_widget_area',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>',
  ));
}
add_action('widgets_init', 'wpse_custom_theme_widgets_init');

function wpse_custom_theme_widgets_init_logo()
{
  register_sidebar(array(
    'name'          => 'Footer Widget Logo',
    'id'            => 'footer_widget_logo',
    'class'         => 'footer-logo' ,
    // 'before_widget' => '<div>',
    // 'after_widget'  => '</div>',
    // 'before_title'  => '<h2>',
    // 'after_title'   => '</h2>',
  ));
}
add_action('widgets_init', 'wpse_custom_theme_widgets_init_logo');


//register menu to put it in the footer
function register_footer_menu() {
  register_nav_menu('footer-menu',__( 'Footer Menu' ));
}
add_action( 'init', 'register_footer_menu' );

// Add certificate post type to use in shortcode

function certificate_shortcode() {
  ob_start();
  include 'loop-templates/page-certificate.php';
  return ob_get_clean();
}
add_shortcode( 'certificate', 'certificate_shortcode' );


// Add services post type to use in shortcode

function service_shortcode() {
  ob_start();
  include 'loop-templates/page-services.php';
  return ob_get_clean();
}
add_shortcode( 'service', 'service_shortcode' );