<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\PostTypes\Person;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cpt_person_name(): string {
	return 'person';
}

function register_cpt_person(): void {
	register_post_type(
		cpt_person_name(),
		array(
			'labels' => array(
				'name' => __( 'People', 'helsinki-sovittelutoiminta-core' ),
				'singular_name' => __( 'Person', 'helsinki-sovittelutoiminta-core' ),
			),
			'public' => false,
			'show_ui' => true,
			'has_archive' => false,
			'show_in_rest' => true,
			'supports' => array( 'title', 'thumbnail' ),
			'menu_icon' => 'dashicons-admin-users',
		)
	);
}
