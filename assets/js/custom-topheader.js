jQuery(document).ready(function($) {
    
    // ========== STICKY HEADER ==========
    let lastScroll = 0;
    const header = $('.custom-topheader');
    
    $(window).scroll(function() {
        const currentScroll = $(this).scrollTop();
        
        if (currentScroll > 100) {
            header.addClass('scrolled');
            
            if (currentScroll > lastScroll && currentScroll > 200) {
                header.addClass('hide');
            } else {
                header.removeClass('hide');
            }
        } else {
            header.removeClass('scrolled hide');
        }
        
        lastScroll = currentScroll;
    });
    
    // ========== TOPBAR HOVER EFFECT ==========
    $('.topbar-item, .topbar-item-fixed').on('mouseenter', function() {
        $(this).css('opacity', '0.85');
    }).on('mouseleave', function() {
        $(this).css('opacity', '1');
    });
    
    // ========== MENU DROPDOWN ==========
    $('.menu-toggle').on('click', function(e) {
        e.stopPropagation();
        $(this).toggleClass('active');
        $('.mega-menu-dropdown').slideToggle(200);
    });
    
    // Đóng menu khi click bên ngoài
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.header-menu').length) {
            $('.menu-toggle').removeClass('active');
            $('.mega-menu-dropdown').slideUp(200);
        }
    });
    
    // ========== PHONE CLICK ==========
    $('a[href^="tel:"]').on('click', function(e) {
        e.preventDefault();
        const phoneNumber = $(this).attr('href').replace('tel:', '');
        console.log('Calling:', phoneNumber);
        // Có thể thêm tracking hoặc analytics ở đây
    });
    
});