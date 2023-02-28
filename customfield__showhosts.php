<?php

add_action('add_meta_boxes' , 'add_custom_field__showhost');
function add_custom_field__showhost() {
    add_meta_box(
        CUSTOM_FIELD_KEY__SHOWHOST, 
        CUSTOM_FIELD_LABEL__SHOWHOST, 
        'render_custom_field__showhost',
        CUSTOM_POST_TYPE__RWSHOW, 
        'side', 
        'core'
    );
}

function render_custom_field__showhost(WP_Post $post) {
    global $ALL_SHOW_HOSTS;
    $the_showhost = get_post_meta($post->ID, CUSTOM_FIELD_KEY__SHOWHOST, true);
    $the_cohost1 = get_post_meta($post->ID, CUSTOM_FIELD_KEY__COHOST1, true);
    $the_cohost2 = get_post_meta($post->ID, CUSTOM_FIELD_KEY__COHOST2, true);

    wp_nonce_field(CUSTOMFORM_NONCE_ACTION, CUSTOMFORM_NONCE_NAME);
    
    
    // show-host
    $attributes = [
        'id' => CUSTOM_FIELD_KEY__SHOWHOST,
        'name' => CUSTOM_FIELD_KEY__SHOWHOST,
        'value' => $the_showhost,
        'class' => 'widefat',
        'type' => 'select',
        //'type' => 'url',
        'required' => 'required'
    ];

    $attributes = implode(' ', array_map(function($key) use($attributes) {
        return sprintf('%s="%s"', $key, esc_attr($attributes[$key]));
    }, array_keys($attributes)));

    echo '<select ' . $attributes . ' >';
    
    echo "<option value = '' disabled hidden>Show Hosts</option>";

    for ($i=0; $i < count($ALL_SHOW_HOSTS); $i++) { 
        $user_id = $ALL_SHOW_HOSTS[$i]['user_id'];
        $user_login = $ALL_SHOW_HOSTS[$i]['user_login'];
        if($user_id==$the_showhost)
            echo "<option value = '$user_id' selected>$user_login</option>";
        else
            echo "<option value = '$user_id'>$user_login</option>";
    }
    echo '</select>';
    


    // co-host-1
    echo '<br><br>';

    $attributes = [
        'id' => CUSTOM_FIELD_KEY__COHOST1,
        'name' => CUSTOM_FIELD_KEY__COHOST1,
        'value' => $the_cohost1,
        'class' => 'widefat',
        'type' => 'select',
    ];

    $attributes = implode(' ', array_map(function($key) use($attributes) {
        return sprintf('%s="%s"', $key, esc_attr($attributes[$key]));
    }, array_keys($attributes)));

    echo '<select ' . $attributes . ' >';
    if($the_cohost1=='')
        echo "<option value = '' disabled selected hidden>Co-Host 1</option>";

    for ($i=0; $i < count($ALL_SHOW_HOSTS); $i++) { 
        $user_id = $ALL_SHOW_HOSTS[$i]['user_id'];
        $user_login = $ALL_SHOW_HOSTS[$i]['user_login'];
        if($user_id==$the_cohost1)
            echo "<option value = '$user_id' selected>$user_login</option>";
        else
            echo "<option value = '$user_id'>$user_login</option>";
    }
    echo '</select>';



    // co-host-2
    echo '<br><br>';

    $attributes = [
        'id' => CUSTOM_FIELD_KEY__COHOST2,
        'name' => CUSTOM_FIELD_KEY__COHOST2,
        'value' => $the_cohost2,
        'class' => 'widefat',
        'type' => 'select',
    ];

    $attributes = implode(' ', array_map(function($key) use($attributes) {
        return sprintf('%s="%s"', $key, esc_attr($attributes[$key]));
    }, array_keys($attributes)));

    echo '<select ' . $attributes . ' >';
    if($the_cohost2=='')
        echo "<option value = '' disabled selected hidden>Co-Host 2</option>";

    for ($i=0; $i < count($ALL_SHOW_HOSTS); $i++) { 
        $user_id = $ALL_SHOW_HOSTS[$i]['user_id'];
        $user_login = $ALL_SHOW_HOSTS[$i]['user_login'];
        if($user_id==$the_cohost2)
            echo "<option value = '$user_id' selected>$user_login</option>";
        else
            echo "<option value = '$user_id'>$user_login</option>";
    }
    echo '</select>';

}

