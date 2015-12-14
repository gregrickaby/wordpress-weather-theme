<?php
/**
 * Functions and definitions
 *
 * @package Weather Theme
 */


/**
 * Enqueue scripts.
 */
function grd_enqueue_scripts() {
	wp_enqueue_script( 'wp-util' );
}
add_action( 'wp_enqueue_scripts', 'grd_enqueue_scripts' );


/**
 * Do our weather template.
 */
function grd_do_weather_template() { ?>

	<script type="text/html" id="tmpl-grd-weather">

		<article class="post">

			<header class="entry-header">
				<h1 class="entry-title"><?php esc_html_e( 'Current Conditions for Enterprise, Alabama', 'grd-weather' ); ?></h1>
			</header>

			<div class="entry-content">
				<p class="current-conditions">{{ data.currently.summary }}, {{ data.currently.temperature }}F</p>

				<h3><?php esc_html_e( 'Next 24 Hours', 'grd-weather' ); ?></h3>
				<p class="today">{{ data.hourly.summary }}</p>

				<h3><?php esc_html_e( 'Next 7 Days', 'grd-weather' ); ?></h3>
				<p class="tomorrow">{{ data.daily.summary }}</p>
			</div>

		</article>

	</script>

<?php }
add_action( 'grd_content', 'grd_do_weather_template', 25 );


/**
 * Put our JS data into the weather template.
 */
function grd_print_scripts() { ?>

	<script type="text/javascript">
		jQuery( document ).ready( function($) {

			// Set global variables.
			var apiUrl = 'https://api.forecast.io/forecast/62627807ae3841ba587c80d49b90759b/31.3601,-85.8554';
				postTemplate = wp.template( 'grd-weather' );
				siteContent = $( '.site-content' );

			// Build the template using API data.
			var buildTemplate = function( data ) {
				siteContent.append( postTemplate( data ) );
			}

			// Get data from API.
			var fetchData = $.ajax({
				dataType: "jsonp",
				url: apiUrl,

			// When finished, pass data to template.
			}).done( function( data )  {
				buildTemplate( data );
			});

			// Engage!
			fetchData;

		});
	</script>
<?php }
add_action( 'wp_footer', 'grd_print_scripts', 25 );