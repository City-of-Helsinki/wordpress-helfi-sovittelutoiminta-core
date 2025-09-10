<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\Blocks\PersonList;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="<?php echo esc_attr( implode( ' ', $entry_classes ) ); ?>">
	<figure class="person-teaser__image wp-block-image is-style-outline">
		<?php
			if ( $thumbnail ) {
				echo $thumbnail;
			} else {
				printf(
					'<img src="%s" alt="%s">',
					esc_url( $placeholder_url ),
					esc_attr( $title )
				);
			}
		?>
	</figure>

	<ul class="person-teaser__details">
	    <li>
			<h3 class="person-teaser__name"><?php echo esc_html( $title ); ?></h3>
		</li>
		<?php if ( $email ) : ?>
			<li class="person-teaser__email">
				<a href="mailto:<?php echo esc_attr( $email ); ?>">
					<?php echo esc_html( $email ); ?>
				</a>
			</li>
		<?php endif; ?>
		<?php if ( $description ) : ?>
			<li class="person-teaser__description">
				<?php echo wp_kses_post( nl2br($description) ); ?>
			</li>
		<?php endif; ?>
    </ul>
</div>
