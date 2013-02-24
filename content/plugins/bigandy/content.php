<?php

function ah_myContentFunct( $content ) {

    if ( is_home() && has_post_format( 'aside' ) ) {
        $content = strip_tags( $content );
        $content .= '<a href="'. get_permalink() .'">&infin;</a>';

        return $content;
    }


    return $content;
}

add_filter( 'the_content', 'ah_myContentFunct' );