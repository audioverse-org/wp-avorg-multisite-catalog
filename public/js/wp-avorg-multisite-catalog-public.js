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

/**
 * fetch the recordings
 * 
 * @param	object	e	event data
 */
function getRecordings(e) {
	var detailPageID = e.getAttribute('data-detail-page-id');
	var next = e.getAttribute('data-next');
	if ( next != 'undefined' ) {
		fetch('?rest_route=/wp-avorg-multisite-catalog/v1/tags&url=' + encodeURIComponent(next)).then(function(response){
			return response.json();
		}).then(function(response){
			console.log(response);
			document.getElementById('more').setAttribute('data-next', response.meta.pagination.links.next);
			showRecordings(response, detailPageID);
		});
	}
}

/**
 * show the recordings
 * 
 * @param	array	data	recordings
 */
function showRecordings( data, detailPageID ) {
	// console.log('data', data);
	var imageUrl, imageName, detailPage;
	data.data.forEach(function(element, index) {
		imageUrl = element.site_image ? element.site_image.url  + '/800/500/' + element.site_image.file : '';
		detailPage = '?page_id=' + detailPageID + '&recording_id=' + element.id;
		jQuery("#avgrid").append('<div class="cell"><a href="' + detailPage + '"><img src="' + imageUrl + '" class="responsive-image"><div class="backdrop"><div class="duration">' + element.duration_formatted + '</div><div class="inner-content"><div class="title">' + element.title + '</div><div class="subtitle">' + element.speaker_name + '</div></div></div><div class="overlay"><div class="text">' + element.description + '</div></div></a></div>');
	});
}
