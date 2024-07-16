<?php
/*
 * Plugin Name:       Horarios de INSOTEL MARINE GROUP
 * Plugin URI:        https://softme.es/
 * Description:       Horarios de INSOTEL MARINE GROUP desarrollado por SOFTME.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            SOFTME
 * Author URI:        https://softme.es/
 * License:           GPL v2 or later
 * License URI:       https://softme.es/
 * Update URI:        https://softme.es/
 * Text Domain:       horarios
 * Domain Path:       /languages/
 */

//salir si accede directamente
if (!defined('ABSPATH')) {
    exit;
}

//Creacion opciones menu
define('WPP_HORARIOS_URL', plugin_dir_url(__FILE__));
define('WPP_HORARIOS_PATH', realpath(plugin_dir_path(__FILE__)));


function insotel_horarios_menu()
{
    add_menu_page('Insotel Horarios', 'Insotel Horarios', 'manage_options', WPP_HORARIOS_PATH . '/admin/main.php', null, 'dashicons-calendar-alt');
    add_submenu_page(WPP_HORARIOS_PATH . '/admin/main.php', 'Configuración textos', 'Configuración textos', 'manage_options', WPP_HORARIOS_PATH . '/admin/configuracion_textos.php', null);
    add_submenu_page(WPP_HORARIOS_PATH . '/admin/main.php', 'Configuración idiomas', 'Configuración idiomas', 'manage_options', WPP_HORARIOS_PATH . '/admin/configuracion_idiomas.php', null);
    add_submenu_page(WPP_HORARIOS_PATH . '/admin/main.php', 'Configuración constantes', 'Configuración constantes', 'manage_options', WPP_HORARIOS_PATH . '/admin/configuracion_constantes.php', null);
}
add_action('admin_menu', 'insotel_horarios_menu');

//Añadir Shortcode
add_shortcode('insotel_horarios', 'insotel_horarios_shortcode');

function insotel_horarios_shortcode($atts)
{
    // Enqueue todos los estilos y scripts registrados
    wp_enqueue_script('insotel_horarios_jquery');
    wp_enqueue_script('insotel_horarios_jquery-ui');
    wp_enqueue_script('insotel_horarios_jquery_alternative');
    wp_enqueue_script('moment_js_motor');

    wp_enqueue_style('insotel_horarios_bootstrap_css');
    wp_enqueue_script('insotel_horarios_popper_js');
    wp_enqueue_script('insotel_horarios_bootstrap_js');

    wp_enqueue_style('insotel_horarios_fontawesome_css');

    wp_enqueue_style('insotel_horarios_main_css');

    wp_enqueue_script('insotel_horarios_daterangepicker_js');
    wp_enqueue_style('insotel_horarios_daterangepicker_css');
    
    ob_start();
    include(WPP_HORARIOS_PATH . '/public/main.php');
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function registrar_librerias_insotel_horarios()
{
    // Registrar bootstrap.css
    wp_enqueue_style(
        'insotel_horarios_bootstrap_css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        array(),
        '5.3.3'
    );
    
    // Registrar popper.js
    wp_enqueue_script(
        'insotel_horarios_popper_js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
        array(),
        '1.14.3',
        true
    );

    // Registrar bootstrap.js
    wp_enqueue_script(
        'insotel_horarios_bootstrap_js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        array('insotel_horarios_jquery', 'insotel_horarios_popper_js'),
        '5.3.3',
        true
    );


    // Registrar main.css
    wp_register_style(
        'insotel_horarios_main_css',
        plugins_url('public/main.css', __FILE__),
        array(),
        null
    );

    // Registrar Font Awesome CSS
    wp_register_style(
        'insotel_horarios_fontawesome_css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css',
        array(),
        '5.9.0'
    );

    // Registrar jQuery
    wp_register_script(
        'insotel_horarios_jquery',
        'https://code.jquery.com/jquery-3.6.3.min.js',
        array(),
        '3.6.3',
        true
    );

    // Registrar jQuery UI
    wp_register_script(
        'insotel_horarios_jquery-ui',
        'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js',
        array('insotel_horarios_jquery'),
        '1.13.2',
        true
    );

    // Registrar jQuery (alternative CDN)
    wp_register_script(
        'insotel_horarios_jquery_alternative',
        'https://cdn.jsdelivr.net/jquery/latest/jquery.min.js',
        array(),
        null,
        true
    );

    // Registrar Moment.js
    wp_register_script(
        'insotel_horarios_moment_js',
        'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',
        array(),
        null,
        true
    );

    // Registrar DateRangePicker JS
    wp_register_script(
        'insotel_horarios_daterangepicker_js',
        'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
        array('insotel_horarios_jquery', 'insotel_horarios_moment_js'),
        null,
        true
    );

    // Registrar DateRangePicker CSS
    wp_register_style(
        'insotel_horarios_daterangepicker_css',
        'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',
        array(),
        null
    );

    // Registrar Bootstrap Input Spinner
    wp_register_script(
        'insotel_horarios_bootstrap_input_spinner',
        plugins_url('admin/js/bootstrap-input-spinner.js', __FILE__),
        array(),
        '6.1.1',
        true
    );
}
// Engancha la función a wp_enqueue_scripts
add_action('wp_enqueue_scripts', 'registrar_librerias_insotel_horarios');




// Creamos la base da datos
function wp_learn_create_database_table()
{
    global $wpdb;
    require_once WPP_HORARIOS_PATH . '/helpers/Insotel_Horarios_Bd.php';
    $Insotel_Horarios_bd = new Insotel_Horarios_Bd;
    $Insotel_Horarios_bd->create_table_insotel_horarios_idiomas($wpdb);
    $Insotel_Horarios_bd->create_table_insotel_horarios_textos($wpdb);
    $Insotel_Horarios_bd->create_table_insotel_horarios_constantes($wpdb);
}
register_activation_hook(__FILE__, 'wp_learn_create_database_table');
