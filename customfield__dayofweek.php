<?php

add_action('add_meta_boxes' , 'add_custom_field__dayofweek');
function add_custom_field__dayofweek() {
    add_meta_box(
        CUSTOM_FIELD_KEY__DAYOFWEEK, 
        CUSTOM_FIELD_LABEL__DAYOFWEEK, 
        'render_custom_field__dayofweek',
        CUSTOM_POST_TYPE__RWSHOW, 
        'side', 
        'core'
    );
}

function render_custom_field__dayofweek(WP_Post $post) {
    global $ALL_DAYS_OF_WEEK;
    $the_dayofweek = get_post_meta($post->ID, CUSTOM_FIELD_KEY__DAYOFWEEK, true);

    wp_nonce_field(CUSTOMFORM_NONCE_ACTION, CUSTOMFORM_NONCE_NAME);
    
    // show-category
    $attributes = [
        'id' => CUSTOM_FIELD_KEY__DAYOFWEEK,
        'name' => CUSTOM_FIELD_KEY__DAYOFWEEK,
        'value' => $the_dayofweek,
        'class' => 'widefat',
        'type' => 'select',
        'required' => 'required'
    ];

    $attributes = implode(' ', array_map(function($key) use($attributes) {
        return sprintf('%s="%s"', $key, esc_attr($attributes[$key]));
    }, array_keys($attributes)));

    echo '<select ' . $attributes . ' >';
    
    echo "<option value = '' disabled hidden>Day of week</option>";

    for ($i=1; $i <= 7; $i++) { 
        $dow = $ALL_DAYS_OF_WEEK[$i];
        if($dow==$the_dayofweek)
            echo "<option value = '$dow' selected>$dow</option>";
        else
            echo "<option value = '$dow'>$dow</option>";
    }
    echo '</select>';
    
}

