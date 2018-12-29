<?php get_header(); ?>



      <?php
        while (have_posts() ): the_post();
      ?>


      
      
      <article class="hero_wrap">
        <a class="hero hero--sub f-flex f-middle f-center js-page-scroll" href="#content" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
          <div class="hero_inner" id="js-scroll">
            <h1 class="hero_text"><?php the_title(); ?></h1>
          </div>
        </a>
      </article>
      
      
      <!-- blog -->
      <div id="content">
       
        <article class="article">
          <div class="article_inner js-fadein-wrap">
            
            <div class="blog_content">
             
              <h2 class="h2"><?php the_title();  ?></h2>
              
              <?php the_content(); ?>
              
              
              <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
               ?>
              
              <ul class="pager f-flex f-center f-middle">
                <li class="pager_item" <?php if (!$prev_post) : ?> style="visibility:hidden;" <?php endif; ?>>
                  <a href="<?php echo get_permalink($prev_post->ID); ?>" class="pager_target btn--arrow_wrap f-flex f-middle f-center"><span class="btn btn--arrow btn--arrow-prev"></span></a>
                </li>
                <li class="pager_item pager_item--text">
                  <a href="<?php echo home_url('/blog/'); ?>" class="pager_target pager_target-text f-flex f-middle f-center">back to list</a>
                </li>
                <li class="pager_item" <?php if (!$next_post) : ?> style="visibility:hidden;" <?php endif; ?>>
                  <a href="<?php echo get_permalink($next_post->ID); ?>" class="pager_target btn--arrow_wrap f-flex f-middle f-center"><span class="btn btn--arrow btn--arrow-next"></span></a>
                </li>
              </ul>
              
              
            </div>
            
            
          </div>
        
        
        </article>
        
        
      </div>
      <?php endwhile; ?>






    <?php get_footer(); ?>
