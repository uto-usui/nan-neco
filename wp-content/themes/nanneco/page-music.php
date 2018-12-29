<?php get_header(); ?>


      
      <article class="hero_wrap">
        <a class="hero hero--sub f-flex f-middle f-center js-page-scroll" href="#content" style="background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/home/11.jpg)">
          <div class="hero_inner" id="js-scroll">
            <h1 class="hero_text">music</h1>
          </div>
        </a>
      </article>
      
      
      <!-- songs -->
      <article class="article" id="content">
        <div class="article_inner js-fadein-wrap" id="music">
          <h2 class="h1 js-fadein-item"><span>songs</span></h2>
          
          <ul class="home_songs">
            <li class="home_songs-item js-song-item">
              <div class="home_audio-bar-wrap js-audio-bar-wrap">
                <div class="home_audio-bar js-audio-bar"></div>
              </div>
              <h3 class="h2 f-flex f-between f-middle">かぞえうた
                <button class="btn btn--song js-song-trigger">play</button>
                <audio class="js-song" src="<?php echo get_stylesheet_directory_uri();?>/assets/music/kazoeuta.mp3"></audio>
              </h3>
            </li>
            <li class="home_songs-item js-song-item">
              <div class="home_audio-bar-wrap js-audio-bar-wrap">
                <div class="home_audio-bar js-audio-bar"></div>
              </div>
              <h3 class="h2 f-flex f-between f-middle">すききらい
                <button class="btn btn--song js-song-trigger">play</button>
                <audio class="js-song" src="<?php echo get_stylesheet_directory_uri();?>/assets/music/sukikirai.mp3"></audio>
              </h3>
            </li>
            <li class="home_songs-item js-song-item">
              <div class="home_audio-bar-wrap js-audio-bar-wrap">
                <div class="home_audio-bar js-audio-bar"></div>
              </div>
              <h3 class="h2 f-flex f-between f-middle">スプーンで掬った月
                <button class="btn btn--song js-song-trigger">play</button>
                <audio class="js-song" src="<?php echo get_stylesheet_directory_uri();?>/assets/music/tuki.mp3"></audio>
              </h3>
            </li>
          </ul>

        </div>

      </article>


      <!-- movie -->
      <article class="article">
        <div class="article_inner js-fadein-wrap">
          <h2 class="h1 js-fadein-item"><span>movie</span></h2>

          <div class="home_movie" id="js-movie-section">
            <section class="home_movie-inner js-movie-wrap">
              <h3 class="h2 u-text-left">music video : 「かぞえうた」</h3>
              <div class="home_movie-item">
                <video class="js-movie" muted loop poster="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/movie/kazoeuta.jpg">
                  <source src="<?php echo get_stylesheet_directory_uri();?>/assets/movie/kazoeuta.mp4">
                  <p>動画を再生するには、videoタグをサポートしたブラウザーが必要です。</p>
                </video>
              </div>
              <p class="home_movie-link-wrap f-flex f-between f-middle">
                <button class="btn btn--song js-movie-mute">sound</button>
                <a class="home_movie-link btn--arrow_wrap" href="https://www.youtube.com/watch?v=Mgl-EP-9RLg" target="_blank"> youtube page　<span class="btn btn--arrow btn--arrow-next"></span></a>
              </p>
            </section>
            <section class="home_movie-inner js-movie-wrap">
              <h3 class="h2 u-text-left">oneman live : 「蒼眼鏡、バースデー」</h3>
              <div class="home_movie-item">
                <video class="js-movie" muted loop poster="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/movie/oneman.jpg">
                  <source src="<?php echo get_stylesheet_directory_uri();?>/assets/movie/orikou.mp4">
                  <p>動画を再生するには、videoタグをサポートしたブラウザーが必要です。</p>
                </video>
              </div>
              <p class="home_movie-link-wrap f-flex f-between f-middle">
                <button class="btn btn--song js-movie-mute">sound</button>
                <a class="home_movie-link btn--arrow_wrap" href="https://www.youtube.com/watch?v=vOQmhmh-w5U&list=PLYO908eHx1ffjBbJEoXwrpMiYefpR4nDe" target="_blank"> youtube page　<span class="btn btn--arrow btn--arrow-next"></span></a>
              </p>
            </section>
          </div>

        </div>


      </article>
        
      </div>




    <?php get_footer(); ?>
