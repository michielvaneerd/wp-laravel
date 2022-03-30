<?php
/**
 * Plugin Name: Add Laravel
 */

use Illuminate\Contracts\Http\Kernel;
use App\Models\Stat;

// Make Laravel available from within Wordpress.\
// So we can use Eloquent, blade, etc.
function my_laravel_start() {
    // Almost the same as laravel/public/index.php
    // But don't handle the request, so only bootstrap the Kernel.
    require __DIR__ . '/../../../../laravel/vendor/autoload.php';
    $app = require_once __DIR__ . '/../../../../laravel/bootstrap/app.php';
    $kernel = $app->make(Kernel::class);
    $kernel->bootstrap();
}

function my_laravel_settings_page() {
    my_laravel_start();
    $view = view('backend.settings-index', [
        'stats' => Stat::all()
    ]);
    echo $view->render();
}

function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
        __( 'Custom Menu Title', 'textdomain' ),
        'custom menu',
        'manage_options',
        'lar',
        'my_laravel_settings_page',
        ''
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

add_action('init', function() {

    register_post_type(
        'article',
        [
            'label' => 'Article',
            'public' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'custom-fields']
        ]
    );

    register_taxonomy(
        'ziektebeeld',
        ['post', 'page', 'article'], // hier toevoegen van custom post types.
        [
        'hierarchical' => true,
        'show_in_rest' => true,
         'labels' => [
             'name' => 'Ziektebeeld'
         ],
         'show_ui' => true,
         'show_admin_column' => true,
         'query_var' => true,
         'rewrite' => [ 'slug' => 'ziektebeeld' ],
    ]);

});

// Voor extra data toevoegen aan custom taxonomies:
// https://www.smashingmagazine.com/2015/12/how-to-use-term-meta-data-in-wordpress/

// Show extra create form fields
add_action('ziektebeeld_add_form_fields', function($tax) {
    ?>
    <div>
        <input type="text" name="ziektebeeld_color" value="">
    </div>
    <?php
}, 10, 2);

// Add new field after create
add_action('created_ziektebeeld', function($term_id, $tt_id) {
    if( !empty($_POST['ziektebeeld_color'])) {
        $group = sanitize_title( $_POST['ziektebeeld_color'] );
        add_term_meta( $term_id, 'ziektebeeld_color', $group, true );
    }
}, 10, 2);

// Show extra update form fields
add_action('ziektebeeld_edit_form_fields', function($term, $taxonomy) {
    $ziektebeeld_color = get_term_meta($term->term_id, 'ziektebeeld_color', true );
    ?>
    <tr><th>Color</th><td>
        <input type="text" name="ziektebeeld_color" value="<?php echo $ziektebeeld_color; ?>">
</td>
</tr>
    <?php
}, 10, 2);

// Update new field after update
add_action('edited_ziektebeeld', function($term_id, $tt_id) {
    if( !empty($_POST['ziektebeeld_color'])) {
        $group = sanitize_title( $_POST['ziektebeeld_color'] );
        update_term_meta( $term_id, 'ziektebeeld_color', $group);
    }
}, 10, 2);

register_term_meta('ziektebeeld', 'ziektebeeld_color', ['show_in_rest' => true] );