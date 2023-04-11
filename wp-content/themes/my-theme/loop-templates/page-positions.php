<?php

use PhpMyAdmin\SqlParser\Utils\Query;

$args = array(
    'post_type' => 'career',
    'order' => 'ASC',
);

$query = new WP_Query($args);
if ($query->have_posts()) :
    echo '<div class="table-responsive">
      <table class="table">
      <tbody>
      <tr>
      <th class="pb-14">Position</th>
      <th class="align-center pb-14">Location</th>
      <th class="pb-14">
          <p class="align-center custom-margin">Type</p>
      </th>
      <th></th>
  </tr>
      ';

    while ($query->have_posts()) :
        $query->the_post();

?>
        <tr>
            <td class="carers-name py-43">
                <p class="H5"><?php the_title(); ?></p>
            </td>
            <td class="align-center py-45">
                <p> <?php $terms = get_the_terms(get_the_ID(), 'location-career');
                    if ($terms && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            echo $term->name;
                        }
                    } ?></p>
            </td>
            <td class="align-center py-45">

                <?php $terms = get_the_terms(get_the_ID(), 'type-career');
                if ($terms && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        echo $term->name;
                    }
                } ?>
            </td>
            <td class="py-46">
                <div class="btn-wrapper" s=""><a class="form-btn" style="width: 161px;" href="<?php echo get_permalink(); ?>"><img src="<?php echo get_theme_file_uri('assets/icons/white-add-icon.svg') ?>" class="btn-add-icon"><span>Apply
                            now</span></a></div>
            </td>
        </tr>





    <?php
    endwhile;
    wp_reset_postdata(); ?>
    </tbody>
    </table>
    </div>
<?php
else :
    echo 'No Careers found.';
endif;
?>

