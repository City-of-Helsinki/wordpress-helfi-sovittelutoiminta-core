<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'blocks' => array(
		'assets',
		'init',
	),
	'post-types' => array(
		'page',
		'person' => array(
			'post-type',
			'posts-table-columns',
			'post-meta',
			'save-post',
			'metabox',
			'init',
		),
	),
);
