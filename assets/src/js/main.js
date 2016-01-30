;(function ($, window, document, undefined) {
    'use strict';

    $(document).ready( function() {

        var EV = {
            init : function() {
                this.utils.init();
                this.hero();
                this.tabs();
                this.accordions.init();
                this.alerts();
                this.bins.init();
                this.patternLibrary();
            },
            vals : {
                $window : $(window) // http://jsperf.com/jquery-window-cache
            },
            utils : {

                init : function() {

                    this.mqState.appendEl();
                    this.mqState.checkStateView();
                    this.setDelay();
                    this.scrollIt();

                },
                mqState : {

                    appendEl : function() {

                        $('body').append('<div class="mq-state"/>');

                    },
                    checkStateView : function() {

                        EV.vals.view  = parseInt( $('.mq-state').css('z-index') );

                        // fallback to desktop if browser doesn't support media queries
                        if ( ! Modernizr.mq( 'only all' ) ) EV.vals.view = 30;

                    },

                },

                setDelay : function() {

                    var scrolled = false,
                        resized  = false;

                    // delay checking of scroll
                    EV.vals.$window.on( 'scroll touchmove', function() { scrolled = true; });
                    // delay checking of window resize
                    EV.vals.$window.on( 'resize', function() { resized = true; });

                    setInterval( function() {

                        if ( scrolled ) {

                            scrolled = false;

                        }

                        if ( resized ) {

                            resized = false;

                            EV.utils.mqState.checkStateView();
                        }

                    }, 50);
                },

                scrollIt : function() {

                    // Animate the scroll to top
                    $('.js-to-top').on( 'click', function( e ) {

                        e.preventDefault();

                        $('html, body').animate( { scrollTop: 0 }, 300 );

                    });

                    // Animate scroll to id
                    $('.js-scroll-to').on( 'click', function( e ) {

                        e.preventDefault();

                        var href        = $(this).attr('href'),
                            scrollPoint = $(href).offset();

                        $('html, body').animate( { scrollTop: scrollPoint.top }, 300 );
                    });
                }
            },

            hero : function() {

                var $slider = $('.js-hero'),
                    $slides = $slider.find('.hero__lining');

                $slider
                    .on( 'init', function( slick ) {
                        $slides.eq(0).find('.hero__content').addClass('is-transparent');
                    })
                    .slick({
                        dots: true,
                        arrows: false,
                        adaptiveHeight: true
                    })
                    .on( 'beforeChange', function( e, slick, currSlide, nextSlide ) {

                        // Only transition when slide advances
                        if ( currSlide != nextSlide ) {
                            $slides.eq( currSlide ).find('.hero__content').removeClass('is-transparent');
                        }
                    })
                    .on( 'afterChange', function( e, slick, currSlide, nextSlide ) {
                        $slides.eq( currSlide ).find('.hero__content').addClass('is-transparent');
                    });

            },

            tabs : function() {

                function setTab( $el ) {

                    if ( ! $el.hasClass('is-active' ) ) {

                        var href        = $el.find('.tabs__link').attr('href'),
                            $tabs       = $el.siblings(),
                            $panelsWrap = $el.closest('.js-tabs').next('.tab-panels'),
                            $panels     = $panelsWrap.find('.tab-panel'),
                            $panel      = $panelsWrap.find(href);

                        // set active tab class
                        $el.addClass('is-active');
                        $panel.addClass('is-active');

                        // remove active class on other items
                        $tabs.removeClass('is-active');
                        $panels.not( $panel ).removeClass('is-active');
                    }
                }

                // Set active tabs
                $('.js-tabs')
                    // set active on page load
                    .each( function( index ) {

                        var $this       = $(this),
                            href        = $this.find('.tabs__link').attr('href'),
                            $panelsWrap = $this.next('.tab-panels'),
                            $panel      = $panelsWrap.find(href);

                        // add active class
                        $this.find('.tabs__item').eq(0).addClass('is-active');
                        $panel.eq(0).addClass('is-active');

                    })
                    // set active tab on tab click
                    .on( 'click', '.tabs__link', function( e ) {

                        e.preventDefault();

                        // set active tab
                        setTab( $(this).closest('.tabs__item') );
                    });

            },

            accordions : {

                init : function() {
                    this.bind();
                },

                bind : function() {
                    $('.js-accordion').on( 'click', 'a', this.action.bind(this) );
                },

                action : function( e ) {

                    var $this    = $(e.currentTarget),
                        $item    = $this.closest('.accordion__item'),
                        $items   = $item.siblings(),
                        $content = $item.find('.accordion__content');

                        if ( $item.hasClass('is-active') ) {

                            // remove active
                            $item.removeClass('is-active');
                            $content.stop(true, true).slideUp();

                        } else {

                            // toggle inactive
                            $items.removeClass('is-active');
                            $items.find('.accordion__content').slideUp();

                            // toggle active
                            $item.addClass('is-active');
                            $content.stop(true, true).slideDown();
                        }

                    e.preventDefault();
                }

            },

            alerts : function() {

                $('.js-close').on( 'click', function( e ) {

                    $(this).closest('.alert').slideUp();

                    e.preventDefault();
                });

            },

            bins : {

                init : function() {
                    this.height();
                },

                height : function() {

                    /**
                     * Set bins to same height
                     */
                    var $container = $('.js-bins'),
                        setHeights = function() {

                            // iterate over each set of bins
                            $container.each( function() {

                                var $this    = $(this),
                                    $bins   = $this.find('.bin'),
                                    viewport = $this.data('bin-viewport') || 0;

                                // reset card heights
                                $bins.find('.bin__shiv').css('height', 'auto');

                                if ( EV.vals.view > viewport ) {

                                    // check container height to get height of tallest card
                                    // var tallest = $this.height();

                                    // check each bin to find tallest
                                    // http://stackoverflow.com/a/6061029/3163972
                                    var tallest = Math.max.apply(null, $bins.map(function () {
                                        return $(this).height();
                                    }).get());

                                    $bins.each( function() {

                                        var $this      = $(this),
                                            cardHeight = $this.height(); // set to innerHeight when checking container height

                                        // set height
                                        if ( cardHeight < tallest ) {

                                            $this.find('.bin__shiv').css('height', tallest - cardHeight );
                                        }

                                    });

                                }
                            });
                        };

                    // add shiv
                    $container
                        .find('[data-bin-shiv]')
                        .after('<div class="bin__shiv"></div>');

                    /**
                     * set height on page load
                     */
                    setHeights();

                    /**
                     * Set height on resize
                     */
                    var resized  = false;

                    // delay checking of window resize
                    EV.vals.$window.on( 'resize', function() { resized = true; });

                    setInterval( function() {

                        if ( resized ) {

                            resized = false;

                            setHeights();
                        }

                    }, 250);

                    /**
                     * Set height after images have loaded
                     *
                     * Wait for images within card to load before calculating
                     * height of card container
                     *
                     * Always - all images loaded whether successful or not
                     */
                    $container.imagesLoaded()
                        .always( function( instance ) {
                            setHeights();
                        });
                },
            },

            patternLibrary : function() {

                $('.js-pl-source-toggle').on( 'click', function( e ) {

                    $(this).closest('.pl-source').find('.pl-source__code').slideToggle();
                    // $('.pl-source__code').slideToggle();
                    e.preventDefault();
                });
            }
        };

        EV.init();

    });

})(jQuery, window, document);
