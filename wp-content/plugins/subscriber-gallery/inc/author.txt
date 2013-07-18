<?php get_header(); ?>

<div id="content" role="main">

    <?php
    $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
    ?>

    <h2><?php echo $curauth->display_name; if($curauth->biz_name != '') { echo ' of <strong>'.$curauth->biz_name.'</strong>'; } ?></h2>
    <p>Website: <a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></p>
    <p>Profile:<br/><?php echo $curauth->user_description; ?></p>

</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>