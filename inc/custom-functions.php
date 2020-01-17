<?php
/**
 * Custom functionality for this theme.
 *
 * @package _s
 */

/* ==========================================================================
   Utilities
   ========================================================================== */

/**
 * Set offset for custom pagination
 */
function _s_get_offset( $limit = '' ) {

    // value set for posts per page
    $number = ( $limit ) ? $limit : get_query_var( 'posts_per_page' );

    // determine what page we're on
    $page   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

    // get offset so we can show what thoughts to display
    return 1 == $page ? ( $page - 1 ) : ( ( $page - 1 ) * $number );

}





/**
 * Sanitize Value
 *
 * Primarily used for output from Advanced Custom Field Plugin
 *
 * wp_kses_allowed_html( 'post' ) => This will return a list of allowed HTML
 * tags for a given content. In this case, posts. Anything not in the list
 * will be removed.
 *
 * Reference: https://codex.wordpress.org/Function_Reference/wp_kses_allowed_html
 *            https://developer.wordpress.org/reference/functions/wp_kses_allowed_html/
 *
 * @param  string $value
 * @return string
 */
function _s_sanitize( $value ) {

    return wp_kses( $value, wp_kses_allowed_html( 'post' ) );

}





/**
 * Get Image Attachment Metadata
 *
 * Primarily used to retrieve details for outputting alt text. However,
 * additional metadata can be added to array as needed. Reference link below.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_prepare_attachment_for_js/#source-code
 *
 * @param  integer    $attachment_id    the attachment id
 * @return mixed      array/boolean
 */
function _s_get_attachment_meta( $attachment_id = 0 ) {

    if ( ! $attachment = get_post( $attachment_id ) )
        return false;

    if ( 'attachment' != $attachment->post_type )
        return false;

    return array(
        'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
        'caption'     => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'title'       => $attachment->post_title
    );

}





/**
 * Get Attachment Alt Text
 *
 * Run through the following attachment details and attempt to gather alt text.
 *
 * - alt
 * - caption
 * - description
 * - title (usually filename w/out extension if nothing manually entered)
 *
 * @param  integer    $attachment_id    the attachment id
 * @return string
 */
function _s_get_attachment_alt( $attachment_id = 0 ) {

    $r = '';

    if ( $meta = _s_get_attachment_meta( $attachment_id ) ) :

        if ( $meta['alt'] ) :

            $r = $meta['alt'];

        elseif ( $meta['caption'] ) :

            $r = $meta['caption'];

        elseif ( $meta['description'] ) :

            $r = $meta['description'];

        elseif ( $meta['title'] ) :

            $r = $meta['title'];

        endif;

    endif;

    return $r;

}





/**
 * Get image path
 *
 * Get image src with option to fallback
 *
 * @param  array  $args options passed to function
 * @return mixed
 *
 *         array        [0] => path
 *                      [1] => width
 *                      [2] => height
 *                      [3] => boolean: true if path is a resized image, false if it is the original or if no image is available.
 *                      [4] => alt text _s_get_attachment_alt()
 *                      [5] => srcset
 *                      [6] => sizes
 *
 *         string       path
 */
