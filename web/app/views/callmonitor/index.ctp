<style type="text/css">
    #control_panel {margin:10px;text-align:right;}
</style>

<div id="title">
    <h1><?php echo __('Tools'); ?> &gt;&gt; <?php echo __('Call Monitor'); ?></h1>
</div>

<div id="container">
    
    <div id="search_panel">
        <form id="search_form" name="search_form">
            <table class="query_table">
                <tbody>
                    <tr>
                        <td>Orig From IP</td>
                        <td>
                            <input type="text" name="orig_from_ip">
                        </td>
                        <td>Orig To IP</td>
                        <td>
                            <input type="text" name="orig_to_ip">
                        </td>
                        <td>Orig ANI</td>
                        <td>
                            <input type="text" name="orig_ani">
                        </td>
                        <td>Orig DNIS</td>
                        <td>
                            <input type="text" name="orig_dnis">
                        </td>
                    </tr>
                    <tr>
                        <td>Term From IP</td>
                        <td>
                            <input type="text" name="term_from_ip">
                        </td>
                        <td>Term To IP</td>
                        <td>
                            <input type="text" name="term_to_ip">
                        </td>
                        <td>Term ANI</td>
                        <td>
                            <input type="text" name="term_ani">
                        </td>
                        <td>Term DNIS</td>
                        <td>
                            <input type="text" name="term_dnis">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    
    <div id="control_panel">
        <input type="button" id="clear_btn" value="Clear">
        <input type="button" id="switch_start" value="Start Monitor" style="width:auto; <?php if ($status) echo "display:none;" ?>" />
        <input type="button" id="switch_stop" value="Stop Monitor" style="width:auto; <?php if (!$status) echo "display:none;" ?>" />
    </div>
    
    <table class="list">
        <thead>
            <tr>
                <th>Orig Time</th>
                <th>Orig From IP</th>
                <th>Orig To IP</th>
                <th>Orig ANI</th>
                <th>Orig DNIS</th>
                <th>Term Time</th>
                <th>Term From IP</th>
                <th>Term To IP</th>
                <th>Term ANI</th>
                <th>Term DNIS</th>
                <th>RBT</th>
                <th>Audio</th>
                <th>SIP</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    <div>
        <button id="fetch_btn" style="width:100%;">More...</button>
    </div>
    
    
</div>
<div id="loading" style="display:none;"></div>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.base64.min.js"></script>
<script type="text/javascript">
    
    
$(function() {
    window.tell = 0;
    
    var $switch_start = $('#switch_start');
    var $switch_stop = $('#switch_stop');
    var $clear_btn = $('#clear_btn');
    var $loading = $('#loading');
    var $table = $('table.list tbody');
    var $fetch_btn = $('#fetch_btn');
    var $search_form = $('#search_form');
    
    
    
    $switch_start.click(function() {
        $.ajax({
            'url' : '<?php echo $this->webroot; ?>callmonitor/start',
            'type' : 'POST',
            'dataType' : 'text',
            'success' : function(data) {
                $loading.hide();
                $switch_start.hide();
                $switch_stop.show();
                window.setTimeout(poll, 5000);
            },
            'beforeSend' : function() {
                $loading.show();
            }
        });
    });
    
    $switch_stop.click(function() {
         $.ajax({
            'url' : '<?php echo $this->webroot; ?>callmonitor/stop',
            'type' : 'POST',
            'dataType' : 'text',
            'success' : function(data) {
                $loading.hide();
                $switch_start.show();
                $switch_stop.hide();
            },
            'beforeSend' : function() {
                $loading.show();
            }
        });
    });
    
    
    $clear_btn.click(function() {
         $.ajax({
            'url' : '<?php echo $this->webroot; ?>callmonitor/clear',
            'type' : 'POST',
            'dataType' : 'text',
            'success' : function(data) {
                $loading.hide();
                $table.empty();
            },
            'beforeSend' : function() {
                $loading.show();
            }
        });
    });
    
    
    function poll() {
        $.ajax({
            'url' : '<?php echo $this->webroot ?>callmonitor/poll',
            'type' : 'POST',
            'dataType' : 'json',
            'data' : {'tell' : window.tell, 'query' : $search_form.serialize()},
            'success' : function(data) {
                var records = data['records'];
                window.tell = data['tell'];
                $loading.hide();
                //$table.empty();
                $.each(records, function(index, row) {
                    var $tr = $('<tr />');
                    $tr.append('<td>' + row['orig_time'] + '</td>');
                    $tr.append('<td>' + row['orig_src_ip'] + '</td>');
                    $tr.append('<td>' + row['orig_dst_ip'] + '</td>');
                    $tr.append('<td>' + row['orig_ani'] + '</td>');
                    $tr.append('<td>' + row['orig_dnis'] + '</td>');
                    $tr.append('<td>' + row['term_time'] + '</td>');
                    $tr.append('<td>' + row['term_src_ip'] + '</td>');
                    $tr.append('<td>' + row['term_dst_ip'] + '</td>');
                    $tr.append('<td>' + row['term_ani'] + '</td>');
                    $tr.append('<td>' + row['term_dnis'] + '</td>');
                    $tr.append('<td><a target="_blank" href="<?php echo $this->webroot ?>callmonitor/download_rtp/rbt/' + row['term_callid'] + '">D/L</a></td>');
                    $tr.append('<td><a target="_blank" href="<?php echo $this->webroot ?>callmonitor/download_rtp/audio/' + row['term_callid'] + '">D/L</a></td>');
                    $tr.append('<td><a target="_blank" href="<?php echo $this->webroot ?>callmonitor/download_aud?file=' + $.base64.encode(row['filename']) + '">D/L</a></td>');
                    $table.append($tr);
                });
            },
            'beforeSend' : function() {
                $loading.show();
            }
        });
    } 
   
    $fetch_btn.bind('click', poll);
    
    poll();
    
});
</script>