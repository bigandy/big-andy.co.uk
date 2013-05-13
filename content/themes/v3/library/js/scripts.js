// this is for the nav button
jQuery(document).ready(function($) {

    $('.top-nav').before('<a href="#" class="menu-trigger"><i>Menu</i></a>');
    $('.menu-trigger').click(function() {
        $('.top-nav').toggleClass('is-open');
    });
});