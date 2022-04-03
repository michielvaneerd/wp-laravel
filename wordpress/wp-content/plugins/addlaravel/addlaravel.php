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
