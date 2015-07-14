<?php
/*
Plugin Name: 5sec Google Maps
Plugin URI: http://5sec-gmap.webfactoryltd.com/
Description: No setup. No code. No bulls**t! Just Google Maps! In 5 sec! Usage: [gmap]my address, my city, my country[/gmap]
Author: Web factory Ltd
Version: 1.6
Author URI: http://www.webfactoryltd.com/
*/

// shortcode name, change if needed
define('GMAP_SHORTCODE', 'gmap');

// **********************************
// !! DO NOT TOUCH BELOW THIS LINE !!
// **********************************

class wf_gmap {
  static $js_functions = '';

  public function init() {
    if(is_admin) {
      add_filter('plugin_row_meta', array(__CLASS__, 'plugin_meta_links'), 10, 2);
    }
    // check wp_footer()
    self::check_wp_footer();

    // add shortcode
    global $shortcode_tags;
    if (isset($shortcode_tags[GMAP_SHORTCODE])) {
      add_action('admin_footer', array(__CLASS__, 'warning'));
    } else {
      add_shortcode(GMAP_SHORTCODE, array(__CLASS__, 'shortcode'));
    }

    // add JS include files
    add_action('wp_footer', array(__CLASS__, 'footer'), 2);

    // add shortcode support in sidebar text widget
    if (has_filter('widget_text', 'do_shortcode') === false) {
      add_filter('widget_text', 'do_shortcode');
    }

    return;
  }

  // add links to plugin's description in plugins table
  function plugin_meta_links($links, $file) {
    $documentation_link = '<a target="_blank" href="' . plugin_dir_url(__FILE__) . 'documentation/' .
                          '" title="View documentation">Documentation</a>';
    $support_link = '<a target="_blank" href="http://codecanyon.net/user/WebFactory#from" title="Contact Web factory">Support</a>';

    if ($file == plugin_basename(__FILE__)) {
      $links[] = $documentation_link;
      $links[] = $support_link;
    }

    return $links;
  } // plugin_meta_links

  public function check_wp_footer() {
    if(get_transient('wp_footer_ok')) {
      return;
    }

    $ok = true;

    // check if we can find wp_footer()
    $tmp = @file_get_contents(get_template_directory() . '/footer.php');
    if (strpos(strtolower($tmp), 'wp_footer(') === false) {
      $tmp = @file_get_contents(get_template_directory() . '/index.php');
      if (strpos(strtolower($tmp), 'wp_footer(') === false) {
        $ok = false;
      }
    }

    if ($ok === false) {
      add_action('admin_footer', array('wf_gmap', 'warning2'));
    } else {
       set_transient('wp_footer_ok', true, 3600*24*30*12);
    }

    return;
  } // check_wp_footer

  public function footer($tmp = '', $wp_footer = true) {
    global $map_id, $fullscreen;
    $out = '';

    // using the normal wp_footer
    if ($wp_footer) {
      if ($map_id > 0) {
        $out .= self::$js_functions;
        $out .= '<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>' . "\n";

        $out .= '<script type="text/javascript">';
        for ($i=1; $i <= $map_id; $i++) {
          $out .= ' run5secGmap_' . $i . '(); ';

          if (array_search('gmap_' . $i, $fullscreen) !== false) {
          $out .= 'jQuery(function($) { $(\'#gmap_' . $i . '\').append(\'<a href="#" title="Toggle full screen view" onclick="gmaps_fullscreen(' . $i . ')"><img src="' . plugins_url('/icons/fullscreen.png', __FILE__) . '" style="z-index: 2; border: none; position: absolute; bottom: 40px; left: 3px;"></a>\');});';
          }
        } // for

        if (sizeof($fullscreen)) {
        $out .= "
var gmaps_full = 0;
function gmaps_fullscreen(map_id) {
  if (!jQuery('#gmap_' + map_id).data('fullscreen')) {
    jQuery('#gmap_' + map_id).data('fullscreen', jQuery('#gmap_' + map_id).attr('style'));
  }
  if (gmaps_full == 1) {
    jQuery('#gmap_' + map_id).attr('style', jQuery('#gmap_' + map_id).data('fullscreen'));
    jQuery('object').show();
    gmaps_full = 0;
  } else {
    jQuery('object').hide();
    gmaps_full = 1;
    jQuery('#gmap_' + map_id).data('fullscreen', jQuery('#gmap_' + map_id).attr('style'));
    jQuery('#gmap_' + map_id).css('position', 'fixed').css('z-index', parseInt(10000 + map_id, 10));
    jQuery('#gmap_' + map_id).css('width', '100%').css('height', '100%');
    jQuery('#gmap_' + map_id).css('top', '0').css('left', '0');
}
  google.maps.event.trigger(eval('map_' + map_id), 'resize');
  return false;
}";
        }
        $out .= '</script>' . "\n";
      }
    } else { // hacked version
      if ($map_id == 1) {
        $out .= '<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>' . "\n";
      }
      if ($map_id > 0) {
        $out .= '<script type="text/javascript">run5secGmap_' . $map_id . '();</script>' . "\n";
      }
    }

    if ($wp_footer == true) {
      echo $out;
    } else {
      return $out;
    }
  }

