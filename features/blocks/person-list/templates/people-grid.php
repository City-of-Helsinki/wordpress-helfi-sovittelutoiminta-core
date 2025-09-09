<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\Blocks\PersonList;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="<?php echo esc_attr( implode( ' ', $block_classes ) ); ?>">
	<div class="<?php echo esc_attr( $grid_class ); ?>">
		<?php
			while( $query->have_posts() ) {
				$query->the_post();

				person_entry( get_post(), $layout );
			}

			wp_reset_postdata();
		?>
	</div>
</div>
