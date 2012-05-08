<?php 

class recommendationsApp { 

	/** 
	 * This variable holds the path to the plugin's timthumb file 
	 * 
	 * @var string The absolute path to timthumb
	*/ 
	public $timthumb;
	
	
	/** 
	 * This variable keeps track of the size of the images
	 *
	 * @var array A 2-item array like (width, height) 
	*/ 
	public $size;  
	
	/** 
	 * Holds the value for the number of posts to display
	 * 
	 * @var int 
	*/ 
	public $num; 

	
	/** 
	 * Sets the object properties
	 *
	*/ 
	public function  __construct() { 
		$this->timthumb = plugins_url( 'lib/timthumb.php', __FILE__ );
		$this->size = array(150, 100); 
	}

	/**
	 * Returns the timthumb path
	*/ 
	public function get_timthumb_path() { 
		return $this->timthumb; 
	}
	
	/**
	 * Returns the size property
	*/ 
	public function get_size() { 
		return $this->size; 
	}
	
	/** 
	 * Returns the number of posts to display 
	*/ 
	public function get_num() { 
		return $this->num; 
	} 
	
	/**  
	 * Builds the recommendations. 
	 * If this is called inside the loop, this will use post's id. 
	 * 
	 * @param  $postID 						[int]					The ID of the post you want to get recomendations from
	 * @param  $numberposts 			[int]					The number of posts to display 
	 * @param  $orderby 					[str]					The order in which to display the posts
	 * @param  $show 							[bool]				TRUE to display, FALSE to return
	 * @return $build 													The HTML needed to show recomendations
	*/ 
	public function build_recommendations( $args ) {
		
		extract( $args, EXTR_SKIP );

		global $post; 
		global $timthumb; 
		
		$build = ""; 
		
		$tmp_post = $post;
		
		if ( is_null($postID) ) { 
			$postID = $post->ID; 
		}
		
		// we're trying to get the author id of the post being requested 
		$requested_post = get_post($postID); 
		$the_author_id = $requested_post->post_author; 
		
		$categories = get_the_category($postID);
		
		foreach ( $categories as $category ) { 
			$category_in[] = $category->term_id; 
		}  
		
		$args = array( 
			'category__in' => $category_in, 
			'numberposts' => $numberposts, 
			'orderby' => $orderby 
		);
		
		if ( is_null( $size ) ) { 
			$size = $this->size;
		} else { 
			$this->size = $size; 
		} 
		
		$this->num = $numberposts;
		
		$image_data = array( 'size' => $this->size, 'num' => $this->num, 'orderby' => $orderby );
		wp_localize_script( 'recommendationsAppJavascript', 'image', $image_data ); 
		
		$relatedposts = get_posts( $args );
		$build .= "<div class='wp-recommendations'>"; 
		$build .= "<div class='protective-well'><form method='POST' action='/' id='wp-recommendations-form'>"; 
		$build .= "<select name='wp-recommendations-form-options-type'>";
		$build .= "<option value='category' id='".implode(',', $category_in)."'>More on this Topic</option>";  
		$build .= "<option value='author' id='{$the_author_id}'>More by this Author</option>";
		$build .= "</select>"; 
		$build .= "</form><img class='load' src='".plugins_url( 'images/load.gif', __FILE__ )."' /></div>";  
		$build .= "<ul>"; 
		foreach ( $relatedposts as $post ) : 
			setup_postdata( $post ); 
			$title = get_the_title(); 
			$link = get_permalink(); 
			$build .= "<li>"; 
			$build .= $this->build_image( $this->get_image($post->ID), $size ); 
			$build .= "<div class='wp-recommendations-meta'><h6><a href='$link'>{$title}</a></h6></div>"; 
			$build .= "</li>"; 
		endforeach;
		$build .= "</ul>"; 
		$build .= "</div>"; 
		
		if ( $show ) {
			echo $build;
			return true; 
		} else {
			return $build;
		}
		 
			
	} 
	
	
	/** 
	 * Get file paths of all images attached to a post
	 *
	 * @param  $postID 		[int] 	Post ID 
	 * @param  $size			[str] 	Thumbnail, medium, large or full 		
	 * @return $paths 		[arr] 	An array with file paths of attached images
	*/ 
	
	private function get_attachments( $postID, $size = 'full' ) { 
		$images = get_children( 
			array( 
				'post_parent' => $postID, 
				'post_type' => 'attachment', 
				'numberposts' => -1, 
				'order' => 'ASC', 
				'orderby' => 'ID', 
				'post_mime_type' => 'image'	
			)
		);
		
		$filepaths = array(); 
		
		if ( !empty( $images ) ) { 
			foreach ( $images as $image ) { 
				$imagefile = wp_get_attachment_image_src( $image->ID, $size ); 
				$filepaths[] = $imagefile[0]; 
			} 
		}	
		
		return $filepaths; 
	} 
	
	/** 
	 * Get the source of an image
	 * 
	 * @param $postID 	[int] 	Post ID
	*/ 
	public function get_image( $postID ) { 
		// if the theme has thumbnails enabled and the post has a thumbnail image	
		if ( has_post_thumbnail( $postID ) ):
			$img = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'single-post-thumbnail' );
			return $img[0]; 
		// else, just get the first image
		else: 
			$array = $this->get_attachments( $postID, "full" ); 
			return $array[0]; 
		endif; 
	} 
	
	/** 
	 * Build an <img> HTML element
	 * 
	 * @param $src 	[str] 	The source 
	 * @param $size [arr] 	A 2 item array with width and height
	*/ 
	private function build_image( $src, $size ) { 
		$w = $size[0]; 
		$h = $size[1]; 
		
		$node = "<img class='thumbnail' src='{$this->timthumb}?src=$src&w=$w&h=$h' />"; 
		
		return $node; 
	}

} // class ~fin~






























