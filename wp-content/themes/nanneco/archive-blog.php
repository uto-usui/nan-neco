<?php get_header(); ?>



      <?php if(have_posts()) : ?>



      <?php $postType =  get_post_type_object( get_post_type() )->label; ?>
      
      
      <article class="hero_wrap">
        <a class="hero hero--sub f-flex f-middle f-center js-page-scroll" href="#content" style="background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/home/12.jpg)">
          <div class="hero_inner" id="js-scroll">
            <h1 class="hero_text"><?php echo $postType; ?></h1>
          </div>
        </a>
      </article>
      
      
      <!-- blog -->
      <div id="content">
       
        <?php  while(have_posts()): the_post(); ?>
        <article class="article">
          <div class="article_inner js-fadein-wrap">
           
            <a class="blog_figure f-flex f-bottom f-right" href="<?php the_permalink(); ?>" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
             <time class="blog_time" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
            </a>
            
            <div class="blog_content">
             
              <h2 class="h2"><?php the_title();  ?></h2>
              
              <?php the_content(); ?>
              
            </div>
            
            
          </div>
        
        
        </article>
        <?php endwhile; ?>
        
        
        <?php
          if (function_exists("pagination")) {
          pagination($additional_loop->max_num_pages);
          }
        ?>
        
        
      </div>


      <?php endif; ?>






    <?php get_footer(); ?>
