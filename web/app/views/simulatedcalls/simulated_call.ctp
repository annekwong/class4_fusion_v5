<style>
    a#xml_alert {
        float: right;
        margin: 0px 0px 10px 0px;
    }

    a#xml_alert:after{
        clear: both;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>simulatedcalls/simulated_call">
            <?php __('Tools') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>simulatedcalls/simulated_call">
            <?php echo __('simulattedcall') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Simulated Call') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<style type="text/css">
    .form_input {float:left;width:220px;}

    .container ul{
        padding-left:20px;
    }
    .container ul li {
        padding:3px;
    }
    select,input[type="text"]{margin: 5px 0;}
    .table-condensed{border-left: 1px solid #EBEBEB;border-bottom: 1px solid #EBEBEB;}
    .table-condensed td{border-right:1px solid #EBEBEB;}
</style>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">


            <?php
            function strip_invalid_xml_chars2($in)
            {
                $out = "";
                $length = strlen($in);
                for ($i = 0; $i < $length; $i++)
                {
                    $current = ord($in{$i});
                    if (($current == 0x9) || ($current == 0xA) || ($current == 0xD) || (($current >= 0x20) && ($current <= 0xD7FF)) || (($current >= 0xE000) && ($current <= 0xFFFD)) || (($current >= 0x10000) && ($current <= 0x10FFFF)))
                    {
                        $out .= chr($current);
                    }
                    else
                    {
                        $out .= " ";
                    }
                }
                return $out;
            }

            function recursion($element, $digital_maps = false)
            {
                if($element->getName() == 'Origination-Translation-ANI'){
                    $ani = (string) $element->{'ANI'}[0];
                    if(isset($digital_maps['ani_'.$ani])){
                        $element->{'ANI'} = $digital_maps['ani_'.$ani];
                    }
                }elseif($element->getName() == 'Origination-Translation-DNIS'){
                    $dnis = (string) $element->{'DNIS'}[0];
                    if(isset($digital_maps['dnis_'.$dnis])){
                        $element->{'DNIS'} = $digital_maps['dnis_'.$dnis];
                    }
                }

                if ($element->getName() != 'root')
                {
                    echo "<li>";
                    echo str_replace('-', ' ', $element->getName());
                    if (trim($element) != '')
                    {
                        echo ' = ' . $element;
                    }
                }
                if ($element->count())
                {
                    foreach ($element->children() as $chldren)
                    {
                        echo "<ul>";
                        recursion($chldren, $digital_maps);
                        echo "</ul>";
                    }
                }
                echo "</li>";
            }

            if (isset($xdata))
            {
                ?>
                <?php
                $xdata = strip_invalid_xml_chars2($xdata);
                $string = <<<XML
<?xml version='1.0'?> 
<root>
$xdata
</root>
XML;
                $string = $appCommon->xmlEscape($string);
                $xml = simplexml_load_string($string);
                if (Configure::read('debug'))
                {
                    echo "<ul>";

                    recursion($xml , $digital_maps);

                    echo "</ul>";
                }
                ?>

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('.container li > ul').hide();
                        $('<img src="<?php echo $this->webroot . 'images/+.gif' ?>" />').prependTo('.container li:has(ul)').css('cursor', 'pointer').
                        toggle(function() {
                            $(this).attr('src', '<?php echo $this->webroot . 'images/-.gif' ?>').siblings().show();
                        }, function() {
                            $(this).attr('src', '<?php echo $this->webroot . 'images/+.gif' ?>').siblings().hide();
                        });
                    });
                </script>

                <table class="list  table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <tbody>
                    <tr>
                        <td><?php __('Ingress Trunk') ?></td>
                        <td><?php echo $xml->{'Origination-Trunk'}->{'Trunk-Name'}; ?></td>
                        <td><?php __('Ingress Host') ?></td>
                        <td><?php echo $_POST['data']['host']; ?></td>
                        <td><?php __('Ingress ANI') ?></td>
                        <td><?php echo $xml->{'Origination-SRC-ANI'}; ?></td>
                        <td><?php __('Ingress DNIS') ?></td>
                        <td><?php echo $xml->{'Origination-SRC-DNIS'}; ?></td>
                    </tr>
                    <tr>
                        <td><?php __('Route Prefix') ?></td>
                        <td>-</td>
                        <td><?php __('Routing Plan') ?></td>
                        <td><?php echo $xml->{'Origination-Trunk'}->{'Route-Strategy-Name'}; ?></td>
                        <td><?php __('Static Route') ?></td>
                        <td><?php echo $xml->{'Origination-Trunk'}->{'Static-Route-Name'}; ?></td>
                        <td><?php __('Dynamic Route') ?></td>
                        <td><?php echo $xml->{'Origination-Trunk'}->{'Dynamic-Route-Name'}; ?></td>
                    </tr>

                    <tr>
                        <td><?php __('Ingress Rate') ?></td>
                        <td><?php echo isset($xml->{'Origination-Trunk-Rate'}->{'Rate'}) ? $xml->{'Origination-Trunk-Rate'}->{'Rate'} : '' ?></td>
                        <td><?php __('LRN Num') ?></td>
                        <td><?php echo isset($xml->{'Origination-Respond-LRN-DNIS'}) ? $xml->{'Origination-Respond-LRN-DNIS'} : '' ?></td>
                        <td><?php __('Ingress Rate Table') ?></td>
                        <td><?php echo $xml->{'Origination-Trunk'}->{'Rate-Table-Name'}; ?></td>
                        <td><?php __('Release Cause') ?></td>
                        <td><?php echo isset($xml->{'Global-Route-State'}->{'Origination-State'}) ? $xml->{'Global-Route-State'}->{'Origination-State'} : ''; ?></td>
                    </tr>
                    <?php if(isset($rate_table_type) && $rate_table_type == 2):?>
                        <tr>
                            <td><?php __('Jurisdiction') ?></td>
                            <td><?php echo isset($xml->{'Origination-Trunk-Rate'}->{'Rate-Type'}) ? $xml->{'Origination-Trunk-Rate'}->{'Rate-Type'} : '' ?></td>
                        </tr>
                    <?php endif;?>
                    </tbody>
                </table>
            <?php
            if (isset($xml->{'Global-Route-State'}->{'Termination-Trunk'}))
            {
            ?>
                <table class="list table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php __('Egress Trunk') ?></th>
                        <th><?php __('Egress Host') ?></th>
                        <th><?php __('Term ANI') ?></th>
                        <th><?php __('Term DNIS') ?></th>
                        <th><?php __('Term Rate') ?></th>
                        <th><?php __('Release Cause') ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if (isset($xml->{'Termination-Route'}) && isset($xml->{'Termination-Route'}->{'Termination-Trunk'})) {

                        foreach ($xml->{'Termination-Route'}->{'Termination-Trunk'} as $item): ?>
                            <tr>
                                <td><?php echo $item->{'Trunk-Name'} ?></td>
                                <td><?php echo $item->{'Termination-Host'}->{'Host-IP'} ?></td>
                                <?php if(isset($replaced_number['ani'])):?>
                                    <td><?php echo $replaced_number['ani'] ?></td>
                                <?php else:?>
                                    <td><?php echo $item->{'Final-ANI'}->{'ANI'} ?></td>
                                <?php endif;?>
                                <?php if(isset($replaced_number['dnis'])):?>
                                    <td><?php echo $replaced_number['dnis'] ?></td>
                                <?php else:?>
                                    <td><?php echo $item->{'Final-DNIS'}->{'DNIS'} ?></td>
                                <?php endif;?>
                                <td><?php echo $item->{'Trunk-Rate'}->{'Rate'} ?></td>
                                <td><?php __('normal') ?></td>
                            </tr>
                        <?php endforeach;
                    }
                    ?>

                    <?php foreach ($xml->{'Global-Route-State'}->{'Termination-Trunk'} as $item): ?>
                        <?php if (strnatcasecmp($item->{'Cause'}, 'normal') != 0): ?>
                            <tr>
                                <td><?php
                                    //                                        if (isset($item->{'Cause'}))
                                    //                                        {
                                    //                                            echo isset($xml->{'Termination-Route'}->{'Termination-Trunk'}->{'Trunk-Name'}) ? $xml->{'Termination-Route'}->{'Termination-Trunk'}->{'Trunk-Name'} : '';
                                    //                                        }
                                    //                                        else
                                    //                                        {
                                    echo $item->{'Trunk-Name'};
                                    //                                        }
                                    ?>
                                </td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>
                                    <?php
                                    echo isset($item->{'Cause'}) ? $item->{'Cause'} : '';
                                    ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>
                <br />
                <?php
            }
            ?>
            <?php if (isset($xdata) && $xdata) {?>
                <a href="javascript:void(0)" id="xml_alert">Show More Detail</a>
                <!--                <input type="button" value="Show More Detail" id="xml_alert"  style="float:right;" class="btn btn-primary" />-->
            <?php } ?>
            <form method="post" action="">
                <div style="margin:0px auto;">


                    <table  class="table table-condensed">
                        <col width="40%">
                        <col width="60%">
                        <tr>
                            <td class="align_right"><?php echo __('Server', true); ?></td>
                            <td>
                                <?php echo $form->input("server", array('class' => "input in-select", 'options' => $voip_gateway, 'label' => false, 'div' => false, 'type' => "select")); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right"><?php echo __('ingress', true); ?></td>
                            <td>
                                <?php echo $form->input("ingress", array('onchange' => "chageSimulateIngress(this,'host')", 'class' => "input in-select form_input", 'options' => $appResource->format_ingress_options($ingress), 'label' => false, 'div' => false, 'type' => "select", 'empty' => array('' => "Select Ingress"))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="align_right"><?php echo __('Host', true); ?></td>
                            <td>
                                <?php echo $form->input("host", array('class' => "input in-select form_input", 'options' => $appSimulateCall->format_host_options($selected_hosts), 'label' => false, 'div' => false, 'type' => "select", 'empty' => false)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right"><?php echo __('origani') ?></td>
                            <td>
                                <?php echo $form->input("ani", array('class' => "input in-select form_input validate[required, custom[onlyLetterNumber]]", 'label' => false, 'div' => false)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right"><?php echo __('origdnis') ?></td>
                            <td>
                                <?php echo $form->input("dnis", array('class' => "input in-select form_input validate[required]", 'label' => false, 'div' => false)); ?>
                            </td>
                        </tr>
                        <!--tr>
                            <td class="align_right"><?php echo __('Time', true); ?></td>
                            <td><?php echo $form->input("time", array('onfocus' => "WdatePicker({dateFmt:'yyyy-MM-dd 00:00:00'});", 'style' => 'width:302px;', 'class' => "validate[required] input in-text wdate", 'readonly' => true, 'label' => false, 'div' => false, 'type' => 'text')) ?></td>
                        </tr-->
                        <tr style="display:none;">
                            <td ><?php echo __('Routing Plan', true); ?></td>
                            <td>
                                <?php echo $form->input("route_strategy", array('class' => "input in-select form_input", 'options' => $appSimulateCall->format_route_strategy_options($route_strategies), 'label' => false, 'div' => false, 'type' => "select", 'empty' => '')); ?>
                            </td>
                        </tr>
                        <tr  style="text-align:center;">
                            <td class="align_right"></td>
                            <td style="padding-left: 45px;">
                                <?php
                                if ($_SESSION['role_menu']['Tools']['simulatedcalls']['model_r'] && $_SESSION['role_menu']['Tools']['simulatedcalls']['model_x'])
                                {
                                    ?>
                                    <input type="submit" value="<?php echo __('submit') ?>" class="input in-submit btn btn-primary">

                                    <input type="reset" value="<?php echo __('reset') ?>" class="input in-submit btn btn-default" >
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                    <?php
                    if (isset($xdata))
                    {
                        $msg = str_replace('\n', '', $xdata);
//$msg = str_replace(' ', '', $msg);
                        $msg = html_entity_decode($msg);
                        $msg = preg_replace("/[^<]*/", '', trim($msg), 1);
                        $xmlStr = "<Document>";
                        $xmlStr .= $msg;
                        $xmlStr .= "</Document>";
//var_dump($xmlStr);
                        $xmlStr = $appCommon->xmlEscape($xmlStr);
                        $xml = simplexml_load_string($xmlStr);
//var_dump($xml);
                        $out = "";
                        foreach ($xml as $k0 => $v0)
                        {
                            $out .= "<div style=\"width: 100%;margin: 0px \">
    <fieldset><legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> $k0</legend>";
                            $out .= "<table class=\"list table table-striped dynamicTable tableTools table-bordered  table-white table-primary\">";
                            $out .= "</thead><tr>";
                            foreach ($v0 as $k => $v)
                            {
                                $out .= "<th>$k</th>";
                            }
                            $out .= "</tr></thead>";
                            $out .= "<tbody><tr style=\"background-color: #EDF0F5;\">";
                            foreach ($v0 as $k => $v)
                            {
                                $out .= "<td>$v</td>";
                            }
                            $out .= "</tr></tbody>";
                            $out .= "</table>";
                            $out .= "</fieldset></div>";
                        }
                    }
                    ?>
                    <div id="result" style="display: none;">
                        <?php echo $out; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    function chageSimulateIngress(ingress, hostId) {
        var host = document.getElementById(hostId);
        host.options.length = 0;
        if (ingress.value.length >= 1) {
            jQuery.getJSON("<?php echo $this->webroot ?>simulatedcalls/get_ingress_by_resource?r_id=" + ingress.value, {}, function(data) {
                var datas = data;//eval(data);
                var loop = datas.length;
                for (var i = 0; i < loop; i++) {

                    var d = datas[i];
                    var option = document.createElement("option");
                    option.innerHTML = d.ip + ":" + d.port;
                    host.appendChild(option);
                }
            });
        }
    }

    $(function() {
        $('input[type="reset"]').on('click', function(){
            setTimeout(function(){
                $('#ani, #dnis, #time, #ingress').val('');
                $('#host option').remove();
            },1000);
        })

        <?php
        if (isset($xdata))
        {
        ?>
        $("#xml_alert").click(function() {
            $("#result").dialog({
                'width': '650px',
                'height': 500,
                'buttons': [{text: "Cancel", "class": "btn btn-inverse", click: function() {
                        $(this).dialog("close");
                    }}]

            });

        });
        <?php } ?>
    });


</script>