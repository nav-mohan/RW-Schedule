<?php

add_action('add_meta_boxes' , 'add_custom_field__starttime');
function add_custom_field__starttime() {
    add_meta_box(
        CUSTOM_FIELD_KEY__STARTTIME, 
        "Start/End Times", 
        'render_custom_field__starttime',
        CUSTOM_POST_TYPE__RWSHOW, 
        'side', 
        'core'
    );
}

function render_custom_field__starttime(WP_Post $post) {
    global $ALL_TIMES_OF_DAY;
    $the_starttime = get_post_meta($post->ID, CUSTOM_FIELD_KEY__STARTTIME, true);
    $the_endtime = get_post_meta($post->ID, CUSTOM_FIELD_KEY__ENDTIME, true);

    wp_nonce_field(CUSTOMFORM_NONCE_ACTION, CUSTOMFORM_NONCE_NAME);
    
    // start-time
    $attributes = [
        'id' => CUSTOM_FIELD_KEY__STARTTIME,
        'name' => CUSTOM_FIELD_KEY__STARTTIME,
        'value' => $the_starttime,
        'class' => 'widefat',
        'type' => 'select',
        'required' => 'required'
    ];

    $attributes = implode(' ', array_map(function($key) use($attributes) {
        return sprintf('%s="%s"', $key, esc_attr($attributes[$key]));
    }, array_keys($attributes)));

    echo '<select ' . $attributes . ' >';
    
    echo "<option value = '' disabled hidden>Start Time</option>";

    foreach($ALL_TIMES_OF_DAY as $start_time_24=>$start_time_12){
        if($start_time_24==$the_starttime)
            echo "<option value = '$start_time_24' selected>$start_time_12</option>";
        else
            echo "<option value = '$start_time_24'>$start_time_12</option>";
    }
    echo '</select>';
    



    $attributes = [
        'id' => CUSTOM_FIELD_KEY__ENDTIME,
        'name' => CUSTOM_FIELD_KEY__ENDTIME,
        'value' => $the_endtime,
        'class' => 'widefat',
        'type' => 'select',
        'required' => 'required'
    ];

    $attributes = implode(' ', array_map(function($key) use($attributes) {
        return sprintf('%s="%s"', $key, esc_attr($attributes[$key]));
    }, array_keys($attributes)));

    echo '<select ' . $attributes . ' >';
    
    echo "<option selected value = '' disabled hidden>End Time</option>";

    foreach($ALL_TIMES_OF_DAY as $end_time_24=>$end_time_12){
        if($end_time_24==$the_endtime)
            echo "<option value = '$end_time_24' selected>$end_time_12</option>";
        else
            echo "<option value = '$end_time_24'>$end_time_12</option>";
    }
    echo '</select>';


    // print_r($ALL_TIMES_OF_DAY);
}

