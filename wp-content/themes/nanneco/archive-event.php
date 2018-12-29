<?php get_header(); ?>




    <main class="l-main" id="main">

        <article class="c-article">

            <h1 class="c-h1 u-mb--md">
                <div class="c-section_inner">ナントカと猫企画のイベント<i>nan-neco presents</i></div>
            </h1>


            <?php

            if(have_posts()) :

            $postType =  get_post_type_object( get_post_type() )->label;

            ?>
            <div class="c-section_inner">

                <article class="c-media">
                    <ul class="c-media_list">

                        <?php

                        while(have_posts()): the_post();

                        ?>
                        <li class="c-media_item">
                            <a class="c-media_target f-flex" href="<?php the_permalink(); ?>">
                                <figure class="c-media_figure"
                                        style="background-image: url(<?php the_post_thumbnail_url(); ?>);"></figure>
                                <div class="c-article_text u-text-left">

                                    <time class="c-media_time"><?php the_field('event-date-yobi'); ?></time>
                                    <div class="c-media_textarea">
                                       <p class="c-media_text"><?php the_field('event-copy'); ?></p>
                                        <h3 class="c-media_title"><?php the_title(); ?></h3>
                                    </div>

                                </div>
                            </a>
                        </li>

                        <?php

                        endwhile;

                        ?>

                    </ul>
                </article>

            </div>
            <?php

            endif;

            ?>


        </article>

    </main>


              <?php
                if (function_exists("pagination")) {
                pagination($additional_loop->max_num_pages);
                }
              ?>


    <?php get_footer(); ?>
