<?php
/*
===  Partita Iva per Fattura Elettronica  ===
Contributors: blacklotusconsulting
Tags: fattura-elettronica,fattura,elettronica,woocommerce,woocommerce-fattura-elettronica,codice-fiscale,pec,iva,codice-univoco,codice-cliente,piva,fatturazione-elettronica,fattura-woocommerce,woocommerce-vat,vat-number,vat-number-woocommerce
Requires at least: 5.1
Tested up to: 6.4.1
Requires PHP: 7.1
Stable tag: 1.3.2
Version: 1.3.2
Author:      Alessandro Romani
Author URI:  https://www.blacklotus.eu
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Plugin Name: Partita Iva per Fattura Elettronica
Plugin URI:  https://github.com/blacklotusconsulting/WordpressPartitaIva
Donate Link: https://www.paypal.com/donate/?business=PZXF7XXABDC7C&no_recurring=0&currency_code=EUR

Description: Partita Iva per Fattura Elettronica adds to the Woocommerce standard checkout form some custom fields(VAT Number, Fiscal Code, NIN Code and PEC email address) required for the italian market.
It is possible to choose the fields that will be displayed in the checkout form and set as mandatory field for each field.
Data are stored as Woocommerce Order Meta.
Requires Woocommerce

Partita Iva per Fattura Elettronica Aggiunge il supporto per l'inserimento nel form di pagamento di Woocommerce dei campi Partita IVA, Codice Fiscale, Codice Cliente e indirizzo PEC, necessari alla fatturazione elettronica in Italia.

Con questa plugin in versione gratuita vengono aggiunte al form di pagamento standard di Woocommerce le seguenti funzionalità:
1) Inserimento del campo Codice Fiscale nel form di pagamento di Woocommerce con salvataggio del dato nei metadati dell'ordine
2) Inserimento del campo Partita Iva nel form di pagamento di Woocommerce con salvataggio del dato nei metadati dell'ordine
3) Inserimento del campo Indirizzo PEC nel form di pagamento di Woocommerce con salvataggio del dato nei metadati dell'ordine
4) Inserimento del campo Codice Univoco Iva nel form di pagamento di Woocommerce con salvataggio del dato nei metadati dell'ordine
5) Inserimento del campo Checkbox per la richiesta di fattura elettronica. Se valorizzato in fase di acquisto verrà inviata una mail all'indirizzo dell'amministratore del sito con i dettagli per la fatturazione elettronica.
6) Pannello di controllo per abilitare/disabilitare la visibilità e l'obbligatorietà dei campi nel form di pagamento.
7) Modificare i campi custom della fattura elettronica nella pagina dell'ordine
8) Dalla versione 1.3.0 è stato inserito un radio button per selezionalre il tipo di cliente (Privato o Azienda) che rende obbligatorio il campo CF/P.IVA a seconda della selezione.

Per il corretto funzionamento è necessaria l'installazione di Woocommerce.

== Frequently Asked Questions ==
Does this plugin require Woocommerce?
Yes, it requires Woocommerce.

Is this plugin FREE?
Yes, it is free.

Does this plugin create a PDF of the invoice?
Nope. It just save VAT Number, Fiscal Code, NIN Code and PEC email address as order meta data.

== Changelog ==
1.3.2 Added support for Wordpress 6.4.1 and Woocommerce 8.2.2
1.3.1 Added support for Wordpress 6.3.2 and Woocommerce 8.2.1 Fixed double State/Province field in Woocommerce form. Now is displayed just the standard State field.
1.3.0 Added support for Woocommerce 8.2.0. Fixed issue If the user has already requested the invoice in the past, the user's preference is not respected, so the "billing_fatt" checkbox is not pre-selected. Admin can modify fields in order page. Added radio button field to choose between private and Business Client, with required field change based on selection.
1.2.5 Added support for Wordpress 6.3.1 and Woocommerce 8.0.3
1.2.4 Added support for Wordpress 6.3 and Woocommerce 8.0.3
1.2.3 Added support for Wordpress 6.2 and Woocommerce 7.5.1
1.2.2 Added support for Wordpress 6.0.2. and Woocommerce 6.8.2
1.2.1 Added more information on the readme file, Plugin Banner and Plugin Icon.
1.2 Fix incorrect Stable Tag. Variables and options escaped when echo'd
1.1 Fixed code to Sanitize Escape and Validate data.
1.0 First release.

== Upgrade Notice ==
No upgrade notice so far

== Screenshots ==
1.Form Custom Fields in Order Page
2.Settings to enable/disable and make custom Fields mandatory in Wordpress settings page
3. Admin order page with saved Custom Fields as order meta
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

define('wp_partita_iva_NAME', 'Partita Iva per Fattura Elettronica');
define( 'wp_partita_iva_REQUIRED_PHP_VERSION', '5.3' );                          // because of get_called_class()
define( 'wp_partita_iva_REQUIRED_WP_VERSION',  '3.1' );                          // because of esc_textarea()
define('PREFIX_CSS_URL_WPPI', plugins_url('/css', __FILE__));
define('PREFIX_JS_URL_WPPI', plugins_url('/javascript', __FILE__));

/**tem requirements are met, false if not
 */
function wp_partita_iva_requirements_met() {
	global $wp_version;
	//require_once( ABSPATH . '/wp-admin/includes/plugin.php' );		// to get is_plugin_active() early

	if ( version_compare( PHP_VERSION, wp_partita_iva_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}

	if ( version_compare( $wp_version, wp_partita_iva_REQUIRED_WP_VERSION, '<' ) ) {
		return false;
	}


	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function wp_partita_iva_requirements_error() {
	global $wp_version;

	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if ( wp_partita_iva_requirements_met() ) {
	require_once( __DIR__ . '/classes/wp_partita_iva-module.php' );
	require_once( __DIR__ . '/classes/wordpress_partita_iva.php' );
	require_once( __DIR__ . '/classes/wp_partita_iva-settings.php' );
	require_once( __DIR__ . '/classes/wp_partita_iva-cron.php' );
	require_once( __DIR__ . '/classes/wp_partita_iva-instance-class.php' );

    if (class_exists('WordPress_Partita_IVA')) {
		$GLOBALS['wp_partita_iva'] = WordPress_Partita_IVA::get_instance();
		register_activation_hook(   __FILE__, array( $GLOBALS['wp_partita_iva'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['wp_partita_iva'], 'deactivate' ) );
	}
} else {
	add_action( 'admin_notices', 'wp_partita_iva_requirements_error' );
}