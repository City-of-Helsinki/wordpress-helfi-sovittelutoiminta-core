<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core\Features\PostTypes\Person;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function posts_table_columns( array $columns ): array {
	$sorted = array();
	foreach ($columns as $key => $value) {
		$sorted[$key] = $value;

		if ( is_checkbox_column( $key ) ) {
			$sorted[thumbnail_column_key()] = '';
		}
	}

	return $sorted;
}

function posts_table_columns_content( string $column, int $post_id ): void {
	if ( is_thumbnail_column( $column ) ) {
		echo get_the_post_thumbnail( $post_id, 'thumbnail' );
	}
}

function posts_table_columns_style(): void {
	echo sprintf(
		'<style>
			.wp-list-table th.column-%1$s { width: 64px; }
			.wp-list-table td.column-%1$s img {
				max-width: 64px;
				max-height: 64px;
				width: auto;
				height: auto;
			}
        </style>',
		thumbnail_column_key()
	);
}

function is_checkbox_column( string $column ): bool {
	return 'cb' === $column;
}

function is_thumbnail_column( string $column ): bool {
	return thumbnail_column_key() === $column;
}

function thumbnail_column_key(): string {
	return 'thumbnail';
}
