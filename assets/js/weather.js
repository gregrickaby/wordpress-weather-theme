/**
 * Fetch the weather and update our template.
 */
window.GRD_Weather = {};
( function( window, $, that ) {

	// Private variables.
	var apiUrl = 'https://api.forecast.io/forecast/62627807ae3841ba587c80d49b90759b/31.3601,-85.8554';
	var postTemplate = wp.template( 'grd-weather' );

	// Constructor.
	that.init = function() {
		that.cache();

		if ( that.meetsRequirements ) {
			that.bindEvents();
		}
	};

	// Cache all the things.
	that.cache = function() {
		that.$c = {
			window: $(window),
			siteContent: $( '.site-content' ),
		};
	};

	// Combine all events.
	that.bindEvents = function() {
		that.$c.window.on( 'load', that.buildTemplate );
	};

	// Do we meet the requirements?
	that.meetsRequirements = function() {
		return that.$c.apiUrl.length;
	};

	// Build the template using API data.
	var buildTemplate = function( data ) {
		that.$c.siteContent.append( postTemplate( data ) );
	}

	// Fetch data.
	var fetchData = $.ajax({
			url: apiUrl,
			dataType: "jsonp",
			success: function( data ) {
				buildTemplate( data );
			}
		});

	// Engage!
	$( that.init );

})( window, jQuery, window.GRD_Weather );