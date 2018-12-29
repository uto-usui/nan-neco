<?php get_header(); ?>


      <?php
        while (have_posts() ): the_post();
      ?>

      <main class="l-main" id="main">

        <section class="" id="js-contact">

          <article class="c-article">

            <h1 class="c-h1">
                <div class="c-section_inner"><?php the_title(); ?><i>Contact</i></div>
            </h1>

            <div class="c-section_inner">
              <div class="p-contact_inner">

                <?php the_content(); ?>

              </div>
            </div>
          </article>

        </section>

      </main>


      <?php endwhile; ?>

    <?php get_footer(); ?>
