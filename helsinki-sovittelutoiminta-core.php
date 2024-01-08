<?php
/*
Plugin Name:  Helsinki Sovittelutoiminta Core
Plugin URI:   https://genero.fi
Description:  Register Post Types and Taxonomies for site
Version:      2.0.0
Author:       Genero
Author URI:   https://genero.fi/
License:      MIT License
*/

namespace Genero\Site;

if (!is_blog_installed()) {
    return;
}

/**
 * Register custom post types and taxonomies with WP.
 *
 * @see https://github.com/jjgrainger/PostTypes
 * @see https://developer.wordpress.org/resource/dashicons/
 */
class PostTypes
{
    private static $instance = null;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register all post types and their taxonomies.
     * @note needs to run during `init`.
     */
    public function register()
    {
        $this->registerPost();
        $this->registerPage();
        $this->registerPerson();
    }

    public function registerPost()
    {
		// Set `has_archive` for compatibility with `post-type-archive-mapping`.
        add_filter('register_post_type_args', function ($args, $post_type) {
            if ($post_type === 'post') {
                $args['has_archive'] = true;
                $args['rewrite'] = [
                    'with_front' => true,
                ];
            }
            return $args;
        }, 10, 2);
    }

    public function registerPage()
    {
		add_action('init', function () {
            add_post_type_support('page', 'excerpt');
        });
    }

    public function registerPerson()
    {
		add_action('init', function () {
            register_post_type('person', array(
				'labels' => array(
					'name' => 'Persons',
					'singular_name' => 'Person',
				),
				'public' => false,
	            'show_ui' => true,
	            'has_archive' => false,
	            'show_in_rest' => true,
	            'supports' => ['title', 'thumbnail'],
				'menu_icon' => 'dashicons-admin-users',
			));
        });

		add_filter('manage_person_posts_columns', function($columns){
			$sorted = array();
			foreach ($columns as $key => $value) {
				$sorted[$key] = $value;

				if ( 'cb' === $key ) {
					$sorted['thumbnail'] = '';
				}
			}

			return $sorted;
		});

		add_action('manage_person_posts_custom_column', function($column, $post_id){
			if ('thumbnail' === $column) {
				echo get_the_post_thumbnail($post_id, 'thumbnail');
			}
		}, 10, 2);
    }

    public function adminHead()
    {
        echo '<style>
            .wp-list-table th.column-thumbnail { width: 28px; }
            .wp-list-table td.column-thumbnail img {
                max-width: 37px;
                max-height: 37px;
                width: auto;
                height: auto;
            }
        </style>';
    }
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\init_plugin', 9 );
function init_plugin() {
	$plugin = PostTypes::getInstance();

	$plugin->register();

	add_action('admin_head', [$plugin, 'adminHead']);
}
