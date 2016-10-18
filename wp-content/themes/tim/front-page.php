<?php get_header(); ?>

<ul class="products row .col-md-8 .col-md-offset-2"  >
	
	

	<?php
		$args = array(
			'post_type' => 'product',
			'per_page' => '4',
      'columns' => '4',
      'orderby' => 'date',
      'order' => 'desc'

			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	?>

</ul><!--/.products-->

<?php get_footer(); ?>