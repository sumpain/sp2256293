<?php
/**
 * add custom scripts (muss an erster Stelle kommen *******************************************
 */

function my_scripts_method() {
    wp_enqueue_script(
        'child-theme-js',
        get_stylesheet_directory_uri() . '/js/custom.js',
        array( 'jquery' )  
		);

}
add_action('wp_enqueue_scripts', 'my_scripts_method');


/**
 * get parent styles ************************************************************************
 */

function theme_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');


/**
 * remove 'Add sw shortcode' button from editor **********************************************
 */

function remove_bws_short_code_buttons( $plugin_array ) {
	if( isset( $plugin_array['add_bws_shortcode'] ) ) {
		unset( $plugin_array['add_bws_shortcode'] );
	}
	return $plugin_array;
}
add_filter( 'mce_external_plugins', 'remove_bws_short_code_buttons', 99, 1 );


/**
 * remove IP from comments
 */

function  remove_comments_ip( $comment_author_ip ) {
	return '';
}
add_filter( 'pre_comment_user_ip', 'remove_comments_ip' );



// Fügt die Funktion dem Standard WP Kommentar Formular hinzu
add_filter( 'comment_form_default_fields', 'webshaped_comment_form_privacy_input' );
// Die Funktion erstellt die Checkbox für das Standard WP Kommentar Formular
function webshaped_comment_form_privacy_input( $fields ) {
	$req      = true;
	$aria_req = ( $req ? " aria-required='true'" : '' );

	// Elemente dem Kommentar Formular hinzufügen
	$fields['datenschutz'] =
	'<div class="comment-form-datenschutz"><input id="datenschutzcomment" class="comment-datenschutz" name="datenschutzcomment" type="checkbox"' . $aria_req . ' />' .
	'<label class="comment-datenschutz" for="datenschutzcomment">' . ( $req ? ' <span class="required">*</span>' : '' ) . __( 'Ich stimme zu, dass meine personenbezogenen Daten aus dieser Übermittlung, gemäß der <a href="/datenschutzerklaerung" target="_blank">Datenschutzerklärung</a> gespeichert, verarbeitet und genutzt werden dürfen.' ) . '</label></div>';

	return $fields;
}

add_filter( 'preprocess_comment', 'webshaped_verify_comment_meta_data' );
function webshaped_verify_comment_meta_data( $commentdata ) {
	// Wenn die Checkbox leer ist und ein Gastnutzer schreiben möchte...
	if ( empty( $_POST['datenschutzcomment'] ) and ! current_user_can( 'read' ) ) {
		// ... zeige folgenden Fehlertext an
		wp_die( __( '<strong>FEHLER</strong>: Die Datenschutzbox wurde nicht akzeptiert.<br><br><a href="javascript:history.back()">« Zurück</a>' ) );
	}
	return $commentdata;
}
