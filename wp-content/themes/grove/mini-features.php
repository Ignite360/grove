<?php 
		$mini_show = array();
		
			if (get_option( 'mini_1_link' )!="http://" AND get_option( 'mini_1_link' )!="") { $mini_show[] = 1; }
			if (get_option( 'mini_2_link' )!="http://" AND get_option( 'mini_2_link' )!="") { $mini_show[] = 2; }
			if (get_option( 'mini_3_link' )!="http://" AND get_option( 'mini_3_link' )!="") { $mini_show[] = 3; }
			if (get_option( 'mini_4_link' )!="http://" AND get_option( 'mini_4_link' )!="") { $mini_show[] = 4; }
			if (get_option( 'mini_5_link' )!="http://" AND get_option( 'mini_5_link' )!="") { $mini_show[] = 5; }

		$count=count($mini_show);

		if ($count > 0) {
			echo '<div class="minifeatures-outer"><div class="minifeatures minifeatures-'.$count.'">';
		}

		foreach ($mini_show as $number) {
			
			$title=get_option( 'mini_'.$number.'_title' );
			$link=get_option( 'mini_'.$number.'_link' );
			$image=get_option( 'mini_'.$number.'_image' );
			$excerpt=get_option( 'mini_'.$number.'_excerpt' );
			
			if ($title AND $image AND $excerpt) {

				echo '<a href="'.$link.'"><img src="'.$image.'"><strong>'.$title.'</strong><span>'.$excerpt.'</span></a>';

			} elseif ($title AND $image AND !$excerpt) {

				echo '<a href="'.$link.'"><img src="'.$image.'"><strong>'.$title.'</strong></a>';
			
			} elseif ($title AND !$image AND $excerpt) {

				echo '<a href="'.$link.'"><strong>'.$title.'</strong><span>'.$excerpt.'</span></a>';

			} elseif ($title AND !$image AND !$excerpt) {

				echo '<a href="'.$link.'"><strong>'.$title.'</strong></a>';

			} elseif (!$title AND $image AND $excerpt) {

				echo '<a href="'.$link.'"><img src="'.$image.'"><span>'.$excerpt.'</span></a>';

			} elseif (!$title AND $image AND !$excerpt) {

				echo '<a href="'.$link.'"><img src="'.$image.'"></a>';

			}

		}

		if ($count > 0) {
			echo '</div></div>';
		}

 ?>