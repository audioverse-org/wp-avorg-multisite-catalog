(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

var startIndex = 0;

function getRecordings(e) {
	startIndex += parseInt(e.getAttribute('data-items-per-page'));
	fetch('?rest_route=/wp-avorg-multisite-catalog/v1/tags/casa1/category/casa2&start=' + startIndex).then(function(response){
		return response.json();
	}).then(function(response){
		showRecordings(response);
	});
}

function showRecordings( data ) {
	// console.log('data', data);
	for (const item of data.result) {
		jQuery("#avgrid").append('<div class="cell"><img src="http://placehold.it/800x800" class="responsive-image"><div class="inner-content"><div class="title">' + item.recordings.title + '</div><div class="subtitle">Subtitle</div></div></div>')
	}
}
