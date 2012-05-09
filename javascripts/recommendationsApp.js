(function($) {	
	// cache
	var wrapper = $('.wp-recommendations ul'); 
	var posts = $('.wp-recommendations ul li'); 
	
	var form = $('#wp-recommendations-form'); 
	var select = $('#wp-recommendations-form select'); 
	
	var load = $('img.load'); 
	
	var recommendationsApp = { 
		fixCaptions: function() { 
			console.log("called"); 
			var titles = $('.wp-recommendations-meta h6 a'); 
			console.log(titles); 
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
				wrapper.empty(); 	 
		}, 		
		
		make: function( post ) { 
	
			var newElement = $("<li></li>").prependTo(wrapper); 
			var src = encodeURI ( tmth.p + "?src=" + post.imgsrc + "&w=" + image.size[0] + "&h=" + image.size[1] ); 	
			
			var title_len = parseInt(post.title.length);
			 
			if ( title_len > 27 ) { 
				var title_id = post.title; 
				var new_text = post.title.substr( 0, 27 ); 
				var elipse = new_text + "...";
				newElement.append(
					"<div class='wp-recommendations-meta'><h6><a class='wp-recommendations-meta-surpress' id='"+post.title+"' href='"+post.link+"'>"+elipse+"</a></h6></div>"
				); 
				
			} else { 
				newElement.append(
					"<div class='wp-recommendations-meta'><h6><a class='' href='"+post.link+"'>"+post.title+"</a></h6></div>"
				); 
			} 	
	
			var img = $("<img class='thumbnail' />").attr( 'src', src )
														.load( function() { 
															newElement.prepend(img); 			
														}); 	
		},
		
		freeze: function() { 
			wrapper.height(wrapper.height());
		},  
	
	}; //recommendationsApp
	
	recommendationsApp.fixCaptions(); 
	
	$(".wp-recommendations ul li").live({ 
			mouseenter: 
				function() { 
					var text_node = $(this).find('h6 a'); 
					var excerpt = text_node.text();
					
					text_node.removeClass('wp-recommendations-meta-surpress');
					 
					if ( text_node.attr('id') != undefined ) { 
						text_node.text( text_node.attr('id') ); 
						text_node.attr('id', excerpt );  
					}  
				}, 
			mouseleave: 
				function() { 
					$(this).find('h6 a').addClass('wp-recommendations-meta-surpress'); 
					
					var text_node = $(this).find('h6 a')
					var full_text = text_node.text(); 
					
					if ( text_node.attr('id') != undefined ) { 
						text_node.text( text_node.attr('id') ); 
						text_node.attr('id', full_text );  
					}  
				}
		} 
	);
	
	select.change( function() { 
		form.submit(); 
	});  
	
	form.submit(
		function(event) { 
			event.preventDefault(); 
			
			load.show(); 
			
			// discover what's being requested
			var selected = select.val(); 
			var selObj = select.find("option[value='"+selected+"']");
			
			// the data object
			var data = { 
				'action': 'get_posts', 
				'type': selObj.attr('value'), 
				'id': selObj.attr('id'),
				'num': image.num, 
				'orderby': image.orderby
			}; 
						
			// freeze the ul 
			recommendationsApp.freeze();
			
			// call the clear method to empty the ul 
			recommendationsApp.clear(); 
			
			// the post request
			$.post(
				ajaxurl,
				data, 
				function (response) { 
					$.each( response, function( index, post ) { 
						recommendationsApp.make( post );
					}); 
					load.hide();  
				},
				'json'
			);			
		}
	); // form.submit()
	
	posts.click( function() { 
		var src = $(this).find("a").attr('href');
		window.location = src; 
	}); 

})( jQuery ); 
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	