<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\PostTypes\Person;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function save_person_post( int $post_id ): void {
	if ( can_save_person_metabox( $post_id ) ) {
		save_person_post_meta( $post_id );
	}
}

function save_person_post_meta( int $post_id ): void {
	foreach ( person_meta_handlers() as $meta_key => $handler ) {
		call_user_func( $handler, $post_id, $_POST[cpt_person_name()][$meta_key] ?? '' );
	}
}

function can_save_person_metabox( int $post_id ): bool {
	return is_post_id_for_post( $post_id )
		&& current_user_can_edit_post( $post_id )
		&& verify_person_metabox_nonce( $post_id );
}

function person_metabox_nonce_name(): string {
	return cpt_person_name() . '_nonce';
}

function person_metabox_nonce_action( int $post_id ): string {
	return person_metabox_nonce_name() . '_' . $post_id;
}

function verify_person_metabox_nonce( int $post_id ): bool {
	$nonce = $_POST[person_metabox_nonce_name()] ?? '';

	return wp_verify_nonce( $nonce, person_metabox_nonce_action( $post_id ) ) ? true : false;
}

function current_user_can_edit_post( int $post_id ): bool {
	return current_user_can( 'edit_post', $post_id );
}

function is_post_id_for_post( int $post_id ): bool {
	return ! wp_is_post_autosave( $post_id )
		&& ! wp_is_post_revision( $post_id );
}
