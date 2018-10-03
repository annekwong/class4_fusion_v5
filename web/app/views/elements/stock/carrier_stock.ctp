<style type="text/css">
    #flashcontent {float:left;}
    #control{float:right;}
</style>
<script type="text/javascript" src="<?php echo $this->webroot; ?>stock/swfobject.js"></script>

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
<!--            <option value="carrier_stats_profit">Profit</option>-->
        </select>
    </div>
</div>

<script type="text/javascript">
// <![CDATA[	
function showstock(name, gress, resource_id) {
    var so = new SWFObject("<?php echo $this->webroot; ?>stock/amstock.swf", "amstock", "1200", "500", "8", "#FFFFFF");
    so.addVariable("path", "<?php echo $this->webroot; ?>upload/stock/");
    so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot; ?>stocks/createxml2/" + name + '/' + gress + '/' + resource_id + '.xml'));
    so.addVariable("preloader_color", "#999999");
    so.addParam("wmode","transparent");  
    so.write("flashcontent");
}
$(function() {
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