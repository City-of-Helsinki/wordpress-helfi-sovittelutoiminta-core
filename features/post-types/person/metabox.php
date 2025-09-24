<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\PostTypes\Person;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function register_person_metabox(): void {
	add_meta_box(
		cpt_person_name() . '-details',
		__( 'Details', 'helsinki-sovittelutoiminta-core' ),
		__NAMESPACE__ . '\\render_person_metabox',
		cpt_person_name(),
		'advanced',
		'high',
		array()
	);
}

function render_person_metabox( \WP_Post $post, array $args ): void {
	wp_nonce_field( person_metabox_nonce_action( $post->ID ), person_metabox_nonce_name() );

	person_meta_table_open();

	render_person_description_input( $post->ID );
	render_person_email_input( $post->ID );
	render_person_phone_input( $post->ID );

	person_meta_table_close();
}

function person_meta_table_open(): void {
	echo '<style>
		.person-meta-table,
		.person-meta-table input,
		.person-meta-table textarea {width: 100%;}

		.person-meta-table th {text-align: left;}
	</style>';
	echo '<table class="person-meta-table"><tbody>';
}

function person_meta_table_close(): void {
	echo '</tbody></table>';
}

function render_person_email_input( int $post_id ): void {
	echo sprintf(
		'<tr>
			<th>
				<label for="%1$s-input">%2$s</label>
			</th>
			<td>
				<input id="%1$s-input" type="email" name="%4$s[%1$s]" value="%3$s">
			</td>
		</tr>',
		person_email_meta_key(),
		__( 'Email', 'helsinki-sovittelutoiminta-core' ),
		esc_attr( person_email( $post_id ) ),
		cpt_person_name()
	);
}

function render_person_phone_input( int $post_id ): void {
	echo sprintf(
		'<tr>
			<th>
				<label for="%1$s-input">%2$s</label>
			</th>
			<td>
				<input id="%1$s-input" type="text" name="%4$s[%1$s]" value="%3$s">
				<p class="description">%5$s</p>
			</td>
		</tr>',
		person_phone_meta_key(),
		__( 'Phone', 'helsinki-sovittelutoiminta-core' ),
		esc_attr( person_phone( $post_id ) ),
		cpt_person_name(),
		__( 'With area code, e.g. +358451234567', 'helsinki-sovittelutoiminta-core' )
	);
}

function render_person_description_input( int $post_id ): void {
	echo sprintf(
		'<tr>
			<th>
				<label for="%1$s-input">%2$s</label>
			</th>
			<td>
				<textarea id="%1$s-input" name="%4$s[%1$s]" rows="3">%3$s</textarea>
			</td>
		</tr>',
		person_description_meta_key(),
		__( 'Description', 'helsinki-sovittelutoiminta-core' ),
		esc_html( person_description( $post_id ) ),
		cpt_person_name()
	);
}
