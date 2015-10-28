<?php
/**
 * Template Name: Pattern Library
 */
get_header(); ?>

<div class="pl-hero">
    <div class="l-constrain">
        <div class="pl-hero__lining row">
            <div class="pl-hero__column medium-9 medium-push-3 columns">
                <h1 class="pl-hero__heading">Pattern Library</h1>
                <p class="pl-hero__subheading">A Clean &amp; Corporate Style Guide</p>
            </div>
            <div class="pl-hero__column medium-3 medium-pull-9 columns">
                <span class="pl-version">V1.1</span>
            </div>
        </div>
    </div>
</div>

<?php
$path   = get_template_directory() . '/pattern-library/';
$files  = array();
$handle = @opendir( $path ) or die( "Can't open the directory. Make sure you're using the correct path. <br /> Path: $path" );

while ( false !== ( $file = readdir( $handle ) ) ) :

    if ( substr( $file, -5 ) == '.html' ) :

        $files[] = $file;

    endif;

endwhile;

// sort by: 01-color.html, 02-typefaces.html, etc
natsort( $files );

foreach ( $files as $file ) :

    $order = '00';
    $name  = 'Component';

    // remove file extension
    $filename = strstr( $file, '.', true );

    // break apart by '-'
    $parts = explode( '-', $filename );

    // create component heading
    if ( $parts && count( $parts ) > 1 ) :

        $order = $parts[0];

        // remove order
        array_shift( $parts );

        $name = implode( ' ', $parts );

    endif;

    echo '<div class="l-section-lg">' . "\n";
    echo '    <div class="l-constrain">' . "\n";
    echo '        <h2 class="pl-heading"><span class="pl-heading__order">' . $order . '</span> ' . $name . '</h2>' . "\n";

    include_once( $path . $file );

    echo '        <div class="pl-source">' . "\n";
    echo '            <div class="pl-source__driver">' . "\n";
    echo '                <a class="pl-source__toggle js-pl-source-toggle" href="#pl-' . $filename .'" role="button">&lt;&gt;</a>' . "\n";
    echo '                <span class="pl-pipe">|</span>' . "\n";
    echo '                <a href="' . get_template_directory_uri() . '/pattern-library/' . $file . '" target="_blank">View File</a>' . "\n";
    echo '            </div>' . "\n";
    echo '            <pre id="pl-' . $filename . '" class="pl-source__code"><code class="language-html">' . "\n";
    echo htmlspecialchars( file_get_contents( $path . $file ), FILE_USE_INCLUDE_PATH );
    echo '            </code></pre>' . "\n";
    echo '        </div>' . "\n";
    echo '    </div>' . "\n";
    echo '</div>' . "\n";

endforeach; ?>

<?php get_footer(); ?>
