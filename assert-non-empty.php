<?php

add_filter('wp_insert_post_data', 'on_insert_post_data');
function on_insert_post_data(array $data) {
    if($data['post_type']!=CUSTOM_POST_TYPE__RWSHOW) return $data;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $data;
    if(!empty($_GET['action']) && $_GET['action'] === 'trash') return $data; // Make sure to do nothing for posts that are going to be deleted

    // If onw of these booleans are true - mark post as draft and notify user
    $is_empty__showhost = empty($_POST[CUSTOM_FIELD_KEY__SHOWHOST]);
    $is_empty__showcategory = empty($_POST[CUSTOM_FIELD_KEY__SHOWCATEGORY]);
    $is_empty__dayofweek = empty($_POST[CUSTOM_FIELD_KEY__DAYOFWEEK]);
    $is_empty__starttime = empty($_POST[CUSTOM_FIELD_KEY__STARTTIME]);
    $is_empty__endtime = empty($_POST[CUSTOM_FIELD_KEY__ENDTIME]);
    
    if($is_empty__showhost) {
        $data['post_status'] = 'draft';
        add_filter('redirect_post_location', function($location) {
            $location = remove_query_arg('message', $location);
            $location = add_query_arg('message', 10, $location); // 10 is for "Post draft updated" message
            return add_query_arg(INVALID_CUSTOMFIELD_KEY, CUSTOM_FIELD_LABEL__SHOWHOST, $location);
        });
    } 
    elseif($is_empty__showcategory) {
        $data['post_status'] = 'draft';
        add_filter('redirect_post_location', function($location) {
            $location = remove_query_arg('message', $location);
            $location = add_query_arg('message', 10, $location); // 10 is for "Post draft updated" message
            return add_query_arg(INVALID_CUSTOMFIELD_KEY, CUSTOM_FIELD_LABEL__SHOWCATEGORY, $location);
        });
    } 
    elseif($is_empty__dayofweek) {
        $data['post_status'] = 'draft';
        add_filter('redirect_post_location', function($location) {
            $location = remove_query_arg('message', $location);
            $location = add_query_arg('message', 10, $location); // 10 is for "Post draft updated" message
            return add_query_arg(INVALID_CUSTOMFIELD_KEY, CUSTOM_FIELD_LABEL__DAYOFWEEK, $location);
        });
    }
    elseif($is_empty__starttime) {
        $data['post_status'] = 'draft';
        add_filter('redirect_post_location', function($location) {
            $location = remove_query_arg('message', $location);
            $location = add_query_arg('message', 10, $location); // 10 is for "Post draft updated" message
            return add_query_arg(INVALID_CUSTOMFIELD_KEY, CUSTOM_FIELD_LABEL__STARTTIME, $location);
        });
    }
    elseif($is_empty__endtime) {
        $data['post_status'] = 'draft';
        add_filter('redirect_post_location', function($location) {
            $location = remove_query_arg('message', $location);
            $location = add_query_arg('message', 10, $location); // 10 is for "Post draft updated" message
            return add_query_arg(INVALID_CUSTOMFIELD_KEY, CUSTOM_FIELD_LABEL__ENDTIME, $location);
        });
    }
    else {
        add_filter('redirect_post_location', function($location) {
            return remove_query_arg(INVALID_CUSTOMFIELD_KEY, $location);
        });
    }

    return $data;
};

add_action('admin_notices' , 'if_invalid_rwshow_post_meta');
function if_invalid_rwshow_post_meta() {
    if (isset($_GET[INVALID_CUSTOMFIELD_KEY])) {
        $message = "Your post was saved as a draft because the <b>" . $_GET[INVALID_CUSTOMFIELD_KEY] . " </b> is empty";
        echo sprintf('<div class="error"><p>%s</p></div>', $message);
    }
}


// the custom-fields are not submitted over $_POST when doing a 'Quick Edit'. So 'Quick Edits' will always result in 'drafts'
// disable 'Quick Edit' for this CPT becayse Custom Fields are not submitted over $_POST[] and hence wordpress thinks we are submitting an empty form
function remove_quick_edit( $actions, $post ) { 
    if ($post->post_type==CUSTOM_POST_TYPE__RWSHOW)
        unset($actions['inline hide-if-no-js']);
    
    return $actions;
}
add_filter('post_row_actions','remove_quick_edit',10,2);