<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Dashboard') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Charts') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Charts'); ?></h4>

    <div class="buttons pull-right">
        <a class="link_back btn btn-default btn-icon glyphicons left_arrow"
           href="<?php echo $this->webroot; ?>homes/report">
            <i></i><?php __('Back') ?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard" class="glyphicons dashboard">
                        <i></i><?php __('Dashboard') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/report" class="glyphicons stats">
                        <i></i><?php __('Report') ?>
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>homes/search_charts" class="glyphicons charts">
                        <i></i><?php __('Charts') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/qos_report" class="glyphicons notes">
                        <i></i><?php __('Qos Report') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/ingress"
                       class="glyphicons eye_open">
                        <i></i><?php __('Ingress Clients Qos') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/egress"
                       class="glyphicons eye_open">
                        <i></i><?php __('Egress Clients Qos') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">

            <h1 style="padding:0;margin:10px;width:100%;font-size:14px;text-align:center;">
                <?php
                echo $start_time . '&nbsp' . $end_time . '&nbsp' . $timezone . '(GMT)';
                ?>
            </h1>

            <input type="hidden" id="editor"/>

            <h2 id="none" class="msg center" style="display:none;">No Result Is Available.</h2>
            <div id="chart">
                <?php __('chart goes here') ?>
            </div>


            <?php echo $this->element("homes/query_chart")?>


        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot; ?>flexchart/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot; ?>flexchart/prototype.js"></script>
<script type="text/javascript">

    var $1 = $;


    function $1G(id) {
        return document.getElementById(id);
    }

    function $1F(id) {
        return $1G(id).value;
    }

    function repopulate() {

        jQuery.ajax({
            'url': "<?php echo $this->webroot ?>homes/get_charts_data?qs=<?php echo $param; ?>",
            'type': "GET",
            "dataType": "text",
            "success": function (data) {
                if(!data){
                    $('#none').show();
                    return false;
                }

                $1("editor").value = data;
                injectFromEditor();
            }
        });

    }

    function injectFromEditor() {
        function f() {
		console.log($1("chart").setDescriptor);
            if ($1("chart").setDescriptor) {
                if (!window.swfloaded) {
                    window.swfloaded = true;
                }
                clearInterval(i);
                $1("chart").setDescriptor($1F("editor"));
            }
        }

        var i = setInterval(f, 100);
        f();
    }


    swfobject.addDomLoadEvent(function () {
        window.swfloaded = false;
        swfobject.embedSWF("<?php echo $this->webroot; ?>flexchart/FlexChart.swf", "chart", '100%', 300, "9.0.28", false, {
                descriptor: "<chart />"
            },
            {
                bgcolor: "#ffffff",
                wmode: "transparent"
            });
        repopulate();

    });

    var $ = jQuery;

</script>