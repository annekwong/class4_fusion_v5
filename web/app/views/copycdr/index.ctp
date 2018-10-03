<style type="text/css">
    #error_info {
        background:white;width:300px;height:200px;display:none;
        overflow:hide;word-wrap: break-word; padding:20px;
    }
</style>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('CDR Import') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('CDR Import') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if (!isset($_GET['showerror'])) echo ' class="active"'; ?>><a href="<?php echo $this->webroot ?>copycdr" class="glyphicons list"><i></i><?php __('Show All'); ?></a></li>
                <li <?php if (isset($_GET['showerror'])) echo ' class="active"'; ?>><a href="<?php echo $this->webroot ?>copycdr?showerror=1" class="glyphicons list"><i></i><?php __('Show Errors'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">
    <div class="clearfix"></div>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php __('CDR File Name')?></th>
                <th><?php __('Status')?></th>
                <th><?php __('Copy Time')?></th>
                <th><?php __('Finish Time')?></th>
                <th><?php __('Error Info')?></th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['CdrLog']['cdr_filename']; ?></td>
                <td><?php echo $status[(string)$item['CdrLog']['status']]; ?></td>
                <td><?php echo $item['CdrLog']['copy_time']; ?></td>
                <td><?php echo $item['CdrLog']['finish_time']; ?></td>
                <td>
                    <a href="###" class="showerror" style="display:block;cursor: pointer;" control="<?php echo $item['CdrLog']['id']; ?>">
                        <?php echo substr($item['CdrLog']['error_info'], 1, 30); ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('xpage'); ?>
        </div> 
    </div>
    <div class="clearfix"></div>
</div>

<div id="error_info">
</div>

    </div>
</div>
<script>
jQuery.fn.center = function(f) {  
    return this.each(function(){  
        var p = f===false?document.body:this.parentNode;  
        if ( p.nodeName.toLowerCase()!= "body" && jQuery.css(p,"position") == 'static' )  
            p.style.position = 'relative';  
        var s = this.style;  
        s.position = 'absolute';  
        if(p.nodeName.toLowerCase() == "body")  
            var w=$(window);  
        if(!f || f == "horizontal") {  
            s.left = "0px";  
            if(p.nodeName.toLowerCase() == "body") {  
                var clientLeft = w.scrollLeft() - 10 + (w.width() - parseInt(jQuery.css(this,"width")))/2;  
                s.left = Math.max(clientLeft,0) + "px";  
            }else if(((parseInt(jQuery.css(p,"width")) - parseInt(jQuery.css(this,"width")))/2) > 0)  
                s.left = ((parseInt(jQuery.css(p,"width")) - parseInt(jQuery.css(this,"width")))/2) + "px";  
        }  
        if(!f || f == "vertical") {  
            s.top = "0px";  
            if(p.nodeName.toLowerCase() == "body") {  
                var clientHeight = w.scrollTop() - 10 + (w.height() - parseInt(jQuery.css(this,"height")))/2;  
                s.top = Math.max(clientHeight,0) + "px";  
            }else if(((parseInt(jQuery.css(p,"height")) - parseInt(jQuery.css(this,"height")))/2) > 0)  
                s.top = ((parseInt(jQuery.css(p,"height")) - parseInt(jQuery.css(this,"height")))/2) + "px";  
        }  
    });  
};  

$(function() {
    $('.showerror').click(function() {
        var control_id = $(this).attr('control');
        $.ajax({
            'url' : '<?php echo $this->webroot ?>copycdr/get_error_info_detail',
            'type' : 'POST',
            'dataType' : 'text',
            'data' : {'id' : control_id},
            'success' : function(data) {
                $('#error_info').text(data);
                $('#error_info').center().css('opacity', .8).show();
            }
        });
    });
    
    $('#container').click(function() {
        $('#error_info').hide();
    });
});

</script>