<?php

add_filter('wp_insert_post_data', function (array $data, array $raw) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $data;
    if(!empty($_GET['action']) && $_GET['action'] === 'trash') return $data; // Make sure to do nothing for posts that are going to be deleted

    $post_id = (empty($data['ID']) ? $raw['ID'] : $data['ID']) ?: 0;

    /** @var wpdb $wpdb */
    global $wpdb;
    $query = $wpdb->prepare(CPTWRCF_SQL, CUSTOM_FIELD_KEY__SHOWHOST, @$_POST[CUSTOM_FIELD_KEY__SHOWHOST], $post_id);
    $found = $wpdb->get_results($query, ARRAY_A);

    // If we found posts with save Video URL - notify user and save post as a draft
    if($found) {
        $data['post_status'] = 'draft';
        add_filter('redirect_post_location', function ($location){
            $location = remove_query_arg('message', $location);
            $location = add_query_arg('message', 10, $location); // 10 is for "Post draft updated" message from `edit-form-advanced.php`
            return add_query_arg(CPTWRCF_NOT_UNIQUE_QUERY_STRING_KEY, 1, $location);
        });
    } else {
        add_filter('redirect_post_location', function ($location){
            return remove_query_arg(CPTWRCF_NOT_UNIQUE_QUERY_STRING_KEY, $location);
        });
    }

    return $data;
}, 10, 2); // Notice last arg: 2 - is for getting raw data which is used to determine post id

add_action('admin_notices', function () {
    if (isset($_GET[CPTWRCF_NOT_UNIQUE_QUERY_STRING_KEY])) {
        /** @var wpdb $wpdb */
        global $wpdb;

        /** @var WP_Post $post */
        global $post;

        // <editor-fold desc="Optional: Get post links that has same Video URL">
        $meta_value = get_post_meta($post->ID, CUSTOM_FIELD_KEY__SHOWHOST, true);
        $query = $wpdb->prepare(CPTWRCF_SQL, CUSTOM_FIELD_KEY__SHOWHOST, $meta_value, $post->ID);
        $items = $wpdb->get_results($query, ARRAY_A);
        $items = array_map(function ($item) {
            return sprintf('<a target="_blank" href="%s">%s</a>', get_permalink($item['post_id']), get_the_title($item['post_id']));
        }, $items);
        $items = implode(', ', $items);
        $used_by = sprintf('by %s', $items);
        // </editor-fold>

        $link = sprintf('<b><a href="#%s">%s</a></b>', CUSTOM_FIELD_KEY__SHOWHOST, CUSTOM_FIELD_LABEL__SHOWHOST);
        $message = sprintf(__('Your post was saved as draft because %s is already used %s'), $link, $used_by);
        echo sprintf('<div class="error"><p>%s</p></div>', $message);
    }
});

