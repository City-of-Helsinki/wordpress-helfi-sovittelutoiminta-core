<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
  * Init
  */
add_action( 'helsinki_sovittelutoiminta_core_loaded', __NAMESPACE__ . '\\init' );
function init() : void {
	add_action( 'init', __NAMESPACE__ . '\\register_blocks', 10 );
	add_action( 'block_categories_all', __NAMESPACE__ . '\\register_categories', 10, 2 );

	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\editor_assets' );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\public_assets', 11 );

	add_filter( 'load_script_translation_file', __NAMESPACE__ . '\\translations_location', 10, 3 );
}

/**
  * Config
  */
function blocks() : array {
	return apply_filters( 'helsinki_sovittelutoiminta_core_load_config', 'blocks' );

}

function categories() : array {
	return apply_filters( 'helsinki_sovittelutoiminta_core_load_config', 'block-categories' );
}

function translations_location( string $file, string $handle, string $domain ) : string {
	if ( 'helsinki-sovittelutoiminta-core' !== $domain || ! $file ) {
		return $file;
	}

	$replace_path = ('acf-person-list-editor-script' === $handle)
		|| ( strpos( $handle, 'helsinki-sovittelutoiminta-core' ) !== false );

	$path = apply_filters( 'helsinki_sovittelutoiminta_core_plugin_path', '' );

	return $replace_path
		? str_replace( WP_LANG_DIR . '/plugins', $path . 'languages', $file )
		: $file;
}

/**
  * Register
  */
function register_blocks() : void {
	foreach ( blocks() as $name => $args) {
		if ( ! empty( $args['render_callback'] ) ) {
			$args['render_callback'] = __NAMESPACE__ . '\\' . $args['render_callback'];

			require_once path_to_block_callback( $name );
		}

		register_block_type( path_to_block_json( $name ), $args );
	}
}

function register_categories(array $categories, $editor_context) : array {
	return array_merge( $categories, categories() );
}

function path_to_block_json( string $name ) : string {
	return apply_filters(
		'helsinki_sovittelutoiminta_core_path_to_file',
		implode(
			DIRECTORY_SEPARATOR,
			array( 'features', 'blocks', $name, 'block.json' )
		)
	);
}

function path_to_block_callback( string $name ) : string {
	return apply_filters(
		'helsinki_sovittelutoiminta_core_path_to_php_file',
		array( 'features', 'blocks', $name, 'render' )
	);
}
