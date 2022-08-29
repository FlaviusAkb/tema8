<?php
/**
Plugin Name: WPR Academy
Author: Flavius
Version: 1.0
Text domain: wpr-academy
**/

// Plugin URL.
define( 'WPR_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
// Plugin path.
define( 'WPR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Adds
 */
function search() {
	search_scripts();
	ob_start(); ?>
	<form action="/" method="get" autocomplete="off">

	<div id="wpr-filter" class="navigation">
	<select id="regions">
		<option class="active" value="">All Regions</option>
		<?php
		$regions = get_terms(
			array(
				'taxonomy'   => 'regions',
				'hide_empty' => true,
			)
		);
		foreach ( $regions as $region ) {
			?>
			<option value="<?php echo $region->term_id; ?>">
				<?php echo $region->name; ?>
			</option>
			<?php
		}
		?>
	</select>

	<!-- Input -->
	<div class="search_bar" style="display: inline-block">
		<input type="text" name="s" placeholder="Search Code..." id="keyword" class="input_search" onkeyup="fetch()">
	</form>
</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'shortcode_search', 'search' );

add_action( 'wp_ajax_search', 'search_callback' );
add_action( 'wp_ajax_nopriv_search', 'search_callback' );


/**
 * Adds
 */
function search_callback() {
	header( 'Content-Type: application/json' );
	$levels = $_GET['regions'];
	$people = array();

	$products = 
		array(
			'post_type'   => 'software',
			'numberposts' => - 1,
			's'           => esc_attr( $_GET['keyword'] ),
			'tax_query'   => array(
				array(
					'taxonomy' => 'regions',
					'field'    => 'term_id',
					'terms'    => $levels,
				),
			),
		);

	$soft = new WP_Query ( $products );

	if ( $soft->have_posts() ) {
		while ( $soft->have_posts() ) {
			$soft->the_post();
				$people[] = array(
					'title'   => get_the_title(),
					'regions' => $levels,
					'link'    => get_the_permalink(),
				);
		}
		wp_reset_query();
	}
	echo wp_json_encode( $people );
	wp_die();
}


/**
 * Adds
 */
function search_scripts() {
	wp_enqueue_script(
		'search',
		WPR_URL . '/assets/search.js',
		array( 'jquery' ),
		'1.0',
		true
	);
	wp_localize_script(
		'search',
		'WPR',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'search' ),
		)
	);
}


/*
==================
Ajax Search
======================
*/
add_action( 'wp_footer', 'ajax_fetch' );
/**
 * Adds
 */
function ajax_fetch() {
	wp_enqueue_script( 'fetch', WPR_URL . '/assets/fetch.js', array( 'jquery' ), '1.0', true );
	wp_localize_script(
		'fetch',
		'WPR',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'fetch' ),
		)
	);
}

// the ajax function.
add_action( 'wp_ajax_data_fetch', 'data_fetch' );
add_action( 'wp_ajax_nopriv_data_fetch', 'data_fetch' );

/**
 * Adds
 */
function data_fetch() {

	$the_query = new WP_Query( array( 'posts_per_page' => -1, 's' => esc_attr( $_POST['keyword'] ), 'post_type' => array( 'software' ) ) );
	if ( $the_query->have_posts() ) :
		echo '<ul>';
		while ( $the_query->have_posts() ) : $the_query->the_post();
			?>

	<div class="software-card-simple">
		<div class="software-card-simple-title-container">
			<h2 class="scs-title">
				<a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a>
			</h2>
		</div>
	</div>

			<?php
		endwhile;
		echo '</ul>';
		wp_reset_postdata();
	endif;

	die();
}
