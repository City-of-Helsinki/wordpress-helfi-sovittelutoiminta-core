<?php
/**
* Plugin Name: Helsinki Sovittelutoiminta Core
* Description: Site specific features
* Requires at least: 6.8
* Requires PHP: 8.2
* Version: 3.0.0
* Author: City of Helsinki
* Author URI: https://www.hel.fi
* License: MIT License
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: helsinki-sovittelutoiminta-core
* Domain Path: /languages
*/

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Sovittelutoiminta\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
  * Setup
  */
require_once \plugin_dir_path( __FILE__ ) . 'constants.php';
define_constants( __FILE__ );

require_once \plugin_dir_path( __FILE__ ) . 'functions.php';
load_includes();

spl_autoload_register( __NAMESPACE__ . '\\class_loader' );

\add_action( 'helsinki_sovittelutoiminta_core_setup', __NAMESPACE__ . '\\setup_filters', 0 );
\add_action( 'helsinki_sovittelutoiminta_core_setup', __NAMESPACE__ . '\\load_features', 1 );
\add_action( 'helsinki_sovittelutoiminta_core_setup', __NAMESPACE__ . '\\load_integrations', 1 );

\add_action( 'plugins_loaded', __NAMESPACE__ . '\\setup', 1 );
\add_action( 'plugins_loaded', __NAMESPACE__ . '\\loaded', 10 );

/**
  * Init
  */
\add_action( 'init', __NAMESPACE__ . '\\textdomain' );
\add_action( 'init', __NAMESPACE__ . '\\init', 100 );

/**
  * Activation
  */
\register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

/**
  * Deactivation
  */
\register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );

/**
  * Uninstall
  */
// \register_uninstall_hook( __FILE__, __NAMESPACE__ . '\\uninstall' );
