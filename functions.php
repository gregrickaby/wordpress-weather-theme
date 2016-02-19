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
	wp_enqueue_script( 'grd-weather', get_template_directory_uri() . '/assets/js/weather.js', array( 'jquery' ), '1.0.0', true );
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

				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/{{ data.currently.icon }}.svg" />

				<h3><?php esc_html_e( 'Next 24 Hours', 'grd-weather' ); ?></h3>
				<p class="today">{{ data.hourly.summary }}</p>

				<h3><?php esc_html_e( 'Next 7 Days', 'grd-weather' ); ?></h3>
				<p class="tomorrow">{{ data.daily.summary }}</p>
			</div>

		</article>

	</script>

<?php }
add_action( 'grd_content', 'grd_do_weather_template', 25 );