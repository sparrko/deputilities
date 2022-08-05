var helperOpened = false;
$(function () {
    $('.helper img').on('click', function() {
        contextMenuOpened = true;
        $(this).parent().children().eq(1).css({'display' : 'block'});
    });
});
$(document).mouseup(function(e){
    // if (helperOpened) {
        $('.helper div').each(function() {
            var div = $(this);
    //         if (!div.is(e.target) && div.has(e.target).length === 0) {
                div.css({'display' : 'none'});
    //         }
        });
        helperOpened = false;

    // }
});

