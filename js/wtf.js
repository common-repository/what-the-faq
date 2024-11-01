(function( $ ) {

/* Add Color Picker to all inputs that have 'colour-picker' class */
$(function() {
    $('.colour-picker').wpColorPicker();
});
    
})( jQuery );

/* Remove parent selector */
jQuery('.taxonomy-wtf_category .term-parent-wrap, #newwtf_category_parent').remove();