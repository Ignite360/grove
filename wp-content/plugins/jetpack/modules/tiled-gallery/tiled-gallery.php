<?php

// Include the class file containing methods for rounding constrained array elements.
// Here the constrained array element is the dimension of a row, group or an image in the tiled gallery.
include_once dirname( __FILE__ ) . '/math/class-constrained-array-rounding.php';

// Layouts
include_once dirname( __FILE__ ) . '/tiled-gallery/tiled-gallery-rectangular.php';
include_once dirname( __FILE__ ) . '/tiled-gallery/tiled-gallery-square.php';
include_once dirname( __FILE__ ) . '/tiled-gallery/tiled-gallery-circle.php';

class Jetpack_Tiled_Gallery {
	private static $talaveras = array( 'rectangular', 'square', 'circle', 'rectangle', 'columns' );

	public function __construct() {
		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		add_filter( 'jetpack_gallery_types', array( $this, 'jetpack_gallery_types' ), 9 );
		add_filter( 'jetpack_default_gallery_type', array( $this, 'jetpack_default_gallery_type' ) );
	}

	public function tiles_enabled() {
		// Check the setting status
		return '' != get_option( 'tiled_galleries' );
	}

	public function set_atts( $atts ) {
		global $post;

		$this->atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => isset( $post->ID ) ? $post->ID : 0,
			'include'    => '',
			'exclude'    => '',
			'type'       => '',
			'grayscale'  => false,
			'link'       => '',
			'columns'	 => 3
		), $atts, 'gallery' );

		$this->atts['id'] = (int) $this->atts['id'];
		$this->float = is_rtl() ? 'right' : 'left';

		// Default to rectangular is tiled galleries are checked
		if ( $this->tiles_enabled() && ( ! $this->atts['type'] || 'default' == $this->atts['type'] ) )
			$this->atts['type'] = 'rectangular';

		if ( !$this->atts['orderby'] ) {
			$this->atts['orderby'] = sanitize_sql_orderby( $this->atts['orderby'] );
			if ( !$this->atts['orderby'] )
				$this->atts['orderby'] = 'menu_order ID';
		}

		if ( 'rand' == strtolower( $this->atts['order'] ) ) {
			$this->atts['orderby'] = 'rand';
		}

		// We shouldn't have more than 20 columns.
		if ( ! is_numeric( $this->atts['columns'] ) || 20 < $this->atts['columns'] ) {
			$this->atts['columns'] = 3;
		}
	}

	public function get_attachments() {
		extract( $this->atts );

		if ( !empty( $include ) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( 0 == $id ) {
			// Should NEVER Happen but infinite_scroll_load_other_plugins_scripts means it does
			// Querying with post_parent == 0 can generate stupidly memcache sets on sites with 10000's of unattached attachments as get_children puts every post in the cache.
			// TODO Fix this properly
			$attachments = array();
		} elseif ( !empty( $exclude ) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		}
		return $attachments;
	}

	public static function default_scripts_and_styles() {
		wp_enqueue_script( 'tiled-gallery', plugins_url( 'tiled-gallery/tiled-gallery.js', __FILE__ ), array( 'jquery' ) );
		if( is_rtl() ) {
			wp_enqueue_style( 'tiled-gallery', plugins_url( 'tiled-gallery/rtl/tiled-gallery-rtl.css', __FILE__ ), array(), '2012-09-21' );
		} else {
			wp_enqueue_style( 'tiled-gallery', plugins_url( 'tiled-gallery/tiled-gallery.css', __FILE__ ), array(), '2012-09-21' );
		}
	}

	public function gallery_shortcode( $val, $atts ) {
		if ( ! empty( $val ) ) // something else is overriding post_gallery, like a custom VIP shortcode
			return $val;

		global $post;

		$this->set_atts( $atts );

		$attachments = $this->get_attachments();
		if ( empty( $attachments ) )
			return '';

		if ( is_feed() || defined( 'IS_HTML_EMAIL' ) ) {
			return '';
		}

		if (
			in_array(
				$this->atts['type'],
				/**
				 * Filters the permissible Tiled Gallery types.
				 *
				 * @module tiled-gallery
				 *
				 * @since 3.7.0
				 *
				 * @param array Array of allowed types. Default: 'rectangular', 'square', 'circle', 'rectangle', 'columns'.
				 */
				$talaveras = apply_filters( 'jetpack_tiled_gallery_types', self::$talaveras )
			)
		) {
			// Enqueue styles and scripts
			self::default_scripts_and_styles();

			// Generate gallery HTML
			$gallery_class = 'Jetpack_Tiled_Gallery_Layout_' . ucfirst( $this->atts['type'] );
			$gallery = new $gallery_class( $attachments, $this->atts['link'], $this->atts['grayscale'], (int) $this->atts['columns'] );
			$gallery_html = $gallery->HTML();

			if ( $gallery_html && class_exists( 'Jetpack' ) && class_exists( 'Jetpack_Photon' ) ) {
				// Tiled Galleries in Jetpack require that Photon be active.
				// If it's not active, run it just on the gallery output.
				if ( ! in_array( 'photon', Jetpack::get_active_modules() ) && ! Jetpack::is_development_mode() )
					$gallery_html = Jetpack_Photon::filter_the_content( $gallery_html );
			}

			return trim( preg_replace( '/\s+/', ' ', $gallery_html ) ); // remove any new lines from the output so that the reader parses it better
		}

		return '';
	}

<<<<<<< HEAD
=======
	public function rectangular_talavera( $attachments ) {
		$grouper = new Jetpack_Tiled_Gallery_Grouper( $attachments );

		Jetpack_Tiled_Gallery_Shape::reset_last_shape();

		$output = $this->generate_carousel_container();
		foreach ( $grouper->grouped_images as $row ) {
			$output .= '<div class="gallery-row" style="' . esc_attr( 'width: ' . $row->width . 'px; height: ' . ( $row->height - 4 ) . 'px;' ) . '">';
			foreach( $row->groups as $group ) {
				$count = count( $group->images );
				$output .= '<div class="gallery-group images-' . esc_attr( $count ) . '" style="' . esc_attr( 'width: ' . $group->width . 'px; height: ' . $group->height . 'px;' ) . '">';
				foreach ( $group->images as $image ) {

					$size = 'large';
					if ( $image->width < 250 )
						$size = 'small';

					$image_title = $image->post_title;
					$orig_file = wp_get_attachment_url( $image->ID );
					$link = $this->get_attachment_link( $image->ID, $orig_file );

					$img_src = add_query_arg( array( 'w' => $image->width, 'h' => $image->height ), $orig_file );

					$output .= '<div class="tiled-gallery-item tiled-gallery-item-' . esc_attr( $size ) . '"><a href="' . esc_url( $link ) . '"><img ' . $this->generate_carousel_image_args( $image ) . ' src="' . esc_url( $img_src ) . '" width="' . esc_attr( $image->width ) . '" height="' . esc_attr( $image->height ) . '" align="left" title="' . esc_attr( $image_title ) . '" /></a>';

					if ( $this->atts['grayscale'] == true ) {
						$img_src_grayscale = jetpack_photon_url( $img_src, array( 'filter' => 'grayscale' ) );
						$output .= '<a href="'. esc_url( $link ) . '"><img ' . $this->generate_carousel_image_args( $image ) . ' class="grayscale" src="' . esc_url( $img_src_grayscale ) . '" width="' . esc_attr( $image->width ) . '" height="' . esc_attr( $image->height ) . '" align="left" title="' . esc_attr( $image_title ) . '" /></a>';
					}

					if ( trim( $image->post_excerpt ) )
						$output .= '<div class="tiled-gallery-caption">' . wptexturize( $image->post_excerpt ) . '</div>';

					$output .= '</div>';
				}
				$output .= '</div>';
			}
			$output .= '</div>';
		}
		$output .= '</div>';
		return $output;
	}

	public function square_talavera( $attachments ) {
		$content_width = self::get_content_width();
		$images_per_row = 3;
		$margin = 2;

		$margin_space = ( $images_per_row * $margin ) * 2;
		$size = floor( ( $content_width - $margin_space ) / $images_per_row );
		$remainder = count( $attachments ) % $images_per_row;
		if ( $remainder > 0 ) {
			$remainder_space = ( $remainder * $margin ) * 2;
			$remainder_size = ceil( ( $content_width - $remainder_space - $margin ) / $remainder );
		}
		$output = $this->generate_carousel_container();
		$c = 1;
		foreach( $attachments as $image ) {
			if ( $remainder > 0 && $c <= $remainder )
				$img_size = $remainder_size;
			else
				$img_size = $size;

			$orig_file = wp_get_attachment_url( $image->ID );
			$link = $this->get_attachment_link( $image->ID, $orig_file );
			$image_title = $image->post_title;

			$img_src = add_query_arg( array( 'w' => $img_size, 'h' => $img_size, 'crop' => 1 ), $orig_file );

			$output .= '<div class="tiled-gallery-item">';
			$output .= '<a border="0" href="' . esc_url( $link ) . '"><img ' . $this->generate_carousel_image_args( $image ) . ' style="' . esc_attr( 'margin: ' . $margin . 'px' ) . '" src="' . esc_url( $img_src ) . '" width=' . esc_attr( $img_size ) . ' height=' . esc_attr( $img_size ) . ' title="' . esc_attr( $image_title ) . '" /></a>';

			// Grayscale effect
			if ( $this->atts['grayscale'] == true ) {
				$src = urlencode( $image->guid );
				$output .= '<a border="0" href="' . esc_url( $link ) . '"><img ' . $this->generate_carousel_image_args( $image ) . ' style="margin: 2px" class="grayscale" src="' . esc_url( 'http://en.wordpress.com/imgpress?url=' . urlencode( $image->guid ) . '&resize=' . $img_size . ',' . $img_size . '&filter=grayscale' ) . '" width=' . esc_attr( $img_size ) . ' height=' . esc_attr( $img_size ) . ' title="' . esc_attr( $image_title ) . '" /></a>';
			}

			// Captions
			if ( trim( $image->post_excerpt ) )
				$output .= '<div class="tiled-gallery-caption">' . wptexturize( $image->post_excerpt ) . '</div>';
			$output .= '</div>';
			$c ++;
		}
		$output .= '</div>';
		return $output;
	}

	public function circle_talavera( $attachments ) {
		return $this->square_talavera( $attachments );
	}

	public function rectangle_talavera( $attachments ) {
		return $this->rectangular_talavera( $attachments );
	}

	function generate_carousel_container() {
		global $post;

		$html = '<div '. $this->gallery_classes() . ' data-original-width="' . esc_attr( self::get_content_width() ) . '">';
		$blog_id = (int) get_current_blog_id();

		if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
			$likes_blog_id = $blog_id;
		} else {
			$jetpack = Jetpack::init();
			$likes_blog_id = $jetpack->get_option( 'id' );
		}

		$extra_data = array( 'data-carousel-extra' => array( 'blog_id' => $blog_id, 'permalink' => get_permalink( $post->ID ), 'likes_blog_id' => $likes_blog_id ) );

		foreach ( (array) $extra_data as $data_key => $data_values ) {
			$html = str_replace( '<div ', '<div ' . esc_attr( $data_key ) . "='" . json_encode( $data_values ) . "' ", $html );
		}

		return $html;
	}

	function generate_carousel_image_args( $image ) {
		$attachment_id = $image->ID;
		$orig_file       = wp_get_attachment_url( $attachment_id );
		$meta            = wp_get_attachment_metadata( $attachment_id );
		$size            = isset( $meta['width'] ) ? intval( $meta['width'] ) . ',' . intval( $meta['height'] ) : '';
		$img_meta        = ( ! empty( $meta['image_meta'] ) ) ? (array) $meta['image_meta'] : array();
		$comments_opened = intval( comments_open( $attachment_id ) );

		$medium_file_info = wp_get_attachment_image_src( $attachment_id, 'medium' );
		$medium_file      = isset( $medium_file_info[0] ) ? $medium_file_info[0] : '';

		$large_file_info  = wp_get_attachment_image_src( $attachment_id, 'large' );
		$large_file       = isset( $large_file_info[0] ) ? $large_file_info[0] : '';
		$attachment_title = wptexturize( $image->post_title );
		$attachment_desc  = wpautop( wptexturize( $image->post_content ) );

        // Not yet providing geo-data, need to "fuzzify" for privacy
		if ( ! empty( $img_meta ) ) {
            foreach ( $img_meta as $k => $v ) {
                if ( 'latitude' == $k || 'longitude' == $k )
                    unset( $img_meta[$k] );
            }
        }

		$img_meta = json_encode( array_map( 'strval', $img_meta ) );

		$output = sprintf(
				'data-attachment-id="%1$d" data-orig-file="%2$s" data-orig-size="%3$s" data-comments-opened="%4$s" data-image-meta="%5$s" data-image-title="%6$s" data-image-description="%7$s" data-medium-file="%8$s" data-large-file="%9$s"',
				esc_attr( $attachment_id ),
				esc_url( wp_get_attachment_url( $attachment_id ) ),
				esc_attr( $size ),
				esc_attr( $comments_opened ),
				esc_attr( $img_meta ),
				esc_attr( $attachment_title ),
				esc_attr( $attachment_desc ),
				esc_url( $medium_file ),
				esc_url( $large_file )
			);
		return $output;
	}

	public function gallery_classes() {
		$classes = 'class="tiled-gallery type-' . esc_attr( $this->atts['type'] ) . ' tiled-gallery-unresized"';
		return $classes;
	}

