$(function () {

    $('img-loader').each(function(index) {
        $el = $('img-loader').eq(index);

        $src = $el.attr('src');
        $nameEl = $el.attr('name');
        $mwidth = $el.attr('mwidth');
        $mweight = $el.attr('mweight');


        if ($src != null && $src.length > 0) {
            var inputFile = "<input type='file' mweight='" + $mweight + "' accept='image/png, image/gif, image/jpeg' />";
            var inputB64 = "<input type='text' name='" + $nameEl + "' id='" + $nameEl + "' value='"+ $src + "' />";
            var clickAction = "$(this).parent().children().eq(0).click();";
            $el.replaceWith("<div class='imgLoader'>" + inputFile + "<img src='" + $src + "' style='max-width:" + $mwidth + "' /><a class='link' onclick='" + clickAction + "'>Загрузить</a>" + inputB64 + "</div>");
        }
        else {
            var inputFile = "<input type='file' mweight='" + $mweight + "' accept='image/png, image/gif, image/jpeg' />";
            var inputB64 = "<input type='text' name='" + $nameEl + "' id='" + $nameEl + "' />";
            var clickAction = "$(this).parent().children().eq(0).click();";
            $el.replaceWith("<div class='imgLoader'>" + inputFile + "<img style='max-width:" + $mwidth + "' /><a class='link' onclick='" + clickAction + "'>Загрузить</a>" + inputB64 + "</div>");
        }
    });

    $(".imgLoader input[type='file']").change(function () {
        $mweight = $el.attr('mweight');

        if (this.files[0].size > parseInt($mweight)) {
            alert("Размер изображения не должен привышать "+ String($mweight) + " байт.");
        }
        else{
            $imgEl = $(this).parent().children().eq(1);
            $inputEl = $(this).parent().children().eq(3);
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $imgEl.attr('src', event.target.result);
                    $inputEl.attr('value', event.target.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

});
