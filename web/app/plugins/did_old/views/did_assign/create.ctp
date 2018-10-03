<style type="text/css" >
    .width5{width:5px;background-color: #EFEFEF;}
    body #myform tr td input {margin-bottom: 0px;}
    .red{background-color: rgb(229,65,45);color: #ffffff;font-size: 14px;font-weight: 600;}
    #inner{height: 28px;padding-top: 8px;padding-left: 10px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Client DID Assignment') ?></li>
</ul>
<div class="heading-buttons">
    <h1><?php echo __('Client DID Assignment') ?></h1>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <div style="margin:0 auto;">

                <form method="post" id="myform">
                    <table class="table dynamicTable tableTools table-bordered  table-white" style="background-color:#F4F4F4;">
                        <tbody>
                        <tr>
                            <td><?php __('Country')?>:</td>
                            <td>
                                <select id="countries">
                                    <option><?php __('Select')?>...</option>
                                    <?php foreach ($countries as $country): ?>
                                        <option value="<?php echo $country[0]['country']; ?>"><?php echo $country[0]['country']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="triggers"><?php __('Organized By')?>:</td>
                            <td class="triggers">
                                <select id="triggers">
                                    <option><?php __('Select')?>...</option>
                                    <option><?php __('By State/Region')?></option>
                                    <option><?php __('By Area Code')?></option>
                                    <option><?php __('By LATA')?></option>
                                </select>
                            </td>
                            <td></td>
                            <td></td>

                            <td><?php __('Number')?>:</td>
                            <td><input type="text" id="number" /></td>
                        </tr>
                        <tr id="addtional_query" style="display:none;">
                            <td><?php __('Rate Center')?>:</td>
                            <td><input type="text" id="rate_center" /></td>
                            <td><?php __('State/Province')?>:</td>
                            <td><input type="text" id="state_province" /></td>
                            <td><?php __('Area Code')?>:</td>
                            <td><input type="text" id="area_code" /></td>
                            <td><?php __('LATA')?>:</td>
                            <td><input type="text" id="lata" /></td>
                        </tr>
                        <tr style="text-align:center;">
                            <td colspan="8" class="button-groups center ">
                                <input type="submit" id="subbtn" class="btn btn-primary trigger_btn" value="<?php __('Search')?>">
                            </td>
                        </tr>
                        </tbody>

                    </table>

                </form>
                <div id="did_display">
                    <h4></h4>
                    <ul>
                    </ul>
                </div>
                <div style="padding:5px;text-align:right">
                    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="mass_assign_btn" href="#myModal_DidAssign" data-toggle="modal">
                        <i></i> <?php __('Mass Assign'); ?>
                    </a>
                </div>
                <table id="did_listing" class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php __('DID')?></th>
                        <th><?php __('Rate Center')?></th>
                        <th><?php __('State')?></th>
                        <th><?php __('LATA')?></th>
                        <th><?php __('Billing Rule')?></th>
                        <th><?php __('Date Assign')?></th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
<!--                    <tfoot>-->
<!--                    <tr>-->
<!--                        <td colspan="7" style="text-align:center;">-->
<!--                            <a id="prev-page" href="javascript:void(0)">-->
<!--                                <i class="icon-chevron-left"></i>-->
<!--                            </a>&nbsp;&nbsp;&nbsp;&nbsp;-->
<!--                            <a id="next-page" href="javascript:void(0)">-->
<!--                                <i class="icon-chevron-right"></i>-->
<!--                            </a>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    </tfoot>-->
                </table>
                <div class="row-fluid separator" id="page_li">
                </div>
                <div class="clearfix"></div>
                <div id="loading"></div>
            </div>
        </div>
    </div>
</div>
<!-- did assign start -->
<div id="myModal_DidAssign" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Client DID Assignment'); ?></h3>
    </div>
    <div class="modal-body">
        <input type="hidden" name="did_assign_number" id="did_assign_number" />
        <table class="table dynamicTable tableTools table-bordered  table-white">
            <td><?php __('Assign to')?></td>
            <td>
                <?php if(empty($selected_egress_id)): ?>
                    <select name="egress_id" id="egress_id">
                        <?php foreach ($egresses as $key => $egress): ?>
                            <option value="<?php echo $key; ?>">
                                <?php echo $egress ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <a title="Create New Client" class="create_new_client" href="#myModal_CreateClient" data-toggle="modal">
                        <i class="icon-plus"></i>
                    </a>
                <?php else: ?>
                    <?php echo $egresses[$selected_egress_id]; ?>
                    <input type="hidden"  name="egress_id" id="egress_id" value="<?php echo $selected_egress_id; ?>" />
                <?php endif; ?>
            </td>
        </table>
    </div>
    <div class="modal-footer">
        <input type="button" id="did_assign_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" id="did_assign_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>
<!-- did assign end -->

<!-- create client start -->
<div id="myModal_CreateClient" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Create Client'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="button" id="create_client_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" id="create_client_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>
<!-- create client end -->
<script type="text/javascript" src="<?php echo $this->webroot ?>js/sprintf.js"></script>
<script type="text/javascript">
    function refresh_clients() {
        $.ajax({
            'url': '<?php echo $this->webroot; ?>did/wizard/get_clients',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $("#egress_id").empty();
                $.each(data, function(index, item) {
                    $("#egress_id").append('<option value="' + item[0]['resource_id'] + '">' + item[0]['alias'] + '</option>');
                });
            }
        });
    }
    $(function() {
        var $triggers = $('#triggers');
        var $pager = $("a[class*='page']");
        var $trigger_button = $('.trigger_btn');
        var $rate_center = $('#rate_center');
        var $locality = $('#locality');
        var $state_province = $('#state_province');
        var $area_code = $('#area_code');
        var $lata = $('#lata');
        var $loading = $('#loading');
        var $did_display = $('#did_display');
        var $did_item = $('.did_item');
        var $did_listing = $('#did_listing');
        var $put_into_cart = $('.put_into_cart');
        var type = 0;
        var option = null;
        var $countries = $('#countries');
        var $egress_id = $('#egress_id');
        var $number = $('#number');
        var $addtional_query = $('#addtional_query');

        $("#myModal_CreateClient").on('shown',function(){
            var modal = $(this);
            modal.find('.modal-body').load('<?php echo $this->webroot; ?>did/clients/ajax_add_client/1');
        });
        $("#create_client_submit").click(function(){
            $.ajax({
                url: "<?php echo $this->webroot ?>did/clients/add/1/1",
                type: 'post',
                dataType: 'text',
                data: $('#myform_client').serialize(),
                success: function(data) {
                    if (data == 1)
                    {
                        $("#create_client_close").click();
                        refresh_clients();
                    }
                    else
                        jGrowl_to_notyfy('<?php __('Create failed'); ?>', {theme: 'jmsg-error'});
                }
            });
        });

        $("#did_assign_submit").click(function(){
            var egress_id = $("#egress_id").val();
            if(!egress_id){
                jGrowl_to_notyfy('<?php __('client can not empty'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            var number = $("#did_assign_number").val();
            if(!number){
                var $mass_assign_checked = $('.mass_assign_checked:checked');
                var numbers = new Array();
                $mass_assign_checked.each(function(index, item) {
                    numbers.push($(this).attr('control'));
                });
                if (numbers.length == 0){
                    jGrowl_to_notyfy('<?php __('Nothing is selected'); ?>', {theme: 'jmsg-error'});
                    return false;
                }
                number = numbers;
            }
//            console.log(number);
//            console.log('egress'+egress_id);
//            return false;
            $.ajax({
                'url':'<?php echo $this->webroot ?>did/did_assign/assign',
                'type':'POST',
                'dataType':'json',
                'data': {'numbers[]' : number, "egress_id": egress_id},
                'success': function(data) {
                    $loading.hide();
                    $(".trigger_btn").click();
                    $("#did_assign_close").click();
                    if (data.result){
                        jGrowl_to_notyfy("<?php __('Assign successfully'); ?>",{theme:'jmsg-success'});
                    }else{
                        jGrowl_to_notyfy('<?php __('Assign failed'); ?>', {theme: 'jmsg-error'});
                    }
                },
                'beforeSend' : function() {
                    $loading.show();
                }
            });
        });
        $(".put_into_cart").live('click',function(){
            var number = $(this).attr('control');
            $("#did_assign_number").val(number);
        });
        $("#mass_assign_btn").click(function(){
            var $mass_assign_checked = $('.mass_assign_checked:checked');
            if ($mass_assign_checked.size() == 0){
                jGrowl_to_notyfy('<?php __('Nothing is selected'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            $("#did_assign_number").val('');
        });


        $countries.change(function() {
            var country = $(this).val();
            if ('US' == $.trim(country) || 'us' == $.trim(country)) {
                $(".triggers").show();
                $triggers.show();
                $addtional_query.show();
            } else {
                $(".triggers").hide();
                $triggers.hide();
                $addtional_query.hide();
            }
        }).trigger('change');

        function put_data(data)
        {
            var $tbody = $did_listing.find('> tbody');
            $tbody.empty();
            $.each(data, function(index, value) {
                var $tr = $('<tr />');
                $tr.append('<td><input type="checkbox" class="mass_assign_checked" control="' + value[0]['number'] + '"/></td>');
                $tr.append('<td>' + value[0]['number'] + '</td>');
                $tr.append('<td>' + value[0]['rate_center'] + '</td>');
                $tr.append('<td>' + value[0]['state'] + '</td>');
                $tr.append('<td>' + value[0]['lata'] + '</td>');
                $tr.append('<td>' + value[0]['lata'] + '</td>');
                $tr.append('<td>' + value[0]['created_time'] + '</td>');
                $tr.append('<td><a href="#myModal_DidAssign" data-toggle="modal" title="Assign" class="put_into_cart" control="' + value[0]['number'] + '"><i class="icon-plus"></i></a></td>');
                $tbody.append($tr);
            });
            $did_listing.show();
        }

        $trigger_button.click(getData);

        var page = 1;

        function getData(event) {
            $did_listing.hide();
            var btn_val = $(event.target).val();

            option = new Object();
            var header = '';

            var $target = $(event.currentTarget);
            var idName = $target.attr('id');

//            if(idName == 'next-page') {
//                btn_val = 'Search';
//                page++;
//            } else if (idName == 'prev-page') {
//                btn_val = 'Search';
//                page--;
//                if (page < 1) {
//                    page = 1;
//                }
//            } else {
//                page = 1;
//                $pager.unbind('click');
//                $pager.click(getData);
//            }
            var tmp_page = $target.attr('page');
            if(tmp_page){
                btn_val = 'Search';
                page = tmp_page;
            }

            switch (btn_val)
            {
                case 'By State/Region':
                    type = 1;
                    header = 'DID Availability by State';
                    break;
                case 'By Area Code':
                    type = 2;
                    header = 'DID Availability by Area Code';
                    break;
                case 'By LATA':
                    type = 3;
                    header = 'DID Availability by LATA';
                    break;
                case 'Search':
                    type = 4;
                    option.number = $number.val();
                    option.rate_center = $rate_center.val();
                    option.locality = $locality.val();
                    option.state_province = $state_province.val();
                    option.area_code = $area_code.val();
                    option.lata = $lata.val();
                    header = 'DID Availability by Search';
                    break;
                default:
                    return;
            }

            option.page = page;

            $.ajax({
                'url':'<?php echo $this->webroot ?>did/orders/search/' + type,
                'type':'POST',
                'dataType':'json',
                'data':option,
                'success': function(data) {
                    $loading.hide();
                    $did_display.find('> h4').text(header);
                    var $ul = $did_display.find('> ul');
                    $ul.empty();
                    if (type != 4)
                    {
                        $.each(data, function(index, value) {
                            $ul.append('<li><a href="###" class="did_item">' + value[0]['name']+'</a>(' + value[0]['count'] + ')</li>');
                        });
                    }
                    else
                    {
                        var table_data = data.result;
                        put_data(table_data);
                        $('tfoot', $did_listing).show();
                        create_page_li(data);
                    }
                },
                'beforeSend' : function() {
                    $loading.show();
                }
            });

            return false;

        }

        $triggers.change(getData);

        $("#page_li").find('a').live('click',getData);

        function create_page_li(data){
            var page_now = data.page_now;
            var total_pages = data.total_pages;
            $("#page_li").load('<?php echo $this->webroot; ?>did/did_assign/ajax_page',{'page_now': page_now,'total_pages':total_pages});
        }



        function get_data_group(event) {
            $("#page_li").hide();
            //var $this = $(this);
            var text = $('.item_clicked').text();

            option.text = text;

            var $target = $(event.currentTarget);
            var idName = $target.attr('id');

            if(idName == 'next-page') {
                page++;
            } else if (idName == 'prev-page') {
                page--;
                if (page < 1) {
                    page = 1;
                }
            } else {
                page = 1;
            }

            option.page = page;

            $.ajax({
                'url':'<?php echo $this->webroot ?>did/orders/search_listing/' + type,
                'type':'POST',
                'dataType':'json',
                'data':option,
                'success': function(data) {
                    $loading.hide();
                    put_data(data);
                },
                'beforeSend' : function() {
                    $loading.show();
                }
            });

            return false;
        }

        $did_item.live('click', function (event) {
            var $this = $(this);
            $('a', '#did_display').removeClass('item_clicked');
            $this.addClass('item_clicked');
            get_data_group(event);
            $pager.unbind('click');
            $pager.click(get_data_group);
        });

    });
</script>