function _s_get_image( $args = array() ) {

    // image path
    $r = array();

    $defaults = array (
        'image_id' => '',
        'size'     => '640x',
        'field'    => '',
        'field_id' => '',
        'fallback' => false,
        'echo'     => false,
        'path'     => false,
        'sizes'    => '' // sizes attribute
    );

    // Parse incoming $args into an array and merge it with $defaults
    $args = wp_parse_args( $args, $defaults );

    // OPTIONAL: Declare each item in $args as its own variable i.e. $type, $before.
    extract( $args, EXTR_SKIP );

    // image id from ACF field (regular or repeater)
    if ( ! $image_id && $field ) :

        $image_id = ( get_sub_field( $field, $field_id ) ) ? get_sub_field( $field, $field_id ) : get_field( $field, $field_id );

    endif;

    // image data
    $image_data = wp_get_attachment_image_src( $image_id, $size );

    // srcset attribute
    $image_srcset = wp_get_attachment_image_srcset( $image_id, $size );

    // get image path or decide to use fallback
    if ( ! $image_data && $fallback ) :

        // fallback image path
        $r[0] = constant( 'FB_' . $size );

        // sizes attribute
        $sizes = ( $sizes ) ? $sizes : '(max-width: 640px) 100vw, 640px'; // default to content width since we don't know fallback image width

        // image dimensions (identical return array as wp_get_attachment_image_src)
        $parts = explode( 'x', $size );
        $r[1]  = $parts[0];
        $r[2]  = $parts[1];
        $r[3]  = false; // was image resized? Consistent with return values for wp_get_attachment_image_src()
        $r[4]  = ''; // alt text
        $r[5]  = $image_srcset; // srcset attribute
        $r[6]  = $sizes; // sizes attribute

    // get image data and alt text
    elseif ( $image_data ) :

        $r   = $image_data; // image data
        $r[] = _s_get_attachment_alt( $image_id ); // image alt text
        $r[] = $image_srcset; // srcset attribute
        $r[] = ( $sizes ) ? $sizes : '(max-width: ' . $r[1] . 'px) 100vw, ' . $r[1] . 'px'; // default to content width since we don't know fallback image width; // sizes attribute

    endif;

    // return path only
    if ( $path && ! $echo ) :
        $r = $r[0];
    endif;

    if ( $echo ) :
        echo $r[0]; // return just image path
    else :
        return $r;
    endif;

}





/**
 * Simple Url
 *
 * Remove 'http(s)', '//:', '/', 'www' from url
 *
 * @param  string $url
 * @return string
 */
function _s_simple_url( $url ) {

    $url = trim( $url, '/' );

    // If scheme not included, prepend it
    if ( !preg_match( '#^http(s)?://#', $url ) ) {

        $url = 'http://' . $url;
    }

    $parts = parse_url( $url );

    // remove www
    $url = preg_replace('/^www\./', '', $parts['host']);

    return $url;

}





/**
 * Get post's content
 *
 * Must be used within loop
 * @param  $id      int/string    Specific post ID where your value was entered. Defaults to current post ID (not required). This can also be options / taxonomies / users / etc
 * @param  $format  boolean       whether or not to format the value loaded from the db. Defaults to true (not required).
 * @param  $echo    boolean       echo or return result
 * @return boolean/string
 */
function _s_the_content( $id = '', $format = true, $echo = true ) {

    $r = ( get_field( 'content', $id ) ) ? get_field( 'content', $id, (bool)$format ) : get_the_content();

    if( $echo ) :
        echo $r;
    else :
        return $r;
    endif;

}





/* ==========================================================================
   Excerpt / Content
   ========================================================================== */

// Append read more link to excerpt
function _s_excerpt_more( $more ) {

    global $post;
    return '... <a class="more-link" href="' . get_permalink( $post->ID ) . '">Read More &raquo;</a>';
}
add_filter('excerpt_more', '_s_excerpt_more');





if ( ! function_exists( '_s_excerpt' ) ) :
/**
 * Limit the Excerpt by 'x' amount of words
 *
 * @param int $limit, str $copy
 * @return str $content
 */
function _s_excerpt( $limit, $copy = NULL ) {

    global $post;

    if( $copy ) {
        $excerpt = explode( ' ', $copy, $limit );
    } else {
        $excerpt = explode( ' ', get_the_excerpt(), $limit );
    }

    if ( count( $excerpt ) >= $limit ) {
        array_pop( $excerpt );
        $excerpt = implode( " ",$excerpt ).'...';
    } else {
        $excerpt = implode( " ",$excerpt );
    }
    $excerpt = preg_replace( '`[[^]]*]`','',$excerpt );

    return $excerpt;
}
endif;





if ( ! function_exists( '_s_content' ) ) :
/**
 * Limit the Content by 'x' amount of words
 *
 * @param int $limit, str $copy
 * @return str $content
 */
function _s_content( $limit, $copy = NULL ) {

    if( $copy ) {
        $content = explode( ' ', $copy, $limit );
    } else {
        $content = explode( ' ', get_the_content(), $limit );
    }

    if ( count( $content ) >= $limit ) {
        array_pop( $content );
        $content = implode( " ",$content ).'...';
    } else {
        $content = implode( " ",$content );
    }
    $content = preg_replace( '/[+]/','', $content );
    $content = apply_filters( 'the_content', $content );
    $content = str_replace( ']]>', ']]>', $content );

    return $content;
}
endif;
