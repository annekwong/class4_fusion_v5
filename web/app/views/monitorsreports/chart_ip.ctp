<style type="text/css">
#flashcontent {float:left;}
#control{float:right;}
#cps_cap_info {
    font-size:14px;
    font-weight:bold;
    padding:10px;
}
#cps_cap_info span {padding:0 10px;}
#cps_cap_info b {color:red;}
</style>

<div id="title">
<h1><?php echo __('Statistics');?>&gt;&gt;<?php echo Inflector::humanize($h_title)?> <?php __('Report')?>
</h1>
<ul id="title-menu">
    <li>
        <font class="fwhite"><?php echo __('Switch Server',true);?>:</font>
        <select id="server_info" style="width:180px;">
            <?php foreach($limit_servers as $limit_server): ?>
            <option value="<?php echo $limit_server[0]['info_ip'] . ':' . $limit_server[0]['info_port'] ?>"><?php echo $limit_server[0]['ip'] . ':' . $limit_server[0]['port'] ?></option>
            <?php endforeach;?>
        </select>
    </li>
		<li>
			<a class="link_back" href="<?php echo $this->webroot; ?>clients/index">
	<img width="16" height="16" src="<?php echo $this->webroot; ?>images/icon_back_white.png" alt="Back">
	&nbsp;Back</a>    	</li>
 	</ul>
</div>


<div id="container">


<?php echo $this->element('qos/qos_tab',array('active_tab'=>$this->params['pass'][1])) ?>
<!--
<div id="cps_cap_info">
    <span>CPS:<b><?php echo $cps ?></b></span>
    <span>CAP:<b><?php echo $cap ?></b></span>
</div>
-->    
<br />
<table class="list">
    <tbody>
        <tr>
            <td><?php __('CPS')?>:</td>
            <td id="cps"></td>
            <td><?php __('Calls')?>:</td>
            <td id="calls"></td>
        </tr>
    </tbody>
</table>
<br />
<div id="stock">
    <div id="flashcontent">
            <strong><?php echo __('viewcharts')?></strong>
    </div>
    <div id="control">
        <select>
            <option value="0"><?php __('Please select')?>...</option>
            <option value="carrier_stats_call"><?php __('Calls')?></option>
            <option value="carrier_stats_cps"><?php __('CPS')?></option>
            <option value="carrier_stats_acd"><?php __('ACD')?></option>
            <option value="carrier_stats_pdd"><?php __('PDD')?></option>
            <option value="carrier_stats_asr"><?php __('ABR')?></option>
        </select>
    </div>
    <br style="clear:both;" />
</div>

</div>


<script type="text/javascript">
// <![CDATA[	
function showstock(name, ip_id, ctype) {
    var so = new SWFObject("<?php echo $this->webroot; ?>stock/amstock.swf", "amstock", "1200", "500", "8", "#FFFFFF");
    so.addVariable("path", "<?php echo $this->webroot; ?>upload/stock/");
    so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot; ?>stocks/createxml3/" + name + '/' + ip_id + '/' + ctype + '.xml'));
    so.addVariable("preloader_color", "#999999");
    so.addParam("wmode","transparent");  
    so.write("flashcontent");
}
$(function() {
    
    $('#server_info').change(function() {
        var type = "<?php echo $type; ?>";
        var ip_id = <?php echo $ip_id; ?>;
        var server_info = $(this).val();
        var server_info_arr = server_info.split(':');
        var ip = server_info_arr[0];
        var port = server_info_arr[1];
        $.ajax({
            'url' : "<?php echo $this->webroot; ?>monitorsreports/get_trunk_ip_count",
            'type' : 'POST',
            'dataType' : 'json',
            'data' : {'ip':ip, 'port':port, 'type':type, 'ip_id' : ip_id},
            'success' : function(data) {
                $('#cps').text(data[0]);
                $('#calls').text(data[1]);
            }
        });
    });

    $('#server_info').change();
    
    $('#control select').change(function() {
        if($(this).val() == "0"){
            return false;
        }
        showstock($(this).val(), '<?php echo $this->params['pass'][0] ?>','<?php echo $this->params['pass'][1] ?>');
    });
    $('#control select').change();
});
// ]]>
</script>