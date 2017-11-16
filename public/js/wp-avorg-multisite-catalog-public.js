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

/**
 * fetch the recordings
 * 
 * @param	object	e	event data
 */
function getRecordings(e) {
	startIndex += parseInt(e.getAttribute('data-items-per-page'));
	var detailPageURL = e.getAttribute('data-detail-page-url');
	fetch('?rest_route=/wp-avorg-multisite-catalog/v1/tags/casa1/category/casa2&start=' + startIndex).then(function(response){
		return response.json();
	}).then(function(response){
		showRecordings(response, detailPageURL);
	});
}

/**
 * show the recordings
 * 
 * @param	array	data	recordings
 */
function showRecordings( data, detailPageURL ) {
	// console.log('data', data);
	data.result.forEach(function(element, index) {
		jQuery("#avgrid").append('<div class="cell"><a href="' + detailPageURL + element.recordings.id + '"><img src="//unsplash.it/' + (800 + index ) + '/500" class="responsive-image"><div class="duration">' + element.recordings.duration_formatted + '</div><div class="inner-content"><div class="title">' + element.recordings.title + '</div><div class="subtitle">' + element.recordings.speaker_name + '</div></div><div class="overlay"><div class="text">' + element.recordings.description + '</div></div></a></div>');
	});
}
