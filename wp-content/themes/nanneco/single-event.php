<?php get_header(); ?>



<?php
while (have_posts()): the_post();
    ?>


  <main class="l-main" id="main">

    <article class="c-article">

        <?php

        $date = get_field('event-date-time');
        //
        $lead = get_field('event-lead');
        //
        $latitude = get_field('event-access-latitude');
        $longitude = get_field('event-access-longitude');
        $url = get_field('event-access-url');
        $name = get_field('event-access-name');

        ?>

      <h1 class="c-h1">
        <div class="c-section_inner"><?php the_title(); ?><i><?php echo $date; ?></i></div>
      </h1>


        <?php if ($lead) { ?>
          <h2 class="c-h2"><span class="c-h2_inner">message</span></h2>
          <div class="c-hero-sub">
            <div class="c-hero-sub_inner f-middle-center">
              <figure class="c-hero-sub_figure" data-parallax=".9"
                      style="background-image: url(<?php the_post_thumbnail_url(); ?>);"></figure>
              <div class="c-hero-sub_caption">
                  <?php echo $lead; ?>
              </div>
            </div>
          </div>
        <?php } ?>


        <?php

        if (have_rows('event-venue-list')) {

            ?>
          <section class="c-section_inner">
            <h2 class="c-h2"><span class="c-h2_inner">time table</span></h2>

            <p class="c-article_lead">会場ごとのタイムテーブルを閲覧できます。「＋」で詳細を確認します。</p>

            <div class="c-tab js-tab">

              <ul class="c-tab_nav f-flex f-wrap f-around">
                  <?php

                  $i = 1;

                  while (have_rows('event-venue-list')) {
                      the_row();
                      $id_name = '#panel0' . $i;

                      ?>

                    <li class="c-tab_nav-item">
                      <a href="<?php echo $id_name ?>"
                         class="c-tab_nav-target js-tab-trigger<?php if ($i == 1) { ?> is-active<?php } ?>"><?php the_sub_field('event-venue'); ?></a>
                    </li>
                      <?php
                      $i = $i + 1;
                  }
                  ?>
              </ul>

                <?php

                if (have_rows('event-timetable')) {

                    $i = 1;

                    while (have_rows('event-timetable')) {

                        the_row();
                        $id_name = 'panel0' . $i;

                        ?>
                      <ul class="c-tab_panel">
                        <li class="c-tab_panel-inner js-tab-item <?php if ($i == 1) { ?> is-open<?php } ?>"
                            id="<?php echo $id_name ?>">
                          <ul class="c-tab_list">
                              <?php

                              if (have_rows('event-timetable-list')) {

                                  while (have_rows('event-timetable-list')) {
                                      the_row();

                                      ?>
                                    <li class="c-tab_item">
                                      <time class="c-tab_time"><?php the_sub_field('event-time'); ?></time>
                                      <h3 class="c-tab_title c-h3">
                                        <div class="c-h3_inner"><?php the_sub_field('event-title'); ?></div>
                                      </h3>
                                      <button class="c-btn c-btn--toggle js-dropdown-trigger"></button>
                                      <div class="c-tab_textarea js-dropdown-item">
                                          <?php
                                          $img = get_sub_field('event-artist-img');
                                          $imgurl = wp_get_attachment_image_src($img, 'full');
                                          if ($imgurl) {
                                          ?>
                                        <div class="f-container f-middle">
                                          <div class="f-item-md-4 f-item-sm-3 f-item-xs-12">
                                            <figure class="c-tab_figure">
                                              <img src="<?php echo esc_attr($imgurl[0]); ?>" alt="">
                                            </figure>
                                          </div>
                                          <div class="f-item-md-8 f-item-sm-9 f-item-xs-12">
                                            <p class="c-article_text">
                                                <?php the_sub_field('event-detail'); ?>
                                            </p>
                                          </div>
                                            <?php } else { ?>
                                              <p class="c-article_text">
                                                  <?php the_sub_field('event-detail'); ?>
                                              </p>
                                                <?php if (get_sub_field('event-ticket-link')) { ?>
                                                <p class="c-article_text">
                                                  <a class="c-article_link js-scroll" href="#ticket">チケットの購入はこちら</a>
                                                </p>
                                                    <?php

                                                }
                                            }

                                            ?>
                                        </div>
                                    </li>
                                      <?php
                                  }
                              }
                              ?>
                          </ul>
                        </li>
                      </ul>
                        <?php
                        $i = $i + 1;
                    }
                }
                ?>

            </div>
          </section>
            <?php
        }
        ?>


        <?php

        if (have_rows('event-shop-group')) {

            ?>
          <section class="c-section_inner">

            <h2 class="c-h2"><span class="c-h2_inner">shop</span></h2>

            <div class="c-tab js-tab">

              <ul class="c-tab_nav f-flex f-wrap f-around">
                  <?php

                  $i = 1;

                  while (have_rows('event-shop-group')) {
                      the_row();
                      $id_name = '#panel1' . $i;

                      ?>
                    <li class="c-tab_nav-item">
                      <a href="<?php echo $id_name; ?>"
                         class="c-tab_nav-target js-tab-trigger<?php if ($i == 1) { ?> is-active<?php } ?>"><?php the_sub_field('event-shop-label'); ?></a>
                    </li>
                      <?php

                      $i = $i + 1;
                  }

                  ?>
              </ul>

              <ul class="c-tab_panel">

                  <?php

                  $j = 1;

                  while (have_rows('event-shop-group')) {
                      the_row();
                      $id_name = 'panel1' . $j;

                      ?>
                    <li class="c-tab_panel-inner js-tab-item<?php if ($j == 1) { ?> is-open<?php } ?>"
                        id="<?php echo $id_name; ?>">
                      <ul class="c-memo-list f-container u-text-left">

                          <?php

                          if (have_rows('event-shop-list')) {

                              while (have_rows('event-shop-list')) {
                                  the_row();

                                  ?>
                                <li class="c-memo-list_item f-item-sm-12 f-item-md-6">
                                  <div class="c-memo-list_item-inner">
                                    <h3 class="c-memo-list_title"><?php the_sub_field('event-shop-title'); ?></h3>
                                    <b class="c-memo-list_lead"><?php the_sub_field('event-shop-detail'); ?></b>
                                  </div>
                                </li>
                                  <?php

                              }
                          }

                          ?>

                      </ul>
                    </li>
                      <?php

                      $j = $j + 1;
                  }

                  ?>


              </ul>

            </div>

          </section>
            <?php
        }
        ?>


        <?php

        if (have_rows('event-ticket-list')) {

            ?>
          <section class="c-section_inner" id="ticket">
            <h2 class="c-h2"><span class="c-h2_inner">ticket</span></h2>

            <p class="c-article_lead">チケット購入ページへジャンプします。</p>

            <ul class="f-container">

                <?php

                $i = 1;

                while (have_rows('event-ticket-list')) {
                    the_row();

                    ?>
                  <li class="f-item-sm-12 f-item-md-6 u-mb--md">
                    <div class="">
                      <a class="c-btn c-btn--primary f-middle f-in-flex"
                         href="<?php the_sub_field('event-ticket-link'); ?>"
                         target="_blank"><?php the_sub_field('event-ticket-title'); ?>
                        <i class="fa fa-ticket" aria-hidden="true"></i></a>
                      <p class="c-article_text u-mt--sm"><?php the_sub_field('event-ticket-fee'); ?></p>
                    </div>
                  </li>
                    <?php

                }

                ?>

            </ul>

          </section>
            <?php

        }

        ?>



        <?php

        if (have_rows('event-access-list')) {

            ?>
          <section class="c-section_inner">
            <h2 class="c-h2"><span class="c-h2_inner">access</span></h2>

            <div class="c-article_lead u-text-left">

                <?php

                $i = 1;

                while (have_rows('event-access-list')) {
                    the_row();

                    ?>
                  <h3 class="c-h4">
                    <div class="c-h4_inner"><?php the_sub_field('event-access-traffic'); ?></div>
                  </h3>
                    <?php the_sub_field('event-access-way'); ?>
                    <?php

                }

                ?>

            </div>

            <div class="c-article_map" id="js-map"></div>

            <script>
              var googleMapValue = {
                latitude: <?php the_field('event-access-latitude') ?>,
                longitude: <?php the_field('event-access-longitude') ?>,
                url: '<?php the_field('event-access-url') ?>',
                name: '<?php the_field('event-access-name') ?>'
              }
            </script>

          </section>
            <?php

        }

        ?>


        <?php

        if (have_rows('event-sponsors-list')) {

            ?>
          <section class="c-section_inner">
            <h2 class="c-h2"><span class="c-h2_inner">Sponsors</span></h2>

            <ul class="c-article_list">

                <?php

                while (have_rows('event-sponsors-list')) {
                    the_row();

                    ?>
                  <li class="c-article_item">
                    <h3 class="c-h3">
                      <div class="c-h3_inner">
                          <?php the_sub_field('event-sponsors-title'); ?>
                      </div>
                    </h3>
                    <a class="c-btn c-btn--primary f-middle-center"
                       href="<?php the_sub_field('event-sponsors-link'); ?>" target="_blank"><i
                              class="fa fa-2x fa-home u-ml--sm"></i></a>
                    <br>
                      <?php the_sub_field('event-sponsors-text'); ?>
                  </li>
                    <?php

                }

                ?>

            </ul>
          </section>
            <?php

        }

        ?>


      <section class="c-section_inner">
          <?php

          $postType = get_post_type_object(get_post_type())->label;
          $prev_post = get_previous_post();
          $next_post = get_next_post();

          ?>
        <style>
          <?php if (!$prev_post) { ?>
          .c-pager_item--prev {
            visibility: hidden;
          }

          <?php } ?>
          <?php if (!$next_post) { ?>
          .c-pager_item--next {
            visibility: hidden;
          }

          <?php } ?>
        </style>
        <div class="c-pager">
          <ul class="c-pager_list f-flex">
            <li class="c-pager_item c-pager_item--prev">
              <a href="<?php echo get_permalink($prev_post->ID); ?>"
                 class="c-btn c-btn--primary c-pager_target f-middle-center">prev</a>
            </li>
            <li class="c-pager_item">
              <a href="<?php echo get_post_type_archive_link($postType); ?>"
                 class="c-btn c-btn--primary c-pager_target f-middle-center"><i class="fa fa-th"></i></a>
            </li>
            <li class="c-pager_item c-pager_item--next">
              <a href="<?php echo get_permalink($prev_post->ID); ?>"
                 class="c-btn c-btn--primary c-pager_target f-middle-center">next</a>
            </li>
          </ul>
        </div>
      </section>


    </article>

  </main>


<?php endwhile; ?>






<?php get_footer(); ?>
