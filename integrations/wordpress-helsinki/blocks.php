<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Integrations\WordPressHelsinki\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'helsinki_sovittelutoiminta_core_loaded', __NAMESPACE__ . '\\init' );
function init(): void {
	add_filter( 'helsinki_wp_allowed_blocks', __NAMESPACE__ . '\\filter_allowed_blocks' );
}

function filter_allowed_blocks( array $blocks ): array {
	if ( isset( $blocks['common'] ) ) {
		$blocks['common']['acf/person-list'] = true;
	}

	return $blocks;
}
