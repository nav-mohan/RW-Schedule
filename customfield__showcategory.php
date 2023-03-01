<?php

add_action('add_meta_boxes' , 'add_custom_field__showcategory');
function add_custom_field__showcategory() {
    add_meta_box(
        CUSTOM_FIELD_KEY__SHOWCATEGORY, 
        CUSTOM_FIELD_LABEL__SHOWCATEGORY, 
        'render_custom_field__showcategory',
        CUSTOM_POST_TYPE__RWSHOW, 
        'side', 
        'core'
    );
}

function render_custom_field__showcategory(WP_Post $post) {
    global $ALL_SHOW_CATEGORIES;
    $the_showcategory = get_post_meta($post->ID, CUSTOM_FIELD_KEY__SHOWCATEGORY, true);

    wp_nonce_field(CUSTOMFORM_NONCE_ACTION, CUSTOMFORM_NONCE_NAME);
    
    // show-category
    $attributes = [
        'id' => CUSTOM_FIELD_KEY__SHOWCATEGORY,
        'name' => CUSTOM_FIELD_KEY__SHOWCATEGORY,
        'value' => $the_showcategory,
        'class' => 'widefat',
        'type' => 'select',
        'required' => 'required'
    ];

    $attributes = implode(' ', array_map(function($key) use($attributes) {
        return sprintf('%s="%s"', $key, esc_attr($attributes[$key]));
    }, array_keys($attributes)));

    echo '<select ' . $attributes . ' >';
    
    echo "<option value = '' disabled hidden>Show Category</option>";

    for ($i=1; $i <= count($ALL_SHOW_CATEGORIES); $i++) { 
        $showcategory_name = $ALL_SHOW_CATEGORIES[$i];
        if($showcategory_name==$the_showcategory)
            echo "<option value = '$showcategory_name' selected>$showcategory_name</option>";
        else
            echo "<option value = '$showcategory_name'>$showcategory_name</option>";
    }
    echo '</select>';
    
}

