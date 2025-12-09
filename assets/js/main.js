/**
 * My Custom Theme - Main JavaScript
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        
        // Mobile menu toggle
        $('.menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('#primary-menu').slideToggle(300);
        });

        // Smooth scroll for anchor links
        $('a[href*="#"]').on('click', function(e) {
            if (this.hash !== "" && this.pathname === window.location.pathname) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $(this.hash).offset().top - 80
                }, 800);
            }
        });

        // Add class to header on scroll
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 100) {
                $('.site-header').addClass('scrolled');
            } else {
                $('.site-header').removeClass('scrolled');
            }
        });

    });

    // Window load
    $(window).on('load', function() {
        // Add any window load functions here
    });

})(jQuery);