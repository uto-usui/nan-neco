<?php get_header(); ?>

    <main class="l-main" id="main">

        <article class="c-article">

            <h1 class="c-h1 u-mb--md">
                <div class="c-section_inner">ナントカと猫企画への問い合わせ<i>get in touch</i></div>
            </h1>


            <?php

            if(have_posts()) :

            $postType =  get_post_type_object( get_post_type() )->label;

            ?>
            <div class="c-section_inner u-mt--ex">

                <article class="c-media">

                   <ul class="f-container">

                        <?php

                        while(have_posts()): the_post();

                        ?>
                        <li class="f-item-sm-12 f-item-md-6 u-mb--md">
                            <div class="">
                                <a class="c-btn c-btn--primary f-middle-center" href="<?php the_permalink(); ?>"><?php the_title(); ?><i class="fa fa-paper-plane-o" aria-hidden="true"></i></a>
                            </div>
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


    <?php get_footer(); ?>
