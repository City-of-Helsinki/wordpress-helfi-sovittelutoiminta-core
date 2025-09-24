<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function setup() : void {
	if ( ! \did_action( 'helsinki_sovittelutoiminta_core_setup' ) ) {
		\do_action( 'helsinki_sovittelutoiminta_core_setup' );
	}
}

function loaded() : void {
	if ( ! \did_action( 'helsinki_sovittelutoiminta_core_loaded' ) ) {
		\do_action( 'helsinki_sovittelutoiminta_core_loaded' );
	}
}

function init() : void {
	if ( ! \did_action( 'helsinki_sovittelutoiminta_core_init' ) ) {
		\do_action( 'helsinki_sovittelutoiminta_core_init' );
	}
}

function activate() : void {
	setup();

	\do_action( 'helsinki_sovittelutoiminta_core_activate' );
}

function deactivate() : void {
	setup();

	\do_action( 'helsinki_sovittelutoiminta_core_deactivate' );
}

function uninstall() : void {
	setup();

	\do_action( 'helsinki_sovittelutoiminta_core_uninstall' );
}
