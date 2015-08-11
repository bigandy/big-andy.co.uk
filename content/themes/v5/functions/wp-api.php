<?php

function ah_use_raw_post_content( $data, $post, $request ) {
    $data->data['content']['plaintext'] = $post->post_content;
    return $data;
}
add_filter( 'rest_prepare_post', 'ah_use_raw_post_content', 10, 3 );
