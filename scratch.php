<?php
/**
 * This was some other old code where I tried building out my own MySQL table and 
 * I tried plugging in the post_meta,post_title,post_content values into that table 
 * the problems with this approach were
 *      - I would have to duplicate the post_content between the wp_posts AND the rw_schedule
 *      - If rw_schedule's SQL criteria rejected a value I couldn't rollback that change on wp_posts
 *      - 
 * */



// add_action('save_post_auto',"insert_cf_into_custom_table",10,3);
// function insert_cf_into_custom_table($post_id,$post,$update){
//     $post_status = $post->post_status;
//     /**
//      * $post_status can take on 4 values
//      *      auto-draft  : this is when we first create a new post.
//      *      publish     : this is when we publish the post
//      *      trash       : when we delete the post
//      * 
//      * $update is a boolean
//      */



//     // $post_content = get_post_field('post_content', $post_id);
//     // $post_title = get_post_field('post_title', $post_id);
//     $brand = $_POST['brand'];
//     $model = $_POST['model'];
//     $color = $_POST['color'];
//     $km = $_POST['km'];
//     $post->post_status = 'draft';
//     if(!isset($brand)||!isset($model) || !isset($color) || !isset($km)){
//         $post->post_status = 'draft';
//     }
//     if($post_status=='trash'){
//         delete_post_meta($post_id, 'brand');
//         delete_post_meta($post_id, 'model');
//         delete_post_meta($post_id, 'color');
//         delete_post_meta($post_id, 'km');
//     }
//     if($post_status=='publish' || $post_status=='draft' || $post_status=='auto-draft'){
//         if(isset($brand))
//             update_post_meta($post_id, 'brand',$brand);
//         if(isset($model))
//             update_post_meta($post_id, 'model',$model);
//         if(isset($color))
//             update_post_meta($post_id, 'color',$color);
//         if(isset($km))
//             update_post_meta($post_id, 'km',$km);
//     }

//     /**
//      * the custom table wp_garage gets written to only if we publish or delete. 
//      * this means that if I create a new post and exit without publishing (still in auto-draft) 
//      * then the wp_garage table will not update with the values of the custom forms
//     */

//     // if($post_status=='auto-draft')
//     //     $sql = "INSERT INTO wp_garage (brand,model,color,km) VALUES ('$post_status','$post_status','$post_status','$post_id')";
        
//     // if($post_status=='publish' && $update==0)
//     //     $sql = "INSERT INTO wp_garage (brand,model,color,km) VALUES ('$post_status','CREATE','CREATE','$post_id')";
        
//     // if($post_status == 'publish' && $update==1)
//     //     $sql = "INSERT INTO wp_garage (brand,model,color,km) VALUES ('$post_status','UPDATE','UPDATE','$post_id')";
//     //     // $sql = "UPDATE wp_garage SET brand='UPDATE', model='UPDATE', color='UPDATE', km='6969' WHERE id=$post_id";
    
//     // if( $post->post_status == 'trash' )
//     //     $sql = "DELETE FROM wp_garage WHERE km=$post_id";

//     /** if i delete a post, and then restore it, it goes into draft. 
//      * from there if i publish it, it is considered as $post_status='publish' and $update = 1. 
//      * even though deleting the post would have removed the post from wp_garage table
//      * So, I'm just not going to save to the rw_schedule table if $post_status = 'auto-draft'
//      * rw_schedule table 
//      *      id                  PRIMARY KEY AUTO_INCREMENT INTEGER
//      *      _post_id            INTEGER UNIQUE NOT NULL
//      *      show_title          VARCHAR(255) UNIQUE  NOT NULL
//      *      show_description    VARCHAR(65535) NOT NULL
//      *      show_host_id        BIGINT UNSIGNED NOT NULL 
//      *      co_host_id_1        BIGINT UNSIGNED 
//      *      co_host_id_2        BIGINT UNSIGNED NOT NULL
//      *      day_of_week         INT UNSIGNED NOT NULL
//      *      start_time          TIME NOT NULL
//      *      end_time            TIME NOT NULL
//      *      show_category       VARCHAR(255) NOT NULL
//      * 
//      *      SELECT * FROM rw_schedule WHERE _post_id=$post_id;
//      *      SELECT * FROM rw_schedule WHERE (show_title=$post_title);
//      *      SELECT * FROM wp_posts WHERE (post_title=$post_title AND post_type='rw-show')
//      * if either of those queries returns anything then it means the rw-show exists.
//      *      if the post exists then do an update. 
//      *      if the post doesn't exist then create.
//      *      you should check not just with $post_id but also with $post_title and $post_type
//      * */

//     // global $wpdb;
//     // $wpdb->get_results( $sql );
// }

// function techiepress_check_for_similar_meta_ids() {
//     $id_arrays_in_cpt = array();

//     $args = array(
//         'post_type'      => 'auto',
//         'posts_per_page' => -1,
//     );

//     $loop = new WP_Query($args);
//     while( $loop->have_posts() ) {
//         $loop->the_post();
//         $id_arrays_in_cpt[] = get_post_meta( get_the_ID(), 'id', true );
//     }

//     return $id_arrays_in_cpt;
// }

// function techiepress_query_garage_table( $car_available_in_cpt_array ) {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'garage';

//     if ( NULL === $car_available_in_cpt_array || 0 === $car_available_in_cpt_array || '0' === $car_available_in_cpt_array || empty( $car_available_in_cpt_array ) ) {
//         $results = $wpdb->get_results("SELECT * FROM $table_name");
//         return $results;
//     } else {
//         $ids = implode( ",", $car_available_in_cpt_array );
//         $sql = "SELECT * FROM $table_name WHERE id NOT IN ( $ids )";
//         $results = $wpdb->get_results( $sql );
//         return $results;
//     }
// }

// function techiepress_insert_into_auto_cpt() {

//     $car_available_in_cpt_array = techiepress_check_for_similar_meta_ids();
//     $database_results = techiepress_query_garage_table( $car_available_in_cpt_array );

//     if ( NULL === $database_results || 0 === $database_results || '0' === $database_results || empty( $database_results ) ) {
//         return;
//     }

//     foreach ( $database_results as $result ) {
//         $car_model = array(
//             'post_title' => wp_strip_all_tags( $result->Brand . ' ' . $result->Model . ' ' . $result->Km ),
//             'meta_input' => array(
//                 'id'        => $result->id,
//                 'brand'        => $result->Brand,
//                 'model'        => $result->Model,
//                 'color'        => $result->Color,
//                 'km'           => $result->Km,
//             ),
//             'post_type'   => 'auto',
//             'post_status' => 'publish',
//         );
//         // wp_insert_post( $car_model );
//     }
// }