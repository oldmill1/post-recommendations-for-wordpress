<?php
/*
Plugin Name: WP Recommendations
Plugin URI: 
Description: Give your visitors more things to see. 
Version: 1.0
Author: Ankur Taxali
Author URI: https://github.com/oldmill1
License: GPL2
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


/* 
// sample use (as a template tag) 
if ( function_exists( 'wp_recommendations' ) ) { 
	wp_recommendations(); 
} 
*/ 
function wp_recommendations( $args ) { 
	global $post; 
	global $app; 

	$defaults = array( 
		'postID' => $post->ID,
		'size' => null,  
		'numberposts' => 6, 
		'orderby' => 'rand', 
		'show' => TRUE
	);  
		
	$args = wp_parse_args( $args, $defaults ); 
	
	$app->build_recommendations( $args );  
} 

// define ajaxurl on the front end 
add_action('wp_head','pluginname_ajaxurl');

function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

add_action('wp_ajax_get_posts', 'prefix_ajax_get_posts'); 

function prefix_ajax_get_posts() { 
	global $app; 
	global $post; 
	extract( $_POST ); // imports $type, $id, $num 
	
	switch ( $type ) { 
		case "category": 
			$categories = $id; 
			$category_in = explode( ",", $categories ); 
			
			$args = array( 
				'category__in' => $category_in, 
				'numberposts' => $num, 
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



















