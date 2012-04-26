Post Recommendations for WordPress
==================================

jQuery-powered recommendations. 


How to use:

Put this code in a **single.php** page, or anywhere you want. 

```php
$args = array(); 
wp_recommendations($args); 
```

Here are the options: 

```php
$defaults = array( 
	'postID' => $post->ID,
	'size' => null,  
	'numberposts' => 6, 
	'orderby' => 'rand', 
	'show' => TRUE
); 
```
