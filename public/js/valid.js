$(function () {
    //  phone - номер телефона (не больше 11 цифр)
    $('.phone').on('keypress', function (e) {
        if ($(this).val().length > 10) return false;

        var keyCode = e.which ? e.which : e.keyCode;

        if (!(keyCode >= 48 && keyCode <= 57)) {
            return false;
        }

        if ($(this).val().length == 0 && keyCode != 56) $(this).val('8');
    })
    // maxlenght не даст ввести больше чем положено
    $(".input").each(function(index) {
        $input = $(".input").eq(index);
        $maxlenght = $input.attr('maxlenght');

        if ($maxlenght != null) {
            $input.on('keypress', function (e) {
                if ($(this).val().length > $maxlenght) return false;
            })
        }
    });
});
