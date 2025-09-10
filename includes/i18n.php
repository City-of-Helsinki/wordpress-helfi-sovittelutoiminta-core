<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function textdomain() {
	load_plugin_textdomain(
		'helsinki-sovittelutoiminta-core',
		false,
		apply_filters( 'helsinki_sovittelutoiminta_core_plugin_dirname', '' ) . '/languages'
	);
}
