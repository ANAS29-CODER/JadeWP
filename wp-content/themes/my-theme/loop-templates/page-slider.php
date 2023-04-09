
<div id="carouselExampleDark" class="carousel carousel-white slide">


    <div class="carousel-inner">

    <?php  while(have_rows('slider')) : the_row(); ?>
        <div class="carousel-item <?php if(get_row_index() ==1 ) echo 'active'; ?>" data-bs-interval="10000">

        <?php 
        
        $image=get_sub_field('image-slider');
        $image_url=$image['sizes']['large'];
        ?>
   
            <img src="<?php echo $image_url;?>" class="d-block w-100" alt="...">
            <div class="carousel-caption d-md-block carousel-text-margin">

                <h1 class="H1"><?php the_sub_field('header-slider');?></h1>
                <p><?php the_sub_field('desc-slider');?></p>

            </div>
        </div>
  
        <?php endwhile;?>

    </div>

    
    <button class="carousel-control-prev " type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
        <span aria-hidden="true">
            <img src="<?php echo get_theme_file_uri('assets/icons/prev-icon.svg')?>" />
        </span>

        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
        <span aria-hidden="true">
            <img src="<?php echo  get_theme_file_uri('assets/icons/next-icon.svg')?>" />
        </span>
        <span class="visually-hidden">Next</span>
    </button>
</div>