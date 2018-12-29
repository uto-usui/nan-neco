<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- favicon -->
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/favicon.ico" type="image/vnd.microsoft.icon">
  <link rel="icon" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/favicon.ico" type="image/vnd.microsoft.icon">
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/apple-touch-icon-180x180.png">
  <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/android-chrome-192x192.png" sizes="192x192">
  <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/favicon-48x48.png" sizes="48x48">
  <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/favicon-96x96.png" sizes="96x96">
  <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/favicon-16x16.png" sizes="16x16">
  <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/favicon-32x32.png" sizes="32x32">
  <link rel="manifest" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/manifest.json">
  <meta name="msapplication-TileColor" content="#2d88ef">
  <meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicons/mstile-144x144.png">
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-93805538-1', 'auto');
    ga('send', 'pageview');
  </script>
  <?php
    if (( get_post_type() != 'contact')) {
      wp_deregister_script('jquery');
    }
    if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
      wpcf7_enqueue_scripts();
      wpcf7_enqueue_styles();
    }
    wp_head();
  ?>
  <?php get_template_part('ogp');?>
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:700|Nunito:400,800" rel="stylesheet">
</head>

<!-- header -->
  <body class="body">

    <div class="loader" id="js-loader">
      <span>n</span>
      <span>a</span>
      <span>n</span>
      <span>n</span>
      <span>e</span>
      <span>c</span>
      <span>o</span>
      <span>.</span>
    </div>

    <svg class="l-header_line" style="display: none">
      <symbol id="line" viewBox="0 0 28.64 5.82">
        <g>
          <line class="a" x1="0.35" y1="0.35" x2="5.47" y2="5.47"/>
          <line class="a" x1="4.92" y1="0.35" x2="10.03" y2="5.47"/>
          <line class="a" x1="9.48" y1="0.35" x2="14.6" y2="5.47"/>
          <line class="a" x1="14.05" y1="0.35" x2="19.16" y2="5.47"/>
          <line class="a" x1="18.61" y1="0.35" x2="23.73" y2="5.47"/>
          <line class="a" x1="23.17" y1="0.35" x2="28.29" y2="5.47"/>
        </g>
      </symbol>
    </svg>

    <div class="border">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>

    <div class="wrap" id="wrap">

    <header class="l-header f-middle f-between" id="header">

      <div class="l-header_inner">
        <h1 class="l-header_title">
          <p class="sr-only">ナントカと猫企画</p>
          <a href="<?php echo home_url('/'); ?>"><svg viewBox="0 0 662.43 84.53">
              <g>
                <g>
                  <path class="a" d="M174.61,68.68c-6.22,2.44-8.29-4.88-3.48-6.66,12.88-4.81,18.72-14.73,19.24-25.38-6.81.07-13.62.37-20.28.59-5.25.22-6.07-6.88-.22-7.1,6.59-.22,13.47-.37,20.43-.44-.07-4.29-.22-8.44-.3-11-.22-5.55,7.33-4.88,7.7-.52s.3,7.92.3,11.4c5.92-.07,11.69-.07,17.24,0,3,.07,4.37,1.92,4.29,3.77s-1.63,3.7-4.59,3.55c-5.48-.22-11.17-.3-16.87-.3C197.62,50.33,189.78,62.69,174.61,68.68Z"/>
                  <path class="a" d="M244.46,35.08a27.3,27.3,0,0,0-11-5.48c-4.44-1-2.59-8.51,3-7,3.92,1.11,9.47,3.11,13,5.63a4.38,4.38,0,0,1,1,6.14A3.92,3.92,0,0,1,244.46,35.08Zm-4.59,30.56c-6.29,2-8.66-6.44-2.52-8,14.88-3.77,29.82-19.09,37.3-33.6,2.37-4.59,10-1.48,6.74,4.37C272.22,45.07,256.9,60.24,239.88,65.65Z"/>
                  <path class="a" d="M307.37,67.92c.15,6-8.29,6.22-8,.3.52-12,.74-41,.44-51.66-.15-5,7.84-5,7.7.07-.07,3.63-.15,10.06-.22,17.32a7.59,7.59,0,0,1,1.26.07c4.14.67,13.76,3,17.76,6.44s-.37,9.69-4.59,6.59c-3.18-2.37-10.51-5.25-14.43-6C307.22,51.27,307.22,61.92,307.37,67.92Z"/>
                  <path class="a" d="M348.43,33.75c-5.92.3-5.48-6.44-.52-6.66,4.22-.15,9.4-.3,14.73-.37a74.28,74.28,0,0,0,.59-9.77c-.07-5.62,7.84-5,7.77.3a62.17,62.17,0,0,1-.74,9.4c3,0,5.92,0,8.51.07,5.77.15,9.92,3.18,9.77,9.18-.22,7.47-1.63,18.21-4.74,23.76-4.59,8.14-14.58,10.43-21.83,4.44-3.77-3.11.15-9.55,4.74-6.07,4,3,7.77,1.7,10.36-2.07,2.89-4.29,4-14.5,4.07-18.35a4,4,0,0,0-4.07-4.37c-2.29-.07-5.11,0-8.07,0-3.11,13.47-10.21,28.12-23.39,35.3-4.74,2.59-9.4-3.55-3.48-6.73,10.14-5.48,16.36-16.73,19.24-28.49C356.72,33.38,352.13,33.6,348.43,33.75Z"/>
                  <path class="a" d="M413.08,31.92c-3.12-3.84,2.76-7.08,5.16-3.66a60.88,60.88,0,0,1,7,14,114.53,114.53,0,0,1,10.56-6.6c4.08-2.28,7.2,3.48,2.58,5.82-6.12,3.12-12.3,6.6-17,10.62-5.94,5-6.12,9.54-1.44,11.82,4.2,2.1,12.78.54,16.38-.78,5-1.92,6.84,5,1.5,6.48-4.74,1.32-14.76,2.52-20.7-.48-8.64-4.38-8.28-14.1,1.44-21.84.6-.48,1.14-1,1.74-1.38C418.72,41.16,415.84,35.28,413.08,31.92Z"/>
                  <path class="a" d="M468.53,53.22a3.13,3.13,0,0,1-2.1.77A3.3,3.3,0,0,1,463.06,51a2.91,2.91,0,0,1,1.4-2.31,48.86,48.86,0,0,0,12.18-13.86,55.79,55.79,0,0,0-1.82-5.81c-6,5.81-7.21,6-8.26,6a3.23,3.23,0,0,1-3.29-2.94,2.79,2.79,0,0,1,1.54-2.38,42.91,42.91,0,0,0,7.56-6.3,31.25,31.25,0,0,0-4.13-6,3.52,3.52,0,0,1-1-2.38,3.18,3.18,0,0,1,3.15-3.15c1.26,0,3.08.7,6.51,6.37a40.44,40.44,0,0,0,3.22-4.69,2.67,2.67,0,0,1,2.38-1.4,3.36,3.36,0,0,1,3.36,3.08c0,.84-.35,2.31-6.16,8.82a68.87,68.87,0,0,1,4.9,25.06c0,16.59-6.23,21.07-13.44,21.07-5.46,0-6.44-1.47-6.44-3.64,0-1.61,1-3.36,2.8-3.36a3.49,3.49,0,0,1,.84.14,10,10,0,0,0,2.73.42c2.59,0,4.48-1.33,5.6-4.34a33.55,33.55,0,0,0,1.68-11,53.74,53.74,0,0,0-.35-5.74A63.4,63.4,0,0,1,468.53,53.22Zm46.06-27.58v5.53c0,2-1.68,2.94-3.36,2.94a2.82,2.82,0,0,1-3.08-2.73V25.56h-6.37v5.6c0,2-1.68,2.94-3.36,2.94a2.82,2.82,0,0,1-3.08-2.73V25.63l-6.3.07H489a3,3,0,0,1-3.08-3.08A2.93,2.93,0,0,1,489,19.54H489l6.3.07V13.52c0-1.89,1.61-2.8,3.22-2.8s3.22.91,3.22,2.8v6.16h6.37V13.52c0-1.89,1.61-2.8,3.22-2.8s3.22.91,3.22,2.8v6.09l7.84-.07a2.91,2.91,0,0,1,3,3.08,2.92,2.92,0,0,1-3,3.08Zm-.21,44.87c-3.08.14-6,.21-8.82.21-3.29,0-6.44-.07-9.59-.21-5.11-.21-7.63-3-7.77-7.07-.14-3.08-.21-6.51-.21-9.87s.07-6.86.21-9.87c.14-3.36,2.24-6.79,7.84-7,2.94-.14,5.88-.14,8.89-.14s6.16,0,9.45.14c4.9.14,7.77,3.36,7.91,6.93s.21,7.21.21,11c0,2.94-.07,6-.21,8.75C522.08,67.22,519.07,70.3,514.38,70.51Zm-12.6-28.42c-1.47,0-2.87,0-4.2.07-3.43.07-3.43,2.1-3.43,8.26h7.63Zm0,13.86h-7.63c0,2,.07,4.06.14,6.09.14,3,2.31,3,7.49,3Zm14.35-5.53c0-1.82,0-3.57-.07-5.32-.14-3-1.82-3-8-3v8.33Zm-8.05,5.53V65c6.09,0,7.91,0,8-3.08.07-2,.07-4,.07-6Z"/>
                  <path class="a" d="M591.17,41.18a4.36,4.36,0,0,1-2.45-.77c-6.44-4.34-15.68-13.3-21.84-20.58-.77-.91-1.4-1.33-2-1.33s-1.26.42-2,1.33A140.54,140.54,0,0,1,541,42.08a5.14,5.14,0,0,1-3.29,1.26,3.88,3.88,0,0,1-3.92-3.71,3.55,3.55,0,0,1,2-3.08c7.7-4.69,17-14.56,22.19-21.56a8,8,0,0,1,6.37-3.43A9.2,9.2,0,0,1,571.15,15,102.62,102.62,0,0,0,594,34.24a3.11,3.11,0,0,1,1.61,2.66A4.56,4.56,0,0,1,591.17,41.18ZM539.71,69.11A3.14,3.14,0,0,1,536.35,66a3.11,3.11,0,0,1,3.36-3.15h7.14V44.68a3.47,3.47,0,0,1,6.93,0V62.81h7.91V31.51a3.5,3.5,0,0,1,7,0v13H583a3,3,0,0,1,3,3.15,2.93,2.93,0,0,1-3,3.15H568.7v12h21A3,3,0,0,1,592.78,66a3,3,0,0,1-3.08,3.08Z"/>
                  <path class="a" d="M637.65,21.36v5.32c1.75,0,3.71,0,6,.07,4.06.07,7,3.08,7.14,6.09.14,2.17.14,5.81.14,9.45s0,7.28-.14,9.31c-.14,3.64-3,6.79-7.49,6.93-4,.14-6,.21-8,.21-2.24,0-4.34-.07-8.89-.21-4.9-.14-7.63-3-7.77-6.86-.07-2.31-.14-5.88-.14-9.38s.07-6.93.14-8.89c.14-3.15,2.1-6.51,7.42-6.65,2-.07,3.57-.14,5.18-.14V21.36H610.56a3,3,0,0,1-3.29-3.08A3.24,3.24,0,0,1,610.56,15h48.72a3.06,3.06,0,0,1,3.15,3.22,3,3,0,0,1-3.15,3.15ZM614.34,33.05c-.07,4.41-.14,9.94-.14,15.19,0,3.78,0,7.49.14,10.36s1.12,4.34,4.41,4.48c4.13.14,9.94.21,15.82.21s11.62-.07,15.61-.21c2.8-.14,4.55-1.54,4.69-4.27.14-3.5.21-7.63.21-11.9,0-4.76-.07-9.59-.21-13.65v-.14A3.15,3.15,0,0,1,658.3,30a3.33,3.33,0,0,1,3.57,3.22c.14,3.08.14,7.77.14,12.67s0,10.08-.14,14.21c-.21,6-4.76,9.1-9.94,9.24-4.83.21-11.48.28-18,.28s-12.88-.07-17.22-.28c-5.74-.21-9-3.22-9.1-9.1-.07-4.48-.14-9.52-.14-14.63,0-4.27.07-8.54.14-12.6a3.19,3.19,0,0,1,3.29-3.29A3.25,3.25,0,0,1,614.34,33Zm16.94-1.19-3.5.07c-2.17.07-3,1.33-3.08,2.8-.07,1.12-.07,2.94-.07,5h6.65Zm0,12.88h-6.65c0,2.31,0,4.41.07,5.74.07,2.66,1.05,2.8,6.58,2.8Zm13.37-5c0-2,0-3.78-.07-5-.07-2.73-1.75-2.87-6.93-2.87V39.7Zm-3.08,13.51c1.82,0,2.94-.63,3-2.8.07-1.26.07-3.36.07-5.67h-7v8.54Z"/>
                </g>
                <g>
                  <circle class="b" cx="96.99" cy="42.39" r="36.72"/>
                  <circle class="c" cx="42.27" cy="42.27" r="39.77"/>
                </g>
              </g>
            </svg></a>
        </h1>
        <a class="l-header_trigger" id="js-nav-trigger" href="#">
          <span></span>
          <span></span>
          <span></span>
        </a>
      </div>

      <nav class="l-heaver_nav js-nav-target">
        <ul class="l-heaver_nav-list">
          <li class="l-heaver_nav-item">
            <a target="_blank" href="<?php echo home_url('/') ?>" class="l-heaver_nav-target f-middle-center">Home</a>
          </li>
          <li class="l-heaver_nav-item">
            <a target="_blank" href="https://nantokatoneko.jimdo.com/%E7%90%86%E5%BF%B5/" class="l-heaver_nav-target f-middle-center">About</a>
          </li>
          <li class="l-heaver_nav-item">
            <a href="<?php echo home_url('/event/'); ?>" class="l-heaver_nav-target f-middle-center">Event</a>
          </li>
          <li class="l-heaver_nav-item">
            <a target="_blank" href="http://otonohapro.exblog.jp/" class="l-heaver_nav-target f-middle-center">Blog</a>
          </li>
          <li class="l-heaver_nav-item">
            <a href="<?php echo home_url('/contacts/'); ?>" class="l-heaver_nav-target f-middle-center">Contact</a>
          </li>
          <li class="l-heaver_nav-item">
            <a target="_blank" href="https://nanneco.thebase.in/" class="l-heaver_nav-target f-middle-center">Shop</a>
          </li>
        </ul>
        <div class="l-heaver_nav-overlay js-nav-close"></div>
      </nav>

    </header>
