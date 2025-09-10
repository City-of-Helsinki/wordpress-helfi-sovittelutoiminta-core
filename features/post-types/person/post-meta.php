<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\PostTypes\Person;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function person_meta_handlers(): array {
	return array(
		person_email_meta_key() => __NAMESPACE__ . '\\update_person_email',
		person_phone_meta_key() => __NAMESPACE__ . '\\update_person_phone',
		person_description_meta_key() => __NAMESPACE__ . '\\update_person_description',
	);
}

/**
  * Email
  */
function person_email_meta_key(): string {
	return 'email';
}

function person_email( int $post_id ): string {
	return get_post_meta( $post_id, person_email_meta_key(), true ) ?: '';
}

function update_person_email( int $post_id, $email = '' ): bool {
	$email = filter_var( $email, FILTER_SANITIZE_EMAIL );
	if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
		$email = '';
	}

	return update_post_meta( $post_id, person_email_meta_key(), $email ) ? true : false;
}

function provide_person_email( string $value, int $post_id ): string {
	return person_email( $post_id );
}

/**
  * Phone
  */
function person_phone_meta_key(): string {
	return 'phone';
}

function person_phone( int $post_id ): string {
	return get_post_meta( $post_id, person_phone_meta_key(), true ) ?: '';
}

function update_person_phone( int $post_id, $phone = '' ): bool {
	$phone = sanitize_text_field( $phone );

	return update_post_meta( $post_id, person_phone_meta_key(), $phone ) ? true : false;
}

function provide_person_phone( string $value, int $post_id ): string {
	return person_phone( $post_id );
}

/**
  * Description
  */
function person_description_meta_key(): string {
	return 'description';
}

function person_description( int $post_id ): string {
	return get_post_meta( $post_id, person_description_meta_key(), true ) ?: '';
}

function update_person_description( int $post_id, $description = '' ): bool {
	$description = sanitize_textarea_field( $description );

	return update_post_meta( $post_id, person_description_meta_key(), $description ) ? true : false;
}

function provide_person_description( string $value, int $post_id ): string {
	return person_description( $post_id );
}
