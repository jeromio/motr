jQuery(document).ready(function( $ ) { 
	var flyerHeading = $('.tribe-events-flyer .flyer-event-heading');
			flyerHeading.click(function(){
				eventContent = $(this).parent('div').find('.tribe-flyer-event-description');
				eventContent.slideToggle('fast');
			});	
});	
