<?php
/**
 * Plugin Name: RW-Schedule
 * Plugin URI: https://navmohan.site
 * Author: Navaneeth Mohan
 * Author URI: https://navmohan.site
 * Description: Maintian a custom form for holding meta values of CPT RW-Schedule
 * Version: 0.1.0
 * License: GPL2
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: prefix-plugin-name
*/


include_once("globals.php");
include_once("cpt.php");
include_once("customfield__showhosts.php");
include_once("customfield__showcategory.php");
include_once("customfield__dayofweek.php");
include_once("customfield__showtime.php");
include_once("assert-non-empty.php");
// include_once("assert-unique.php");


add_action('save_post_rwshow', 'on_save_post_rwshow',10,1);
function on_save_post_rwshow($post_id) {
    // dont updateh wp_postmeta if one of these conditions
    if (!isset($_POST[CUSTOMFORM_NONCE_NAME])) return;
    if (!wp_verify_nonce($_POST[CUSTOMFORM_NONCE_NAME], CUSTOMFORM_NONCE_ACTION)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST[CUSTOM_FIELD_KEY__SHOWHOST])) return; 
    if (!isset($_POST[CUSTOM_FIELD_KEY__SHOWCATEGORY])) return; 
    if (!isset($_POST[CUSTOM_FIELD_KEY__DAYOFWEEK])) return; 
    if (!isset($_POST[CUSTOM_FIELD_KEY__STARTTIME])) return; 
    if (!isset($_POST[CUSTOM_FIELD_KEY__ENDTIME])) return; 
    if (!current_user_can('edit_post', $post_id)) return;

    // ok. everything looks good. go ahead and update wp_postmeta
    update_post_meta($post_id, CUSTOM_FIELD_KEY__SHOWHOST, sanitize_text_field($_POST[CUSTOM_FIELD_KEY__SHOWHOST]));
    update_post_meta($post_id, CUSTOM_FIELD_KEY__COHOST1, sanitize_text_field($_POST[CUSTOM_FIELD_KEY__COHOST1]));
    update_post_meta($post_id, CUSTOM_FIELD_KEY__COHOST2, sanitize_text_field($_POST[CUSTOM_FIELD_KEY__COHOST2]));
    update_post_meta($post_id, CUSTOM_FIELD_KEY__SHOWCATEGORY, sanitize_text_field($_POST[CUSTOM_FIELD_KEY__SHOWCATEGORY]));
    update_post_meta($post_id, CUSTOM_FIELD_KEY__DAYOFWEEK, sanitize_text_field($_POST[CUSTOM_FIELD_KEY__DAYOFWEEK]));
    update_post_meta($post_id, CUSTOM_FIELD_KEY__STARTTIME, sanitize_text_field($_POST[CUSTOM_FIELD_KEY__STARTTIME]));
    update_post_meta($post_id, CUSTOM_FIELD_KEY__ENDTIME, sanitize_text_field($_POST[CUSTOM_FIELD_KEY__ENDTIME]));

};


/**
 * For the frontend (the fancy table), I have to make some changes to 
 * the class-Schedule. Specifically, I'll have to make it fetch from 
 * the wp_posts and wp_postmeta tables. 
 * The wp_posts table will contain the show-title and show-description
 * The wp_postmeta will contain the show-title, show-host-id, co-host-1-id, co-host-2-id, show-category, day-of-week, start-time, end-time, 
 */





/**
 * To help debug this plugin I disabled wordpress's revision
 *      wp-config.php
 *      define('WP_POST_REVISIONS', false);
 * But for some reason wordpress will still create a revision if I follow these steps
 *      1) create a post. publish it
 *      2) exit the post editor.
 *      3) reopen the post and make some changes to the post (eg: change the title)
 *      4) SAVE the post. Control+S or Command+S. Dont hit publish. Just save it using the Wordpress auto-save feature.
 *      5) Now that will be a reviison. And that revision will persist even if you publish this saved post. 
 * Besides revisions, Wordpress will also create Auto-Drafts. These are created when you first open a editor. 
 * WP will immediately auto-save it. Super annoying but apparently it helps deal with race-conditions when two people publish 
 * at the same time. Also Auto-Drafts are automatically deleted after 7 days. 
 * So basically for debugging purposes I use this one-line SQL command. It's multiple commands spearated by ";"
 * select * from wp_postmeta;select ID,post_title,post_status,post_type,post_name,guid,post_parent from wp_posts; select ID,post_title,post_status,post_type,post_name,guid,post_parent from wp_posts where not (post_type = 'revision' or post_status='auto-draft');
 */