
<?php if (is_single()): ?>

  <meta property="og:type" content="article">

  <?php if(have_posts()): while(have_posts()): the_post(); ?>

    <meta name="description" content="<?php the_excerpt(); ?>">
    <meta property="og:description" content="<?php the_excerpt(); ?>">
    <meta name="twitter:description" content="<?php the_excerpt(); ?>">

  <?php endwhile; endif; ?>

  <meta property="og:title" content="<?php the_title(); ?>">
  <meta name="twitter:title" content="<?php the_title(); ?>">
  <meta property="og:url" content=" <?php the_permalink(); ?>">


  <?php if(has_post_thumbnail()):

    $image_id = get_post_thumbnail_id();
    $image = wp_get_attachment_image_src( $image_id, 'large');

  ?>

    <meta property="og:image" content="<?php echo $image[0] ?>">
    <meta name="twitter:image" content="<?php echo $image[0] ?>">

  <?php else:
    $ogp_image = get_stylesheet_directory_uri(). '/screenshot.png';
  ?>


    <meta property="og:image" content="<?php echo $ogp_image ?>">
    <meta name="twitter:image" content="<?php echo $ogp_image ?>">

  <?php endif; ?>


<?php else: ?>

  <meta property="og:type" content="website">
  <meta name="description" content="<?php bloginfo('description'); ?>">
  <meta property="og:description" content="<?php bloginfo('description'); ?>">
  <meta name="twitter:description" content="<?php bloginfo('description'); ?>">
  <meta property="og:title" content="<?php bloginfo('name'); ?>">
  <meta name="twitter:title" content="<?php bloginfo('name'); ?>">
  <meta property="og:url" content="<?php bloginfo('url'); ?>">
  <?php
    $ogp_image = get_stylesheet_directory_uri(). '/screenshot.png';
  ?>
  <meta property="og:image" content="<?php echo $ogp_image ?>">
  <meta name="twitter:image" content="<?php echo $ogp_image ?>">



<?php endif; ?>




<meta property="og:site_name" content="<?php bloginfo('name'); ?>">
<meta property="og:locale" content="ja_JP">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@otonoha2013oton">




