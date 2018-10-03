<ul class="breadcrumb">
    <li><?=__('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>configs/index">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>configs/index">
        <?php echo __('Verify Configuration') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Verify Configuration') ?></h4>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <div class="widget widget-tabs widget-tabs-double-2 widget-tabs-gray">

                <div class="widget-body">
                    <div class="tab-content">

                        <!-- Tab content -->
                        <div class="accordion" id="accordion">
                            <div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle" data-toggle="collapse"  data-parent="#accordion" href="#collapse-1">
                                                <?=__('Switch Service Test')?>
                                            </a>
                                        </div>
                                        <div style="height: auto;" id="collapse-1" class="accordion-body in collapse">
                                            <div class="accordion-inner">
                                                    <table class=" table  tableTools table-bordered  table-condensed table-primary" >
                                                        <thead>
                                                            <tr>
                                                                <th class="center"><?php __('IP')?></th>
                                                                <th><?php __('Port')?></th>
                                                                <th><?php __('Status')?></th>
                                                                <th><?php __('Action')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="center" ondblclick="edit_ip(this,'web_switch');" ><?=$web_switch_ip?></td>
                                                                <td  ondblclick="edit_port(this,'web_switch');" ><?=$web_switch_port?></td>
                                                                <td><span class="status_span"></span></td>
                                                                <td>
                                                                    <input onclick="get_msg('web_switch',this);" id="init_web_switch" class="btn btn-primary test_in" type="submit" value="<?php __('Verify Again')?>">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="loading_img" style="text-align: center;display:none;">
                                                            <img src="<?=$this->webroot?>app/webroot/images/loader12.gif" />
                                                    </div>

                                            </div>
                                        </div>
                                    </div>



                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" onclick="click_test('web_switch',this);" data-parent="#accordion" href="#collapse-2">
                                        <?=__('Switch Info Test')?>
                                    </a>
                                </div>
                                <div style="height: 0px;" id="collapse-2" test_class="web_switch" class="accordion-body collapse">
                                    <div class="accordion-inner">

                                        <?php
                                        if(count($sipss) == 0){
                                            ?>
                                            <center><div class="msg"><?php  echo __('no_data_found') ?></div></center>
                                        <?php
                                        }else{
                                            ?>

                                            <table class=" table  tableTools table-bordered  table-condensed table-primary" >
                                                <thead>
                                                <tr>
                                                    <th class="center"><?php __('IP')?></th>
                                                    <th><?php __('Port')?></th>
                                                    <th><?php __('Status')?></th>
                                                    <th><?php __('Action')?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach($sipss as $sip){
                                                    if(empty($sip[0]['lan_ip'])){
                                                        continue;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="center"  ><?=$sip[0]['lan_ip']?></td>
                                                        <td  ><?=$sip[0]['lan_port']?></td>
                                                        <td><span class="status_span"></span></td>
                                                        <td>
                                                            <input onclick="get_msg_lan(<?=$sip[0]['id']?>,this);" class="btn btn-primary test_in web_switch" type="submit" value="<?php __('Verify Again')?>">
                                                        </td>
                                                    </tr>

                                                <?php
                                                }
                                                ?>

                                                </tbody>
                                            </table>
                                        <?php
                                        }
                                        ?>
                                        <div class="loading_img" style="text-align: center;display:none;">
                                            <img src="<?=$this->webroot?>app/webroot/images/loader12.gif" />
                                        </div>

                                    </div>
                                </div>
                            </div>
<!--
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" onclick="click_test('web_sip_capture',this);" data-parent="#accordion" href="#collapse-3">
                                        <?=__('SIP Capture Test')?>
                                    </a>
                                </div>
                                <div style="height: 0px;" id="collapse-3" class="accordion-body collapse">
                                    <div class="accordion-inner">

                                        <?php
                                        if(count($sips) == 0){
                                            ?>
                                            <center><div class="msg"><?php  echo __('no_data_found') ?></div></center>
                                        <?php
                                        }else{
                                            ?>

                                            <table class=" table  tableTools table-bordered  table-condensed table-primary" >
                                                <thead>
                                                <tr>
                                                    <th class="center"><?php __('IP')?></th>
                                                    <th><?php __('Port')?></th>
                                                    <th><?php __('Status')?></th>
                                                    <th><?php __('Action')?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach($sips as $sip){
                                                    if(empty($sip[0]['sip_capture_ip'])){
                                                        continue;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="center"  ><?=$sip[0]['sip_capture_ip']?></td>
                                                        <td  ><?=$sip[0]['sip_capture_port']?></td>
                                                        <td><span class="status_span"></span></td>
                                                        <td>
                                                            <input onclick="get_msg_sip(<?=$sip[0]['id']?>,this);" class="btn btn-primary test_in web_sip_capture" type="submit" value="<?php __('Verify Again')?>">
                                                        </td>
                                                    </tr>

                                                <?php
                                                }
                                                ?>

                                                </tbody>
                                            </table>
                                        <?php
                                        }
                                        ?>
                                        <div class="loading_img" style="text-align: center;display:none;">
                                            <img src="<?=$this->webroot?>app/webroot/images/loader12.gif" />
                                        </div>

                                    </div>
                                </div>
                            </div>
-->
                            <!--div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" onclick="click_test('redis',this);" data-parent="#accordion" href="#collapse-4">
                                        <?=__('Redis Server Test')?>
                                    </a>
                                </div>
                                <div style="height: 0px;" id="collapse-4" class="accordion-body collapse">
                                    <div class="accordion-inner">
                                        <table class=" table  tableTools table-bordered  table-condensed table-primary" >
                                            <thead>
                                            <tr>
                                                <th class="center"><?php __('IP')?></th>
                                                <th><?php __('Port')?></th>
                                                <th><?php __('Status')?></th>
                                                <th><?php __('Action')?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="center" ondblclick="edit_ip(this,'redis');" ><?=$redis_ip?></td>
                                                <td  ondblclick="edit_port(this,'redis');" ><?=$redis_port?></td>
                                                <td><span class="status_span"></span></td>
                                                <td>
                                                    <input onclick="get_msg('redis',this);" class="btn btn-primary test_in redis" type="submit" value="<?php __('Verify Again')?>">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <div class="loading_img" style="text-align: center;display:none;">
                                            <img src="<?=$this->webroot?>app/webroot/images/loader12.gif" />
                                        </div>

                                    </div>
                                </div>
                            </div-->


                            <!--div class="accordion-group">
                                <div class="accordion-heading">
                                   <a class="accordion-toggle" data-toggle="collapse" onclick="click_test('script_file',this);" data-parent="#accordion" href="#collapse-2">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-5">
                                        <?=__('Script Permissions Test')?>
                                    </a>
                                </div>
                                <div style="height: 0px;" id="collapse-5" class="accordion-body collapse">
                                    <div class="accordion-inner">
                                        <table class=" table  tableTools table-bordered  table-condensed table-primary" >
                                            <thead>
                                            <tr>
                                                <th class="center"><?php __('File Name')?></th>
                                                <th><?php __('Permissions')?></th>
                                                <th><?php __('Action')?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($files as $file){
                                                ?>
                                                <tr>
                                                    <td class="center"><?=$file['file']?></td>
                                                    <td><span class="status_span"><?=$file['msg']?></span></td>
                                                    <td>
                                                        <input onclick="get_msg('<?=$file['file']?>',this);" class="btn btn-primary test_in script_file" type="submit" value="<?php __('Verify Again')?>">
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>


                                            </tbody>
                                        </table>

                                        <div class="loading_img" style="text-align: center;display:none;">
                                            <img src="<?=$this->webroot?>app/webroot/images/loader12.gif" />
                                        </div>

                                    </div>
                                </div>
                            </div-->



                            <!-- // Accordion Item END -->
                            <!-- Accordion Item -->

                            <!-- // Accordion Item END -->


                        </div>
                        <!-- // Tab content END -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $("#init_web_switch").click();
    })
    function click_test(class_name,obj)
    {
        var click_div_height = $(obj).parent().next().css('height');
        if(click_div_height == '0px')
        {
            var class_obj = "."+class_name;
            $(class_obj).each(function(){
            $(this).click();
            });
        }
    }

    function set_777(obj){
        return false;
        var va = $.trim($(obj).parents('tr').eq(0).find('td').eq(0).html());

        var old_va = $(obj).parents('td').html();
        var td = $(obj).parents('td').eq(0).find('.status_span');
        $.ajax({
            'url':"<?=$this->webroot.'configs/change_pri'?>",
            'type':'post',
            'dataType':'json',
            'data':{'value':va},
            'beforeSend':function(){
                td.html("<img src='<?=$this->webroot?>app/webroot/images/loader12.gif' />");
            },
            'success':function (data){
                if(data != '' && data != null){
                    if(data['status'] == 'yes'){
                        td.html(data['msg']);
                        jGrowl_to_notyfy(data['msg'],{theme:'jmsg-success'});
                    }else{
                        jGrowl_to_notyfy(data['msg'],{theme:'jmsg-alert'});
                        td.html(old_va);
                    }
                }
            },
            'error': function(XMLHttpRequest, textStatus, errorThrown) {
                if(XMLHttpRequest.status == 500){

                }
                $(obj).parents('td').eq(0).html(old_va);
            }
        });



    }


    function edit_ip(obj,type){
        var ip = $.trim($(obj).html());
        if($(obj).find('input').length == 0){
            $(obj).html("<input type='text' value='"+ip+"'>  <a tou='"+type+"' msg='"+ip+"' style='margin-left:20px' title='Save' onclick='do_save(this,\"ip\");'  href='javascript:void(0);' ><i class='icon-save'></i></a><a msg='"+ip+"' title='Cancel' href='javascript:void(0);' onclick='do_not_update(this);'  style='margin-left:20px'   ><i class='icon-remove'></i></a> <img style='display:none;' src='<?=$this->webroot?>app/webroot/images/loader12.gif' /> ");
        }
    }

    function edit_port(obj,type){
        var ip = $.trim($(obj).html());
        if($(obj).find('input').length == 0){
            $(obj).html("<input type='text' value='"+ip+"'>  <a tou='"+type+"' msg='"+ip+"' style='margin-left:20px' title='Save' onclick='do_save(this,\"port\");'  href='javascript:void(0);' ><i class='icon-save'></i></a><a msg='"+ip+"' title='Cancel' href='javascript:void(0);' onclick='do_not_update(this);'  style='margin-left:20px'   ><i class='icon-remove'></i></a> <img style='display:none;' src='<?=$this->webroot?>app/webroot/images/loader12.gif' /> ");
        }
    }

    function do_save(obj,type){
        var va = $(obj).parents('td').eq(0).find('input').val();
        var tou = $(obj).attr('tou');

        $.ajax({
            'url':"<?=$this->webroot.'configs/change_config'?>",
            'type':'post',
            'dataType':'json',
            'data':{'type':type,'value':va,'tou':tou},
            'beforeSend':function(){
                $(obj).parents('td').eq(0).find('input').hide();
                $(obj).parents('td').eq(0).find('a').hide();
                $(obj).parents('td').eq(0).find('img').show();
            },
            'success':function (data){
                if(data != '' && data != null){

                    if(data['status'] == 'yes'){
                        $(obj).parents('td').eq(0).html(va);
                        jGrowl_to_notyfy(data['msg'],{theme:'jmsg-success'});
                    }else{
                        jGrowl_to_notyfy(data['msg'],{theme:'jmsg-alert'});
                        $(obj).parents('td').eq(0).find('input').show();
                        $(obj).parents('td').eq(0).find('a').show();
                        $(obj).parents('td').eq(0).find('img').hide();
                    }
                }
            },
            'error': function(XMLHttpRequest, textStatus, errorThrown) {
                if(XMLHttpRequest.status == 500){

                }
                $(obj).parents('td').eq(0).find('input').show();
                $(obj).parents('td').eq(0).find('a').show();
                $(obj).parents('td').eq(0).find('img').hide();
            }
        });



    }

    function do_not_update(obj){

        var va = $(obj).attr('msg');
        $(obj).parents('td').eq(0).html(va);

    }


    function get_msg_lan(id,obj){

        $.ajax({
            'url':"<?=$this->webroot.'configs/test_config_lan'?>",
            'type':'post',
            'dataType':'json',
            'data':{'id':id},
            'beforeSend':function(){
                $(obj).parents('table').eq(0).parent().find('table').hide();
                $(obj).parents('table').eq(0).parent().find('.loading_img').show();
            },
            'success':function (data){
                if(data != '' && data != null){
                    $(obj).parents('table').eq(0).parent().find('table').show();
                    $(obj).parents('table').eq(0).parent().find('.loading_img').hide();
                    if(data['status'] == 'yes'){
                        $(obj).parents('tr').eq(0).find('.status_span').html("OK");
                    }else{
                        $(obj).parents('tr').eq(0).find('.status_span').html(data['msg']);
                    }
                }
            },
            'error': function(XMLHttpRequest, textStatus, errorThrown) {
                if(XMLHttpRequest.status == 500){

                }
                $(obj).parents('table').eq(0).parent().find('table').show();
                $(obj).parents('table').eq(0).parent().find('.loading_img').hide();
            }
        });
    }

    function get_msg_sip(id,obj){

        $.ajax({
            'url':"<?=$this->webroot.'configs/test_config_sip'?>",
            'type':'post',
            'dataType':'json',
            'data':{'id':id},
            'beforeSend':function(){
                $(obj).parents('table').eq(0).parent().find('table').hide();
                $(obj).parents('table').eq(0).parent().find('.loading_img').show();
            },
            'success':function (data){
                if(data != '' && data != null){
                    $(obj).parents('table').eq(0).parent().find('table').show();
                    $(obj).parents('table').eq(0).parent().find('.loading_img').hide();
                    if(data['status'] == 'yes'){
                        $(obj).parents('tr').eq(0).find('.status_span').html("OK");
                    }else{
                        $(obj).parents('tr').eq(0).find('.status_span').html(data['msg']);
                    }
                }
            },
            'error': function(XMLHttpRequest, textStatus, errorThrown) {
                if(XMLHttpRequest.status == 500){

                }
                $(obj).parents('table').eq(0).parent().find('table').show();
                $(obj).parents('table').eq(0).parent().find('.loading_img').hide();
            }
        });
    }

    function get_msg(type,obj){

        $.ajax({
            'url':"<?=$this->webroot.'configs/test_config'?>",
            'type':'post',
            'dataType':'json',
            'data':{'type':type},
            'beforeSend':function(){
                $(obj).parents('table').eq(0).parent().find('table').hide();
                $(obj).parents('table').eq(0).parent().find('.loading_img').show();
            },
            'success':function (data){
                if(data != '' && data != null){
                    $(obj).parents('table').eq(0).parent().find('table').show();
                    $(obj).parents('table').eq(0).parent().find('.loading_img').hide();
                    if(data['status'] == 'yes'){
                        $(obj).parents('tr').eq(0).find('.status_span').html("OK");
                    }else{
                        $(obj).parents('tr').eq(0).find('.status_span').html(data['msg']);
                    }
                }
            },
            'error': function(XMLHttpRequest, textStatus, errorThrown) {
                if(XMLHttpRequest.status == 500){

                }
                $(obj).parents('table').eq(0).parent().find('table').show();
                $(obj).parents('table').eq(0).parent().find('.loading_img').hide();
            }
        });

    }



</script>