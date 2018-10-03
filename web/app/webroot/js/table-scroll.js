$(function() {
        var a = $(".scroll_table").height();
        var b = $(window).height();
        if (b)
        {
            if (a > b)
            {
                $(".scroll_div").height(b);
            }
        }

    });