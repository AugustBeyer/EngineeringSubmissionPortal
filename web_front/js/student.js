/*$(document).ready(function () {
    $(window).scroll(function () {
        var y = $(this).scrollTop();
        $('.link').each(function (event) {
            if (y >= $($(this).attr('href')).offset().top - 10) {
                $('.link').not(this).removeClass('active');
                $(this).addClass('active');
            }
        });
    });
});


$(function () {
    $('a[href*=#]:not([href=#])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: (target.offset().top - 0)
                }, 900);
                return false;
            }
        }
    });
}); */