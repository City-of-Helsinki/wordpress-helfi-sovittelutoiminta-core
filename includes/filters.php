<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function setup_filters() : void {
	foreach ( filter_names() as $filter ) {
		add_filter( 'helsinki_sovittelutoiminta_core_' . $filter, __NAMESPACE__ . '\\' . $filter );
	}
}

function filter_names(): array {
	return array(
		'plugin_version',
		'plugin_main_file',
		'plugin_path',
		'plugin_dirname',
		'plugin_basename',
		'plugin_slug',
		'plugin_name',
		'plugin_url',
		'is_debug',
		'asset_version',
		'assets_url',
		'images_url',
		'script_url',
		'style_url',
		'path_to_file',
		'path_to_php_file',
		'load_config',
	);
}
