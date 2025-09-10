<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\PostTypes\Person;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
  * Init
  */
add_action( 'helsinki_sovittelutoiminta_core_loaded', __NAMESPACE__ . '\\init' );
function init(): void {
	add_action( 'init', __NAMESPACE__ . '\\register_cpt_person' );

	$cpt = cpt_person_name();

	add_filter( "manage_{$cpt}_posts_columns", __NAMESPACE__ . '\\posts_table_columns' );
	add_action( "manage_{$cpt}_posts_custom_column", __NAMESPACE__ . '\\posts_table_columns_content', 10, 2 );
	add_action( 'admin_head', __NAMESPACE__ . '\\posts_table_columns_style' );

	add_action( 'add_meta_boxes_' . $cpt, __NAMESPACE__ . '\\register_person_metabox' );
	add_action( 'save_post_' . $cpt, __NAMESPACE__ . '\\save_person_post' );

	add_filter( 'helsinki_sovittelutoiminta_core_person_cpt_name', __NAMESPACE__ . '\\cpt_person_name' );
	add_filter( 'helsinki_sovittelutoiminta_core_person_email', __NAMESPACE__ . '\\provide_person_email', 10, 2 );
	add_filter( 'helsinki_sovittelutoiminta_core_person_phone', __NAMESPACE__ . '\\provide_person_phone', 10, 2 );
	add_filter( 'helsinki_sovittelutoiminta_core_person_description', __NAMESPACE__ . '\\provide_person_description', 10, 2 );
}
