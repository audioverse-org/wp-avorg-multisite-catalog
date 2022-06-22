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

	jwplayer.key=jwPlayerOop.lisence
	if(document.getElementById("mediaplayer")!=null){
		jwplayer("mediaplayer").setup({
			primary: "html5",
			file: jwPlayerOop.audio_url,
			image: jwPlayerOop.image_url,
			width: "100%",
			aspectratio: "16:9",
			stretching: "fill"
		});
	  }


})( jQuery );

/**
 * fetch the recordings
 * 
 * @param	object	e	event data
 */
function getRecordings(e) {
	var detailPermalink = e.getAttribute('data-detail-permalink');
	var next = e.getAttribute('data-next');
	// var tags = e.getAttribute('data-tags');
	if ( next != 'undefined' ) {
		// fetch('?rest_route=/wp-avorg-multisite-catalog/v1/tags&url=' + encodeURIComponent(next) + '&tag=' + encodeURIComponent(tags)).then(function(response){
		fetch('?rest_route=/wp-avorg-multisite-catalog/v1/tags&url=' + encodeURIComponent(next)).then(function(response){
			console.log(response)
			return response.json();
		}).then(function(response){
			console.log(response);
			document.getElementById('more').setAttribute('data-next', response?.data.recordings.pageInfo.hasNextPage ? response?.data.recordings.pageInfo.endCursor : null);
			document.getElementsByClassName('show-more')[0].style.display = response?.data.recordings.pageInfo.hasNextPage ? 'block' : 'none';

			showRecordings(response, detailPermalink);
		})
	}
}

/**
 * show the recordings
 * 
 * @param	array	data	recordings
 */
function showRecordings( data, detailPermalink ) {
	var imageUrl, imageName, detailPage, description;
	data.data.recordings.nodes.forEach(function(element, index) {
		imageUrl = element.coverImage ? element.coverImage?.url : element.imageWithFallback.url;
		detailPage = detailPermalink + '?' + element.sanitized_title + '&recording_id=' + element.id;
		description = element.description ? element.description : 'No Description'
		
		jQuery("#avgrid").append(
			'<div class="cell"><a href="' + detailPage + '"><img src="' + imageUrl + '" class="responsive-image"><div class="backdrop"><div class="duration"><span class="play-icon"></span>' + element.duration_formatted + '</div><div class="inner-content"><div class="title">' + element.title + '</div><div class="subtitle">' + element.speaker_name + '</div></div></div><div class="overlay"><div class="text">' + description + '</div></div></a></div>');
	});
}
