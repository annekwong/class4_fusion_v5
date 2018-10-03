/**
 * Created by root on 16-3-23.
 */
$(function(){
    $('[data-toggle="notyfy"]').click(function ()
    {
        var self = $(this);
        var show_value = self.data('value');
        var hasHtml = /<[^>]+>/g.test(show_value);
        $('.notyfy_message').remove();
        if (hasHtml){
            var data_value = $('<div/>').text(self.data('value')).html();
            var strLength = data_value.length;
            var strLine = Math.floor(strLength / 40);
            var show_value = '';
            var tmp_str = '';
            //console.log(strLine);
            for (var i = 0;strLine > i; i ++){
                tmp_str = data_value.substring(i*40,(i+1)*40);
                show_value += tmp_str + '<br />';
            }
            show_value += data_value.substring(strLine*40);
        }
        notyfy({
            text: show_value,
            type: self.data('type'),
            dismissQueue: true,
            layout: self.data('layout'),
            template: '<div class="notyfy_message" style="background-color: #525252"><span class="notyfy_text"></span><div class="notyfy_close"></div></div>',
        });
        return false;
    });
})