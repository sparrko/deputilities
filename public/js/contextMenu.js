var contextMenuOpened = false;
$(function () {
    $('.contextMenu img').on('click', function() {
        contextMenuOpened = true;
        $(this).parent().children().eq(1).css({'display' : 'block'});
    });
});
$(document).mouseup(function(e){
    // if (contextMenuOpened) {
        $('.contextMenu ul').each(function() {
            var div = $(this);
    //         if (!div.is(e.target) && div.has(e.target).length === 0) {
                div.css({'display' : 'none'});
                contextMenuOpened = false;
    //         }
        });
    // }
});

