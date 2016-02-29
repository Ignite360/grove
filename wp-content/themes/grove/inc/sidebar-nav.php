<?php
global $post;
	if($post->post_parent) {
		$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0&sort_column=menu_order&depth=1");
	}  else {
		$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0&sort_column=menu_order&depth=1");
	}

	if ($children) { ?>
		
		<aside id="sub-nav" class="widget widget_navigation">
			<?php if(!$post->post_parent) { ?>
				<h1 class="widget-title parent_page_item"><a href=""><?php the_title() ?></a></h1>
			<?php } else { ?>
				<h1 class="widget-title parent_page_item"><a href="<?php echo get_permalink($post->post_parent); ?>"><?php echo get_the_title($post->post_parent); ?></a></h1>
			<?php } ?>
			<ul>	
			<?php echo $children; ?>
		</ul>
		</aside>
<?php } ?>