>>>>>>> origin/johndcoy
	public static function gallery_already_redefined() {
		global $shortcode_tags;
		$redefined = false;
		if ( ! isset( $shortcode_tags[ 'gallery' ] ) || $shortcode_tags[ 'gallery' ] !== 'gallery_shortcode' ) {
			$redefined = true;
		}
		/**
		 * Filter the output of the check for another plugin or theme affecting WordPress galleries.
		 *
		 * This will let folks that replace core’s shortcode confirm feature parity with it, so Jetpack's Tiled Galleries can still work.
		 *
		 * @module tiled-gallery
		 *
		 * @since 3.1.0
		 *
		 * @param bool $redefined Does another plugin or theme already redefines the default WordPress gallery?
		 */
		return apply_filters( 'jetpack_tiled_gallery_shortcode_redefined', $redefined );
	}

	public static function init() {
		if ( self::gallery_already_redefined() )
			return;

		$gallery = new Jetpack_Tiled_Gallery;
		add_filter( 'post_gallery', array( $gallery, 'gallery_shortcode' ), 1001, 2 );
	}

	public static function get_content_width() {
		$tiled_gallery_content_width = Jetpack::get_content_width();

		if ( ! $tiled_gallery_content_width )
			$tiled_gallery_content_width = 500;

		/**
		 * Filter overwriting the default content width.
		 *
		 * @module tiled-gallery
		 *
		 * @since 2.1.0
		 *
		 * @param string $tiled_gallery_content_width Default Tiled Gallery content width.
		 */
		return apply_filters( 'tiled_gallery_content_width', $tiled_gallery_content_width );
	}

	/**
	 * Media UI integration
	 */
	function jetpack_gallery_types( $types ) {
		if ( get_option( 'tiled_galleries' ) && isset( $types['default'] ) ) {
			// Tiled is set as the default, meaning that type='default'
			// will still display the mosaic.
			$types['thumbnails'] = $types['default'];
			unset( $types['default'] );
		}

		$types['rectangular'] = __( 'Tiled Mosaic', 'jetpack' );
		$types['square'] = __( 'Square Tiles', 'jetpack' );
		$types['circle'] = __( 'Circles', 'jetpack' );
		$types['columns'] = __( 'Tiled Columns', 'jetpack' );

		return $types;
	}

	function jetpack_default_gallery_type() {
		return ( get_option( 'tiled_galleries' ) ? 'rectangular' : 'default' );
	}

	static function get_talaveras() {
		return self::$talaveras;
	}

	/**
	 * Add a checkbox field to the Carousel section in Settings > Media
	 * for setting tiled galleries as the default.
	 */
	function settings_api_init() {
		global $wp_settings_sections;

		// Add the setting field [tiled_galleries] and place it in Settings > Media
		if ( isset( $wp_settings_sections['media']['carousel_section'] ) )
			$section = 'carousel_section';
		else
			$section = 'default';

		add_settings_field( 'tiled_galleries', __( 'Tiled Galleries', 'jetpack' ), array( $this, 'setting_html' ), 'media', $section );
		register_setting( 'media', 'tiled_galleries', 'esc_attr' );
	}

	function setting_html() {
		echo '<label><input name="tiled_galleries" type="checkbox" value="1" ' .
			checked( 1, '' != get_option( 'tiled_galleries' ), false ) . ' /> ' .
			__( 'Display all your gallery pictures in a cool mosaic.', 'jetpack' ) . '</br></label>';
	}
}

add_action( 'init', array( 'Jetpack_Tiled_Gallery', 'init' ) );
