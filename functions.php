<?php
/**
 * Functions and definitions
 *
 * @package Weather Theme
 */

/**
 * Enqueue Scripts
 */
function grd_enqueue_scripts() {
	wp_enqueue_script( 'wp-util' );
}
add_action( 'wp_enqueue_scripts', 'grd_enqueue_scripts' );

/**
 * Get our weather data.
 *
 * @return string  Weather data from forecast.io
 */
function grd_get_weather_data() {

	// Leave a backdoor for flushing cache
	$flush = isset( $_GET['delete-cache'] ) ? true : $flush;

	// Forecast.io API URL
	$api_url = 'https://api.forecast.io/forecast/62627807ae3841ba587c80d49b90759b/31.3601,-85.8554';

	// Set cache key
	$transient_key = 'grd_weather_data';

	// Attempt to fetch from cache
	$data = get_transient( $transient_key );

	// If we're flushing or there isn't cache
	if ( $flush || false === ( $data ) ) {

		// Check the API
		$response = wp_remote_get( $api_url );

		// Is the API up?
		if ( ! 200 == wp_remote_retrieve_response_code( $response ) ) {
			return 'The API appears to be down. Please try again later =(';
		}

		// Parse the API data and place into a string
		$data = wp_remote_retrieve_body( $response );

		// Unserialize the results
		$data = maybe_unserialize( $data );

		// Set cache, and expire after a max of 1 hour
		set_transient( $transient_key, $data, 1 * HOUR_IN_SECONDS );
	}

	return $data;
}

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
        jQuery( document ).ready( function() {

            var post_template = wp.template( 'grd-weather' );

            jQuery( '.site-content' ).append( post_template( <?php echo grd_get_weather_data(); ?> ) );
        } );
    </script>
<?php }
add_action( 'wp_footer', 'grd_print_scripts', 25 );
