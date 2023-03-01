<?php
// this serves the JSON for rendering the timetable on the frontned. 
require_once("/var/www/fm949.ca/wp-load.php");
require_once('/var/www/fm949.ca/wp-admin/includes/upgrade.php' );
require_once('/var/www/fm949.ca/wp-content/plugins/RW-Schedule/globals.php');

global $wpdb;

function rw_schedule_get_timetable()
{
    global $ALL_SHOW_CATEGORIES;
    global $ALL_SHOW_HOSTS;
    global $ALL_DAYS_OF_WEEK;
    global $ALL_TIMES_OF_DAY;

    $timetable = array();
    $query = new WP_Query(array('post_type'=>'rwshow','post_status'=>'publish'));
    while($query->have_posts())
    {
        $query->the_post();
        $show_category = get_post_meta(get_the_ID(), CUSTOM_FIELD_KEY__SHOWCATEGORY, true);
        $showhost_id = get_post_meta(get_the_ID(), CUSTOM_FIELD_KEY__SHOWHOST, true);
        $cohost_id1 = get_post_meta(get_the_ID(), CUSTOM_FIELD_KEY__COHOST1, true);
        $cohost_id2 = get_post_meta(get_the_ID(), CUSTOM_FIELD_KEY__COHOST2, true);
        $show_starttime = get_post_meta(get_the_ID(), CUSTOM_FIELD_KEY__STARTTIME, true);
        $show_endtime = get_post_meta(get_the_ID(), CUSTOM_FIELD_KEY__ENDTIME, true);
        $dayofweek = get_post_meta(get_the_ID(), CUSTOM_FIELD_KEY__DAYOFWEEK, true);
        $show_title = get_the_title();
        $show_description = get_the_content();

        $show_details = array(
            'host'         => get_userdata($showhost_id)->data->user_login,
            'cohost1'          => get_userdata($cohost_id1)->data->user_login,
            'cohost2'          => get_userdata($cohost_id2)->data->user_login,
            'startTime'    => $ALL_TIMES_OF_DAY[$show_starttime],
            'endTime'      => $ALL_TIMES_OF_DAY[$show_endtime],
            'day'         => $dayofweek,
            'title'        => $show_title,
            'description'  => $show_description,
            'category'  => $show_category,
        );
        $timetable[] = $show_details;
    }
    wp_send_json_success($timetable);
    die();
}


add_action('wp_ajax_rw_schedule_get_timetable', 'rw_schedule_get_timetable');           // for logged in user
add_action('wp_ajax_nopriv_rw_schedule_get_timetable', 'rw_schedule_get_timetable');    // if user not logged in

;?>