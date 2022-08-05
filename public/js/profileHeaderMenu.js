var profileHeaderMenuOpened = false;
$(function () {
    $('#profileHeaderImg').on('click', function() {
        $('#profileHeaderMenu').css({'display' : 'block'});
        $('#profileHeaderImg img').css({'outline' : '2px solid black'});
        profileHeaderMenuOpened = true;
    });
    $('#profileHeaderImg').hover(
        function() {
            if (!profileHeaderMenuOpened)
                $('#profileHeaderImg img').css({'outline' : '2px solid black'});
        },
        function() {
            if (!profileHeaderMenuOpened)
                $('#profileHeaderImg img').css({'outline' : '0px solid black'});
        }
    );
});
$(document).mouseup( function(e){
    var div = $( "#profileHeaderMenu" );
    if ( !div.is(e.target) && div.has(e.target).length === 0 ) {
        profileHeaderMenuOpened = false;
        div.css({'display' : 'none'});
        $('#profileHeaderImg img').css({'outline' : '0px solid black'});
    }
});

