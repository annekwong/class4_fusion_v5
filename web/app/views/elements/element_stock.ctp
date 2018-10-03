  <style type="text/css">
  #flashcontent {float:left;}
  #control{float:right;}
  </style>
<!-- amstock script-->
  <script type="text/javascript" src="<?php echo $this->webroot; ?>stock/swfobject.js"></script>
  <script type="text/javascript" src="jquery.js"></script>
	<div id="flashcontent">
		<strong><?php echo __('viewcharts')?></strong>
	</div>
  
        <div id="control">
            <select style="width:120px;">
                <option value="0"><?php __('Please select')?>...</option>
                <option value="global_stats_call"><?php __('Calls')?></option>
                <option value="global_stats_cps"><?php __('CPS')?></option>
                <option value="global_stats_acd"><?php __('ACD')?></option>
                <option value="global_stats_pdd"><?php __('PDD')?></option>
                <option value="global_stats_asr"><?php __('ABR')?></option>
            </select>
        </div>

	<script type="text/javascript">
        // <![CDATA[	
        function showstock(name) {
            var so = new SWFObject("<?php echo $this->webroot; ?>stock/amstock.swf", "amstock", "1200", "500", "8", "#FFFFFF");
            so.addVariable("path", "<?php echo $this->webroot; ?>upload/stock/");
            //so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot; ?>stock/createxml.php?name=" + name));
            so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot; ?>monitorsreports/create_config_xml/" + name + '.xml'));
            so.addVariable("preloader_color", "#999999");
            so.addParam("wmode","transparent");  
    //  so.addVariable("chart_settings", "");
    //  so.addVariable("additional_chart_settings", "");
    //  so.addVariable("loading_settings", "Loading settings");
    //  so.addVariable("error_loading_file", "ERROR LOADING FILE: ");

            so.write("flashcontent");
        }
        $(function() {
            $('#control select').change(function() {
                if($(this).val() == "0"){
                    return false;
                }
                showstock($(this).val());
            });
            $('#control select').change();
        });
        
        $(function() {
            var thetime = 180000;
            var iterval;
            $('#changetime').change(function() {
                refresh($(this).val() * 1000);
            });
            
            function refresh(thetime) {
                clearInterval(iterval);
                iterval = setInterval(function() {
                    var val = $('#control select').val();
                    if(val !=0 ) {
                         showstock(val);  
                    }
                }, thetime);
            }
            refresh(thetime);
        });
        // ]]>
	</script>
<!-- end of amstock script -->