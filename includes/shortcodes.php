<?php
function wpr_software_shortcode( $attr ) {
	$arg = shortcode_atts(
		array(
			'h1'         => 'true',
			'background' => 'red',
			'h1-text'    => '',
		),
		$attr
);
	ob_start();
	?>
	<?php if ( 'true' === $args['h1'] ) { ?>
	<h1><?php echo esc_attr( $arg['h1-text'] ); ?> </h1> <?php } ?>
	<p style="background-color:<?php echo esc_attr( $arg['background'] ); ?>" >Lorem ipsum</p>
	<?php
	return ob_get_clean();
}
add_shortcode( 'software-shortcode', 'wpr_software_shortcode' );

