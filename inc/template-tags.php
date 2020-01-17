<?php

/* ==========================================================================
   Comments
   ========================================================================== */

if ( ! function_exists( '_s_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function _s_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;

    if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

    <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
        <div class="comment-body">
            <?php _e( 'Pingback:', '_s' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', '_s' ), '<span class="edit-link">', '</span>' ); ?>
        </div>

    <?php else : ?>

    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php if ( 0 != $args['avatar_size'] ) { echo get_avatar( $comment, $args['avatar_size'] ); } ?>
                    <?php printf( __( '%s <span class="says">says:</span>', '_s' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
                </div><!-- .comment-author -->

                <div class="comment-metadata">
                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', '_s' ), get_comment_date(), get_comment_time() ); ?>
                        </time>
                    </a>
                    <?php edit_comment_link( __( 'Edit', '_s' ), '<span class="edit-link">', '</span>' ); ?>
                </div><!-- .comment-metadata -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', '_s' ); ?></p>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
                comment_reply_link( array_merge( $args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="reply">',
                    'after'     => '</div>',
                ) ) );
            ?>
        </article><!-- .comment-body -->

    <?php
    endif;
}
endif; // ends check for _s_comment()





/* ==========================================================================
   Pagination
   ========================================================================== */

if ( ! function_exists( '_s_pagination' ) ) :
/**
* Display custom pagination
*
* @param str $echo
* @return str $r
*/
function _s_pagination( $query = false, $echo = true ) {

    global $wp_query;

    $total_pages  = ( $query ) ? $query->max_num_pages : $wp_query->max_num_pages;
    $current_page = max( 1, get_query_var('paged') );

    $big = 999999999; // need an unlikely integer

    $pages = paginate_links( array(
        'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format'             => '?paged=%#%',
        'total'              => $total_pages,
        'current'            => $current_page,
        'prev_next'          => true,
        'prev_text'          => '<i class="icon-keyboard-arrow-left"></i>',
        'next_text'          => '<i class="icon-keyboard-arrow-right"></i>',
        'type'               => 'array',
        'show_all'           => false,
        'end_size'           => 1,
        'mid_size'           => 2,
        'add_args'           => false,
        'add_fragment'       => '',
        'before_page_number' => '',
        'after_page_number'  => ''
    ) );

    // only show pagination if needed
    if( !$pages ) // returns null if only 1 page of results
        return;

    $r = '<div class="pagination-wrap pagination-centered">' . "\n";
    $r .= '    <ul class="pagination">' . "\n";

    foreach( $pages as $page ) {
        $r .= '        <li class="pagination__item">' . $page . '</li>' . "\n";
    }

    $r .= '    </ul>' . "\n";

    $page_count = _s_result_count_output( array(
        'query' => $query,
        'echo'  => false
    ) );

    $r .= '    ' . $page_count . "\n";
    $r .= '</div>' . "\n";

    if ( $echo ) {
        echo $r;
    } else {
        return $r;
    }
}
endif;





if ( ! function_exists( '_s_result_count' ) ) :
/**
 * Result Count Meta
 * @param  boolean $echo echo or return result
 * @return string        output
 */
function _s_result_count( $query = false, $echo = true ) {

    $r = '';

    global $wp_query;

    $total_pages  = ( $query ) ? $query->max_num_pages : $wp_query->max_num_pages;
    $total_items  = ( $query ) ? $query->found_posts : $wp_query->found_posts;
    $current_page = max( 1, get_query_var('paged') );
    $per_page     = ( $query ) ? $query->query_vars['posts_per_page'] : $wp_query->get( 'posts_per_page' );
    $page_start   = ( $per_page * $current_page ) - $per_page + 1;
    $page_end     = min( $total_items, $per_page * $current_page );

    if ( 1 == $total_items ) :

        $r .= __( 'Showing: 1 Result', 'elevator' );

    elseif ( $total_items <= $per_page || -1 == $per_page ) :

        $r .= sprintf( __( 'Showing: %d Results', 'elevator' ), $total_items );

    else :

        $r .= 'Showing: ' . sprintf( _x( '%1$d&ndash;%2$d of %3$d', '%1$d = page_start, %2$d = page_end, %3$d = total_items', 'elevator' ), $page_start, $page_end, $total_items ) . "\n";

    endif;

    if ( $echo ) {
        echo $r;
    } else {
        return $r;
    }

}
endif;





if ( ! function_exists( '_s_result_count_output' ) ) :
/**
 * Result Count Meta Output
 * @param array $args options
 * @return string           html output
 */
function _s_result_count_output( $args = array() ) {

    $r = '';

    $defaults = array (
        'container'       => 'div',
        'container_class' => '',
        'query'           => false,
        'echo'            => true
    );

    // Parse incoming $args into an array and merge it with $defaults
    $args = wp_parse_args( $args, $defaults );

    // OPTIONAL: Declare each item in $args as its own variable i.e. $type, $before.
    extract( $args, EXTR_SKIP );

    $result = _s_result_count( $query, false );

    if ( $result ) :

        $classes = ( $container_class ) ? ' ' . $container_class : '';

        $r = '<' . $container . ' class="page-count' . $classes . '">' . $result . '</' . $container . '>';

    endif;

    if ( $echo ) {
        echo $r;
    } else {
        return $r;
    }

}
endif;





/* ==========================================================================
   Social Icons
   ========================================================================== */

if ( ! function_exists( '_s_social_icons' ) ) :
/**
 * Output social icons
 *
 * @param boolean $echo echo or return result
 * @return str $r
 */
function _s_social_icons( $args = array() ) {

    $defaults = array (
        'container_class' => '',
        'field_id'        => false,
        'echo'            => true
    );

    // Parse incoming $args into an array and merge it with $defaults
    $args = wp_parse_args( $args, $defaults );

    // OPTIONAL: Declare each item in $args as its own variable i.e. $type, $before.
    extract( $args, EXTR_SKIP );

    $r = false;

    if( $profiles = get_field( 'social_profiles', $field_id ) ) :

        $c = 1;
        $total = count( $profiles );

        $container_class = ( $container_class ) ? ' ' . $container_class : '';

        $r .= '<ul class="social-profiles' . $container_class . '">' . "\n";

        while( has_sub_field( 'social_profiles', $field_id ) ) :

            // Account for email addresses
            $link = get_sub_field( 'link' );
            $link = ( get_sub_field( 'profile' ) === 'email' && ! stristr( $link, 'mailto:' ) ) ? 'mailto:' . $link : $link;

            $open_comment = ( $c < $total ) ? '<!--': '';
            $close_comment = ( $c !== 1 ) ? '-->': '';

            $r .= $close_comment . '<li class="social-profiles__item"><a class="social-profiles__link icon-' . get_sub_field( 'profile' ) . '" href="' . esc_attr( $link ) . '" target="_blank"><span class="visuallyhidden">' . get_sub_field( 'profile' ) . '</span></a></li>' . $open_comment . "\n";

            $c++;

        endwhile;

        $r .= '</ul>' . "\n";

    endif;

    if ( $echo ) :
        echo $r;
    else :
        return $r;
    endif;
}
endif;





/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package _s
 */

if ( ! function_exists( '_s_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function _s_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', '_s' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( '_s_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function _s_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', '_s' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( '_s_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function _s_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', '_s' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', '_s' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', '_s' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', '_s' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', '_s' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', '_s' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( '_s_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function _s_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;
