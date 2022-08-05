$(function(){
    $("#goUpButton").bind('click', function(e){
        e.preventDefault();
        $('body,html').animate({scrollTop: 0}, 800);
    });
    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) $("#goUpButton").fadeIn(400);
        else $("#goUpButton").fadeOut(200);
    });
});
