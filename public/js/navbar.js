$(function () {
    $(document).scroll(function () {
        var $nav = $(".navbar");
        $nav.toggleClass('nav-scrolled', $(this).scrollTop() > $nav.height());
    });
});
