(function($) {	
	// cache
	var wrapper = $('.wp-recommendations ul'); 
	var posts = $('.wp-recommendations ul li'); 
	var titles = $('.wp-recommendations-meta h6'); 
	
	var form = $('#wp-recommendations-form'); 
	var select = $('#wp-recommendations-form select'); 
	
	var recommendationsApp = {
		init: function() { 
			titles.addClass('wp-recommendations-meta-surpress'); 
			
			titles.each ( function() { 
				var title_len = parseInt($(this).text().length);
				if ( title_len > 27 ) { 
					$(this).attr('id', $(this).text() ); 
					var new_text = $(this).text().substr( 0, 27 ); 
					$(this).text(new_text + "..."); 
				} 
			}); 
		},
		
		clear: function() { 
				posts.hide( 'drop', {}, 500 ); 	 
		}, 		
		
		make: function( post ) { 
	
			var newElement = $("<li></li>").prependTo(wrapper); 
			var src = encodeURI ( tmth.p + "?src=" + post.imgsrc + "&w=" + image.size[0] + "&h=" + image.size[1] ); 	
			
			newElement.append(
				"<div class='wp-recommendations-meta'><h6 class='wp-recommendations-meta-surpress'><a href='"+post.link+"'>"+post.title+"</a></h6></div>"
			); 
			
			
			
			var img = $("<img class='thumbnail' />").attr( 'src', src )
														.load( function() { 
															newElement.prepend(img); 			
														}); 	
		},
		
		freeze: function() { 
			wrapper.height(wrapper.height());
		},  
	
	}; //recommendationsApp
	
	recommendationsApp.init(); 
	
	posts.hover( 
		function() { 
			var text_node = $(this).find('h6'); 
			var excerpt = text_node.text();
			
			text_node.removeClass('wp-recommendations-meta-surpress');
			 
			if ( text_node.attr('id') != undefined ) { 
				text_node.text( text_node.attr('id') ); 
				text_node.attr('id', excerpt );  
			}  
		}, 
		function() { 
			$(this).find('h6').addClass('wp-recommendations-meta-surpress'); 
			
			var text_node = $(this).find('h6')
			var full_text = text_node.text(); 
			
			if ( text_node.attr('id') != undefined ) { 
				text_node.text( text_node.attr('id') ); 
				text_node.attr('id', full_text );  
			}  
		}
	); // posts.hover()
	
	select.change( function() { 
		form.submit(); 
	});  
	
	form.submit(
		function(event) { 
			event.preventDefault(); 
			
			// discover what's being requested
			var selected = select.val(); 
			var selObj = select.find("option[value='"+selected+"']");
			
			// the data object
			var data = { 
				'action': 'get_posts', 
				'type': selObj.attr('value'), 
				'id': selObj.attr('id'),
				'num': image.num
			}; 
			
			// freeze the ul 
			recommendationsApp.freeze(); 
			
			recommendationsApp.clear(); 
			
			// the post request
			$.post(
				ajaxurl,
				data, 
				function (response) { 
					$.each( response, function( index, post ) { 
						recommendationsApp.make( post );
					}); 
				},
				'json'
			);
					
		}
	); // form.submit()

})( jQuery ); 
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	