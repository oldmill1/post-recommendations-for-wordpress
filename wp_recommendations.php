<?php
/*
Plugin Name: Post Recommendations for WordPress
Plugin URI: http://oldmill1.github.com/post-recommendations-for-wordpress/
Description: Give your visitors more posts to see. 
Version: 1.1
Author: Ankur Taxali
Author URI: http://oldmill1.github.com/
License: GPL2
*/

/* 
		This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 

include 'recommendationsApp.php'; 

wp_enqueue_style(
	'wp-recommendations-style', 
	plugins_url( 'stylesheets/stylesheet.css', __FILE__ ) 
); 

wp_enqueue_script("jquery"); // load jQuery for those who don't have it

wp_enqueue_script(
	'recommendationsAppJavascript', 
	plugins_url( 'javascripts/recommendationsApp.js', __FILE__ ), 
	NULL, 
	NULL, 
	TRUE 
); 

wp_enqueue_script(
	'jqueryui', 
	plugins_url( 'javascripts/jquery-ui.min.js', __FILE__ ), 
	NULL, 
	NULL, 
	TRUE 
); 


global $app; 

$app = new recommendationsApp(); 

// this can only be done after an app has been started... 

$tmth_data = array( 'p' => $app->get_timthumb_path() ); 
wp_localize_script( 'recommendationsAppJavascript', 'tmth', $tmth_data );


// template tag 
function wp_recommendations( $args ) { 
	global $post; 
	global $app; 

	$defaults = array( 
		'postID' => $post->ID,
		'size' => null,  
		'numberposts' => 6, 
		'orderby' => 'rand', 
		'show' => true
	);  
		
	$args = wp_parse_args( $args, $defaults ); 
	
	$app->build_recommendations( $args );  
	 
} 

// shortcode 
function recommend_something_func( $atts ) { 
	global $post; 
	
	extract( shortcode_atts( array(
			'post_id' => $post->ID,
			'size' => null,  
			'numberposts' => 6, 
			'orderby' => 'rand', 
			'show' => false
		), $atts ) );
		
	// if size is set, convert it to an array 
	if ( ! is_null($size) ) { 
		$size = explode(",", $size, 2 );
		$size[0] = intval($size[0]); 
		$size[1] = intval($size[1]); 
	} 
		
	$args = array( 
		'postID' => $post_id,
		'size' => $size,  
		'numberposts' => $numberposts, 
		'orderby' => $orderby, 
		'show' => false
	); 
	
	$app = new recommendationsApp(); 
	return $app->build_recommendations( $args );  
}


add_shortcode( 'recommend_posts_for_me', 'recommend_something_func' );

// define ajaxurl on the front end 

if ( !is_admin() ) : 
	add_action('wp_head','wprecommendations_ajaxurl');
endif; 

function wprecommendations_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

add_action('wp_ajax_get_posts', 'prefix_ajax_get_posts' ); 
add_action('wp_ajax_nopriv_get_posts', 'prefix_ajax_get_posts');


// the main ajax request function
function prefix_ajax_get_posts() { 
	global $app; 
	global $post; 
	extract( $_POST ); // imports $type, $id, $num, $orderby
	
	switch ( $type ) { 
		case "category": 
			$categories = $id; 
			$category_in = explode( ",", $categories ); 
			
			$args = array( 
				'category__in' => $category_in, 
				'numberposts' => $num, 
				'orderby' => $orderby 
			);
			
			$myposts = get_posts( $args ); 
			$data = array(); 
			foreach ( $myposts as $post ) { 
				setup_postdata( $post ); 
				$data[$post->ID] = array( "title" => get_the_title(), "imgsrc" => $app->get_image($post->ID), "link" => get_permalink() );  
			}
			exit(json_encode($data));  
			break;
		
		case "author":  
			$args = array( 
				'numberposts' => $num, 
				'author' => $id,  
				'orderby' => 'rand' 
			); 	
			$myposts = get_posts( $args ); 
			$data = array(); 
			foreach ( $myposts as $post ) { 
				setup_postdata( $post ); 
				$data[$post->ID] = array( "title" => get_the_title(), "imgsrc" => $app->get_image($post->ID), "link" => get_permalink() );  
			}
			exit(json_encode($data)); 
			break;
	} 
	
} 



















