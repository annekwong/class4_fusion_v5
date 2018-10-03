function myconfirm(msg, obj, buttons = [])
{
    var $obj = $(obj);
    if(buttons.length > 0) {
        bootbox.confirm(msg, buttons[1], buttons[0], function(result) {
            if(result) {
                window.location.href = $obj.attr('href');
            }
        });
    } else {
        bootbox.confirm(msg, function(result) {
            if(result) {
                window.location.href = $obj.attr('href');
            }
        });
    }
    return false;
}

$(function() {
    $('form').validationEngine();

    $('a:not(.no-tooltip)').qtip({
        style: {
            classes: 'qtip-shadow qtip-tipsy'
        }
    });
    
    $('#advance_btn').click(function() {
        var $this = $(this);
        var $i  = $this.find('i');
        if ($i.hasClass('icon-long-arrow-down')) {
            $i.removeClass('icon-long-arrow-down');
            $i.addClass('icon-long-arrow-up');
        } else {
            $i.removeClass('icon-long-arrow-up');
            $i.addClass('icon-long-arrow-down');
        }
        if ($('.dynamicTable:visible').size() > 0){
            $('.dynamicTable:visible').floatThead('reflow');
        }else if($('.table_page_num:visible').size() > 0){
            $('.table_page_num').floatThead('reflow');
        }
        $('#advance_panel').toggle('fast', function() {
            if ($('.dynamicTable:visible').size() > 0){
                $('.dynamicTable:visible').floatThead('reflow');
            }else if($('.table_page_num:visible').size() > 0){
                $('.table_page_num').floatThead('reflow');
            }
        });

    });
});