  public function shortcode($atts, $content = null) {
    // support multiple maps on single page
    global $map_id, $post, $fullscreen;
    $out = '';
    $map_id++;
    if (is_null($fullscreen)) {
      $fullscreen = array();
    }

    // parse attributes and set some default values
    $atts = shortcode_atts(array('width'   => '100%',
                                  'height'  => '400px',
                                  'address' => '',
                                  'lat' => false,
                                  'lng' => false,
                                  'description' => '',
                                  'show_description' => false,
                                  'zoom' => '17',
                                  'fullscreen' => false,
                                  'icon' => plugins_url('/icons/red-pin.png', __FILE__),
                                  'type' => 'ROADMAP',
                                  'disable_cache' => false,
                                  'scroll' => true,
                                  'debug' => false,
                                  'post_id' => $post->ID),
                           $atts);

    // check if address came from var or content
    if (!empty($content)) {
      $atts['address'] = trim($content);
    } else {
      $atts['address'] = trim($atts['address']);
    }

    // if we're in a post, replace $value with custom field values
    $atts['post_id'] = (int) $atts['post_id'];
    if ($atts['post_id']) {
      foreach ($atts as $param => $val) {
        $tmp = '';
        $val = explode(' ', $val);
        foreach($val as $val2) {
          if (substr($val2, 0, 1) == '$' && substr($val2, 0, 2) != '$$') {
            $tmp .= ' ' . get_post_meta($post->ID, str_replace('$', '', $val2), true);
          } elseif (substr($val2, 0, 2) == '$$') {
            $tmp .= ' ' . substr($val2, 1);
          } else {
            $tmp .= ' ' . $val2;
          }
        } // foreach val arr
        $atts[$param] = trim($tmp);
      } // foreach
    } // if $post

    // clean-up all attributes
    // description
    $atts['description'] = str_replace(array("\n", '"', "'"), array(' ', '\"', "\'"), $atts['description']);
    if (substr_count($atts['description'], '|') == 1) {
      $tmp = explode('|', $atts['description']);
      $atts['description'] = '<b>' . $tmp[0] . '</b><br />' . $tmp[1];
    }

    // add directions to description
    $directions = 'http://maps.google.com/?daddr=' . urlencode($atts['address']);
    $atts['description'] = str_replace('DIRECTIONS', '<a href=\'' . $directions . '\'>directions</a>', $atts['description']);
    $atts['description'] = str_replace('DIRECTIONS_LINK', $directions, $atts['description']);

    // show description
    $atts['show_description'] = (bool) $atts['show_description'];

    // fullscreen icon
    $atts['fullscreen'] = (bool) $atts['fullscreen'];
    if ($atts['fullscreen']) {
      $fullscreen[] = 'gmap_' . $map_id;
    }

    // width
    $atts['width'] = trim($atts['width']);
    if (empty($atts['width'])) {
      $atts['width'] = '100%';
    }
    if (is_numeric($atts['width'])) {
      $atts['width'] .= 'px';
    }

    // height
    $atts['height'] = trim($atts['height']);
    if (empty($atts['height'])) {
      $atts['height'] = '400px';
    }
    if (is_numeric($atts['height'])) {
      $atts['height'] .= 'px';
    }

    // zoom
    $atts['zoom'] = (int) $atts['zoom'];
    if ($atts['zoom'] < 0 || $atts['zoom'] > 20) {
      $atts['zoom'] = 17;
    }

    // icon
    switch (strtolower($atts['icon'])) {
      case 'blue':
        $atts['icon'] = plugins_url('/icons/blue-pin.png', __FILE__);
      break;
      case 'red':
        $atts['icon'] = plugins_url('/icons/red-pin.png', __FILE__);
      break;
      case 'yellow':
        $atts['icon'] = plugins_url('/icons/yellow-pin.png', __FILE__);
      break;
      case 'green':
        $atts['icon'] = plugins_url('/icons/green-pin.png', __FILE__);
      break;
      case 'grey':
      case 'gray':
        $atts['icon'] = plugins_url('/icons/grey-pin.png', __FILE__);
      break;
      case 'black':
        $atts['icon'] = plugins_url('/icons/black-pin.png', __FILE__);
      break;
      case 'white':
        $atts['icon'] = plugins_url('/icons/white-pin.png', __FILE__);
      break;
      case 'house':
        $atts['icon'] = plugins_url('/icons/house.png', __FILE__);
      break;
      case 'shop':
        $atts['icon'] = plugins_url('/icons/shop.png', __FILE__);
      break;
      case 'chat':
        $atts['icon'] = plugins_url('/icons/chat.png', __FILE__);
      break;
      case 'flag':
        $atts['icon'] = plugins_url('/icons/flag.png', __FILE__);
      break;
      case 'star':
        $atts['icon'] = plugins_url('/icons/star.png', __FILE__);
      break;
      case 'default':
        $atts['icon'] = '';
      break;
      default:
    }

    // map type
    switch (strtolower($atts['type'])) {
      case 'roadmap':
      case 'road':
      case 'roads':
        $atts['type'] = 'ROADMAP';
      break;
      case 'satellite':
      case 'satelite':
      case 'sat':
        $atts['type'] = 'SATELLITE';
      break;
      case 'hybrid':
      case 'hybride':
      case 'hy':
      case 'hyb':
        $atts['type'] = 'HYBRID';
      break;
      case 'terrain':
      case 'terra':
      case 'terr':
      case 'terraine':
        $atts['type'] = 'TERRAIN';
      break;
      default:
        $atts['type'] = 'ROADMAP';
    }

    // scroll
    $atts['scroll'] = (int) (bool) $atts['scroll'];

    // cache usage
    $atts['disable_cache'] = (bool) $atts['disable_cache'];

    // get coordinates from user, cache or Google
    if ($atts['lat'] && $atts['lng']) {
      $coordinates['lat'] = $atts['lat'];
      $coordinates['lng'] = $atts['lng'];
    } else {
      $coordinates = self::get_coordinates($atts['address'], $atts['disable_cache']);
      if (is_string($coordinates)) {
        $err = '<p style="color: red;">' . $coordinates . '</p>';
      }
    }


    // set title / tooltip
    if (isset($coordinates['address'])) {
      $atts['title'] = $coordinates['address'];
    } else {
      $atts['title'] = $atts['address'];
    }


    // debug?
    $atts['debug'] = (bool) $atts['debug'];
    if ($atts['debug']) {
      $out .= '<pre>';
      $out .= var_export($atts, true) . '<br />';
      $out .= var_export($coordinates, true);
      $out .= '</pre>';
    }

    if ($err) {
      return $out . $err;
    }

    // build JS function
    $out .= '<div class="gmap-canvas" id="gmap_' . $map_id . '" style="width:' . $atts['width'] . '; height:' . $atts['height'] . ';"></div>' . "\n\n";
    self::$js_functions .= '<script type="text/javascript">
             var map_' . $map_id . ';
             function run5secGmap_' . $map_id . '(){
                var myLatlng = new google.maps.LatLng(' . $coordinates['lat'] . ', ' . $coordinates['lng'] . ');
                var myOptions = {
                  zoom: ' . $atts['zoom'] . ',
                  center: myLatlng,
                  scrollwheel: ' . $atts['scroll'] . ',
                  mapTypeId: google.maps.MapTypeId.' . $atts['type'] . '
                }
                map_' . $map_id . ' = new google.maps.Map(document.getElementById("gmap_' . $map_id . '"), myOptions);
                var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map_' . $map_id . ',
                  icon: "' . $atts['icon'] . '",
                  title: "' . $atts['title'] . '"
                });';

