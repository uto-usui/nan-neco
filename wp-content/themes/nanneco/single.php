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
      
        <!-- shop -->
        <article class="article">
          
          
          <div class="post f-top f-wrap">
              
            <div class="post_left f-flex f-middle">
              <time class="post_time" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y-m-d'); ?></time>
              <a target="_blank" class="post_social" href="https://twitter.com/share?url=<?php rawurlencode(the_permalink()); ?>&text=<?php rawurlencode( the_title()); ?> ">T</a><a target="_blank" class="post_social" href="https://www.facebook.com/sharer/sharer.php?u=<?php rawurlencode(the_permalink()); ?>">F</a>
            </div>
              
            <div class="post_right btn--arrow_wrap" href="<?php the_permalink(); ?>">
              <h3 class="h2 u-text-left"><?php the_title();  ?></h3>
              <div class="post_lead">
                
              </div>
              <div class="post_body">
                <?php the_content(); ?>
              </div>
            </div>
              
          </div>
          
          
          <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
               ?>
              
              <ul class="pager f-flex f-center f-middle">
                <li class="pager_item" <?php if (!$prev_post) : ?> style="visibility:hidden;" <?php endif; ?>>
                  <a href="<?php echo get_permalink($prev_post->ID); ?>" class="pager_target btn--arrow_wrap f-flex f-middle f-center"><span class="btn btn--arrow btn--arrow-prev"></span></a>
                </li>
                <li class="pager_item pager_item--text">
                  <a href="<?php echo get_post_type_archive_link( get_post_type() ); ?>" class="pager_target pager_target-text f-flex f-middle f-center">back to list</a>
                </li>
                <li class="pager_item" <?php if (!$next_post) : ?> style="visibility:hidden;" <?php endif; ?>>
                  <a href="<?php echo get_permalink($next_post->ID); ?>" class="pager_target btn--arrow_wrap f-flex f-middle f-center"><span class="btn btn--arrow btn--arrow-next"></span></a>
                </li>
              </ul>


        </article>
       


      <?php endwhile; ?>






    <?php get_footer(); ?>
