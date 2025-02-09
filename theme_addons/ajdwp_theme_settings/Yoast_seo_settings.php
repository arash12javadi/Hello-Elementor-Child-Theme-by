<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//--------------------------- Yoast Seo Settings ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['disable_yoast_metabox'])) {
    //---------- Removes the Yoast Metabox for Roles other then Admins
    //---------- Returns true if user has specific role
    function check_user_role( $role, $user_id = null ) {
        if ( is_numeric( $user_id ) )
        $user = get_userdata( $user_id );
        else
        $user = wp_get_current_user();
        if ( empty( $user ) )
        return false;
        return in_array( $role, (array) $user->roles );
    }

    //---------- Disable WordPress SEO meta box for all roles other than administrator and seo
    function wpse_init(){
    if( !( check_user_role( 'seo' ) || check_user_role( 'administrator' )) ) {
            // Remove page analysis columns from post lists, also SEO status on post editor
            add_filter( 'wpseo_use_page_analysis', '__return_false' );
            // Remove Yoast meta boxes
            add_action( 'add_meta_boxes', 'disable_seo_metabox', 100000 );
        }
    }
    add_action('init', 'wpse_init');

    function disable_seo_metabox() {
        remove_meta_box( 'wpseo_meta', 'post', 'normal' );
        remove_meta_box( 'wpseo_meta', 'page', 'normal' );
    }
}

//--------------------------- Remove Yoast SEO Columns ---------------------------//
if (!empty($options['remove_yoast_seo_columns'])) {
    add_filter( 'manage_edit-post_columns', 'yoast_seo_admin_remove_columns', 10, 1 );
    add_filter( 'manage_edit-page_columns', 'yoast_seo_admin_remove_columns', 10, 1 );

    function yoast_seo_admin_remove_columns( $columns ) {
        unset($columns['wpseo-score']);
        unset($columns['wpseo-score-readability']);
        unset($columns['wpseo-title']);
        unset($columns['wpseo-metadesc']);
        unset($columns['wpseo-focuskw']);
        unset($columns['wpseo-links']);
        unset($columns['wpseo-linked']);
        return $columns;
    }

}

?>