    if ($atts['description']) {
       self::$js_functions .= 'var contentString = "<div class=\"gmap-description\">' . $atts['description']  . '</div>";
                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                var open = 0;
                google.maps.event.addListener(marker, "click", function() {
                  if (open) {
                    infowindow.close(map_' . $map_id . ', marker);
                    open = 0;
                  } else {
                    infowindow.open(map_' . $map_id . ', marker);
                    open = 1;
                  }
                });';
        if ($atts['show_description']) {
         self::$js_functions .= 'google.maps.event.addListener(map_' . $map_id . ', "tilesloaded", function() {
                   open = 1;
                   infowindow.open(map_' . $map_id . ', marker);
                 });';
        }
    }
    self::$js_functions .= '} // run map
              </script>';

    if(!get_transient('wp_footer_ok')) {
      $out .= self::footer(false, false);
    }

    return $out;
  } // shortcode

  public function get_coordinates($address, $force_refresh = false) {
    $address_hash = md5($address);

    if ($force_refresh || ($coordinates = get_transient($address_hash)) === false) {
      $url = 'http://maps.googleapis.com/maps/api/geocode/xml?address=' . urlencode($address) . '&sensor=false';

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $xml = curl_exec($ch);
      $ch_info = curl_getinfo($ch);
      curl_close($ch);

      if ($ch_info['http_code'] == 200) {
        $data = new SimpleXMLElement($xml);
        if ($data->status == 'OK') {
          $cache_value['lat']     = (string) $data->result->geometry->location->lat;
          $cache_value['lng']     = (string) $data->result->geometry->location->lng;
          $cache_value['address'] = (string) $data->result->formatted_address;

          // cache coordinates for 3 months
          set_transient($address_hash, $cache_value, 3600*24*30*3);
          $data = $cache_value;
        } elseif (!$data->status) {
          return 'XML parsing error. Please try again later.';
        } else {
          return 'Unable to parse entered address. API response code: ' . @$data->status;
        }
      } else {
         return 'Unable to contact Google Maps API service.';
      }
    } else {
       // data is cached, get it
       $data = get_transient($address_hash);
    }

    return $data;
  } // get_coordinates

  public function warning() {
    echo '<div id="message" class="error"><p><strong>5sec Google Map is not active!</strong> The shortcode [gmap] is already in use by another plugin. Please refer to <a href="http://5sec-gmap.webfactoryltd.com/">documentation</a> to solve this issue.</p></div>';

    return;
  } // warning

  public function warning2() {
    echo '<div id="message" class="error"><p><strong>5sec Google Map may not be working properly!</strong> We couldn\'t detect <i>wp_footer()</i> call in your footer.php file and had to use a workaround method. If you know you have that function call please ignore this message, otherwise please refer to <a href="http://5sec-gmap.webfactoryltd.com/">documentation</a> to resolve the issue.</p></div>';

    return;
  } // warning2

  // clean up
  function deactivate() {
    delete_transient('wp_footer_ok');
  } // deactivate
} // class wf_gmap

// hook our thing
add_action('init', array('wf_gmap', 'init'));
register_deactivation_hook(__FILE__, array('wf_gmap', 'deactivate'));
?>