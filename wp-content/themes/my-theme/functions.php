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

/************************************************************************************************************** */


//register menu to put it in the footer
function register_footer_menu() {
  register_nav_menu('footer-menu',__( 'Footer Menu' ));
}
add_action( 'init', 'register_footer_menu' );

/************************************************************************************************************** */

// Add certificate post type to use in shortcode

function certificate_shortcode() {
  ob_start();
  include 'loop-templates/page-certificate.php';
  return ob_get_clean();
}
add_shortcode( 'certificate', 'certificate_shortcode' );

/************************************************************************************************************** */


// Add services post type to use in shortcode

function service_shortcode() {
  ob_start();
  include 'loop-templates/page-services.php';
  return ob_get_clean();
}
add_shortcode( 'service', 'service_shortcode' );

/************************************************************************************************************** */

//add location taxomony in table career 

// Add new column to careers table
function add_career_location_column($columns) {
  $columns['location'] = __('Location', 'text-domain');
  return $columns;
}
add_filter('manage_career_posts_columns', 'add_career_location_column');

// Populate new column with taxonomy values
function populate_career_location_column($column, $post_id) {
  if ($column === 'location') {
      $terms = wp_get_post_terms($post_id, 'location-career');
      if (!empty($terms)) {
          $locations = array();
          foreach ($terms as $term) {
          
            $locations[] = $term->name;
          }
          echo implode(', ', $locations);
      }
  }
}
add_action('manage_career_posts_custom_column', 'populate_career_location_column', 10, 2);

/************************************************************************************************************** */


//add Type taxomony in table career 

// Add new column to careers table
function add_career_type_column($columns) {
  $columns['type'] = __('Type', 'text-domain');
  return $columns;
}
add_filter('manage_career_posts_columns', 'add_career_type_column');

// Populate new column with taxonomy values
function populate_career_type_column($column, $post_id) {
  if ($column === 'type') {
      $terms = wp_get_post_terms($post_id, 'type-career');
      if (!empty($terms)) {
          $locations = array();
          foreach ($terms as $term) {
          
            $locations[] = $term->name;
          }
          echo implode(', ', $locations);
      }
  }
}
add_action('manage_career_posts_custom_column', 'populate_career_type_column', 10, 2);


/************************************************************************************************************** */

// Add certificate post type to use in shortcode

function career_shortcode() {
  ob_start();
  include 'loop-templates/page-positions.php';
  return ob_get_clean();
}
add_shortcode( 'career', 'career_shortcode' );

/************************************************************************************************************** */

//put custom css file just for single post Career
function add_custom_css_to_career_single_post() {
  if (is_singular('career') && get_post_type() === 'career') {
    wp_enqueue_style('career-single-post-css', get_stylesheet_directory_uri() . '/assets/css/apply_carers.css');
  }
}
add_action('wp_enqueue_scripts', 'add_custom_css_to_career_single_post');

/************************************************************************************************************** */

//Change the submenu class in menus
function change_submenu_class($menu) {
  $menu = preg_replace('/ class="sub-menu"/',' class="dropdown-menu"',$menu);
  return $menu;
}
add_filter('wp_nav_menu', 'change_submenu_class');



/************************************************************************************************************** */

// Add certificate post type to use in shortcode

function news_shortcode() {
  ob_start();
  include 'loop-templates/page-news.php';
  return ob_get_clean();
}
add_shortcode( 'news', 'news_shortcode' );

/************************************************************************************************************** */