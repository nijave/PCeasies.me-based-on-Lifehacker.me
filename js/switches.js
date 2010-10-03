
function switchto(elem, num)
{
	// Use cached object so you don't have to keep researching dom -- performance
	// Make use of included jQuery
	$(elementsArray).hide(1);
	$('#'+elem).fadeIn('slow');
	
	// Determine where to move triangle arrow based on num parameter and padding, width, and margin values -- might still be a few px off
	triangle_margin = (52 + (num*116));

	// Move the triangle over using jQuery animate feature
	$('#triangle').stop().animate({marginRight: triangle_margin}, 750);
}