<script>

    var descriptions = {
    <?php foreach($products as $product) {
        echo $product['ProductRouteRateTable']['id'] . ':' . '\'' . $product['ProductRouteRateTable']['description'] . '\',' . "\r\n";
    }
    ?>
    };
</script>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
     <?php if($_SESSION['login_type'] == 3):?>
        <li><?php __('Client Portal') ?></li>
    <?php else:?>
        <li><?php __('Management') ?></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Product') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Product') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<?php
$showButtonFlag = false;
foreach($products as $product) {
    if($product['ProductRouteRateTable']['product_name'] == "Public" || $product['ProductRouteRateTable']['product_name'] == "Private") {
        $showButtonFlag = true;
        break;
    }
}
?>
<?php if((isset($_SESSION['new_user']) && $_SESSION['new_user'] == false) && $showButtonFlag): ?>
<div class="buttons pull-right newpadding">
    <a href="#myModal_add_trunk" data-toggle="modal" class="btn btn-primary btn-icon glyphicon glyphicon-plus " title=""><i class="icon-plus"></i> <?php __('Build Trunk'); ?></a>
</div>
<?php endif; ?>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php
            $count = count($this->data);
            if (!$count):
                ?>
                <h2 class="msg center"><br /><?php __('No data found'); ?></h2>
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded hide">
                    <thead>
                    <tr>
                        <th><?php __('Product Name'); ?></th>
                        <th><?php __('Tech Prefix'); ?></th>
                        <th><?php __('Product Description'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            <?php else: ?>
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('ProductRouteRateTable.product_name', __('Product Name', true)) ?></th>
                        <th><?php __('Tech Prefix'); ?></th>
                        <th><?php __('Product Description'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->data as $k => $item)
                    {
                        ?>
                        <tr class="product_item">
                            <td class="product_id" data-value="<?php echo $item['ProductRouteRateTable']['id'] ?>"><?php echo $item['ProductRouteRateTable']['product_name'] ?></td>
                            <td><?php echo $item['ProductRouteRateTable']['tech_prefix'] ?></td>
                            <td><?php echo $item['ProductRouteRateTable']['description'] ?>
                                <!-- <select name="sip_ip" class="sip_ip">
                                    <?php// foreach($voip_arr as $val):?>
                                        <option value="<?php// echo $val[0]['sip_ip'].":".$val[0]['sip_port']?>"><?php// echo $val[0]['sip_ip'].":".$val[0]['sip_port']?></option>
                                    <?php// endforeach; ?>
                                </select> -->
                            </td>
                            <td>
                                <a data-toggle="modal" href="#myModal_ip<?php echo $item['ProductRouteRateTable']['id']; ?>" title="Specify Your IPs">
                                    <i class="icon-list"></i>
                                </a>
                                <a target="_blank" href="<?php echo $this->webroot?>clients/download_rate/<?php echo base64_encode($item['ProductRouteRateTable']['rate_table_id']);?>" title="<?php __('Download Rate'); ?>"><i class="icon-download"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>



        <div class="clearfix"></div>
    </div>
</div>

<!--   -->
<form action="" id="myModal_add_trunk_form" method="post">
    <div id="myModal_add_trunk" style="max-height: 80%; overflow-y: auto;" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Build Trunk'); ?></h3>
        </div>
        <div class="separator"></div>
        <div class="widget-body">
            <!-- <input type="hidden" id="myModal_product_id" name="myModal_product_id" value=""/> -->
<!--            <input type="hidden" id="myModal_sip_ip" name="myModal_sip_ip" value=""/>-->
<!--            <div>-->
<!--                <div class="text-center">-->
<!--                    <h4 style="font-size: 14px">Trunk Name</h4>-->
<!--                </div>-->
<!--                <div class="text-center">-->
<!--                    <input type="text" id="myModal_trunk_name" class="validate[required,custom[onlyLetterNumberLineSpace]]" name="myModal_trunk_name" value=""/>-->
<!--                </div>-->
<!--            </div>-->

            <div>
                <div style="margin-top:20px;margin-bottom: 20px;">
                    <a href="#" onclick="return false;" class="btn btn-primary btn-icon glyphicons circle_plus a_myModal_add_product" id="addProduct"><i></i>Add Product </a>
                </div>
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                    <tr>
                        <th class="baidiv">Product Name</th>
                        <th class="baidiv">Product Description</th>
                        <th class="baidiv">Action</th>
                    </tr>
                    </thead>
                    <tbody id="clone_body2">
                    <tr class="clone2">

                        <td class="value baidiv">
                            <select id="product-name" name="myModal_product_id[]">
                                <?php foreach ($this->data as $k => $item)  { ?>
                                    <option value="<?php echo $item['ProductRouteRateTable']['id'] ?>">
                                        <?php echo $item['ProductRouteRateTable']['product_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>

                        <td class="value baidiv">
                            <input type="text" readonly name="myModal_sip_ip[]" id="product-description">
<!--                            <select name="myModal_sip_ip[]">-->
<!--                            --><?php //foreach($products as $product):?>
<!--                                    <option value="--><?php //echo $product['ProductRouteRateTable']['description']?><!--">--><?php //echo $product['ProductRouteRateTable']['description']?><!--</option>-->
<!--                                --><?php //endforeach; ?>
<!--                            </select>-->
                        </td>

                        <td>
                            <a onclick="return false;" href="#" class="del_myModal_add_product"><i class="icon-remove"></i></a>
                        </td>

                    </tr>
                    </tbody>

                </table>
            </div>

            <div style="margin-top:20px;margin-bottom: 20px;">
                <a href="#" onclick="return false;" class="btn btn-primary btn-icon glyphicons circle_plus a_myModal_add_trunk" id="addHost"><i></i>Add Host </a>
            </div>
            <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                <thead>
                <tr>
                    <th class="baidiv">IP</th>
                    <th class="baidiv">Netmask</th>
                    <th class="baidiv">Port</th>
                    <th class="baidiv">Action</th>
                </tr>
                </thead>
                <tbody id="clone_body">
                <tr class="clone">

                    <td class="value baidiv">
                        <input type="text" value="" id="ip" class="add_trunk_ip validate[required]" name="accounts[ip][]" style="width: 100px;" onkeyup="value = value.replace(/[^\w\.\/]/ig, '')">
                    </td>
                    <td class="baidiv">
                        <select id="GatewaygroupNeedRegister" style="width: 100px;" class="nohei" name="accounts[need_register][]">
                            <option value="32">32</option>
                            <option value="31">31</option>
                            <option value="30">30</option>
                            <option value="29">29</option>
                            <option value="28">28</option>
                            <option value="27">27</option>
                            <option value="26">26</option>
                            <option value="25">25</option>
                            <option value="24">24</option>
                        </select>
                    </td>
                    <td class="value baidiv">
                        <input type="text" value="5060" maxlength="16" id="port" class="add_trunk_port validate[required]"  name="accounts[port][]">
                    </td>
                    <td><a onclick="return false;" href="#" class="del_myModal_add_trunk"><i class="icon-remove"></i></a></td>
                </tr>
                </tbody>

            </table>
        </div>
        <div class="modal-footer">
            <input type="button" id="myModal_add_trunk_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" id="myModal_add_trunk_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>


<?php foreach($resource_ip_arr as $product_id => $resource_ip_row) :?>
	<div id="myModal_ip<?php echo $product_id; ?>" class="modal hide">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">&times;</button>
			<h3><?php echo __('Product',true)."[".$pr_name[$product_id]."] IP"; ?></h3>
		</div>
		<div class="modal-body">
		        <div class="buttons pull-right newpadding">
                    <a table_type="1" tbody_id = "tbody<?php echo $product_id; ?>" class="link_btn btn btn-primary btn-icon glyphicons circle_plus ip_add_btn" href="javascript:void(0)">
                        <i></i>
                        <?php __('Create new'); ?>
                    </a>
                </div>
				<table class="table table-bordered table-primary" resource_id = "<?php echo isset($resource_ip_row[0][0]['resource_id']) ? $resource_ip_row[0][0]['resource_id'] : ''; ?>" product_name="<?php echo $pr_name[$product_id];?>" product_id="<?php echo $product_id;?>" >
					<thead>
					<tr>
						<th><?php __('IP'); ?></th>
						<th><?php __('Port'); ?></th>
						<th><?php __('Call Limit'); ?></th>
						<th><?php __('CPS Limit'); ?></th>
						<th><?php __('Action'); ?></th>
					</tr>
					</thead>
					<tbody id="tbody<?php echo $product_id; ?>">
					<?php if(isset($resource_ip_row[0]['resource_ip']) && $resource_ip_row[0]['resource_ip']):
					    foreach ($resource_ip_row[0]['resource_ip'] as $key =>$resource_ip_item):
					     ?>
						<tr>
							<td><?php echo $resource_ip_item[0]['ip']; ?></td>
							<td><?php echo $resource_ip_item[0]['port']; ?></td>
							<td><?php echo $resource_ip_item[0]['capacity']; ?></td>
							<td><?php echo $resource_ip_item[0]['cps']; ?></td>
							<td>
								<a class="ip_edit_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" title="save" href="javascript:void(0)"><i class="icon-edit"></i></a>
								<a class="delete_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" href="javascript:void(0)" title="Delete">
									<i class="icon-remove"></i>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
		</div>
	</div>
<?php endforeach; ?>

<table class="hide">
	<tr class="ip_tr">
		<td><input type="text" name="ip"  class="width120 ip validate[required,custom[ipv4]]" /></td>
		<td><input type="text" name="port" class="width50 port validate[required,custom[integer]]" /></td>
		<td></td>
		<td></td>
		<td>
			<a class="ip_save_btn" re_ip_id="" title="Save" href="javascript:void(0)"><i class="icon-save"></i></a>
			<a onclick="$(this).closest('tr').remove();" href="javascript:void(0)" title="Delete">
				<i class="icon-remove"></i>
			</a>
		</td>
	</tr>
	<tr class="ip_tr_edit">
		<td><input type="text" name="ip"  class="width120 ip validate[required,custom[ipv4]]" /></td>
		<td><input type="text" name="port" class="width50 port validate[required,custom[integer]]" /></td>
		<td></td>
		<td>
			<a class="ip_save_btn" re_ip_id="" title="Save" href="javascript:void(0)"><i class="icon-save"></i></a>
			<a class="save_cancel" href="javascript:void(0)" title="Cancel">
				<i class="icon-remove"></i>
			</a>
		</td>
	</tr>
</table>

<!--  -->
<script type="text/javascript">

    function ChangeProductNameEventHandler(el)
    {
        let selected = $(el).val();
        $(el).parent().next().find('input').val(descriptions[selected]);
//        $("#product-description").val(descriptions[selected]);
    }

    $(function() {
        //var trunkClone = $('.clone').clone(true);
        //var prodClone = $('.clone2').clone(true);
        $('.item_to_modal').click(function(){
            // $('#myModal_product_id').val($(this).parents('.product_item').find('.product_id').data('value'));
            // $('#myModal_sip_ip').val($(this).parents('.product_item').find('.sip_ip').val());
        });
        $('.a_myModal_add_trunk').click(function(){
            var clone = $('.clone').clone(true);
            clone.clone(true).first().appendTo('#clone_body');
        });

        $('.del_myModal_add_trunk').click(function(){
            if($('.clone').length > 1){

                $(this).parents('.clone').remove();
            }
        });

        $('.a_myModal_add_product').click(function(){
            var clone = $('.clone2').clone(true);
            clone.clone(true).first().appendTo('#clone_body2');
        });
        $('.del_myModal_add_product').click(function(){
            if($('.clone2').length > 1){

                $(this).parents('.clone2').remove();
            }
        });

        $("#myModal_add_trunk_submit").click(function(){
            var validate = $("#myModal_add_trunk_form").validationEngine('validate');

            var alias = $("#myModal_trunk_name").val();

            var is_ok = true;
            $.ajax({
                url : "<?php echo $this->webroot?>product_management/ajax_check_alias",
                data : "alias=" + alias,
                async : false,
                success : function(data){
                    is_ok = data;

                }
            });

            if(is_ok == 'false') {

                $('#myModal_trunk_name').before('<div class="ipformError parentFormmyModal_add_trunk_form formError" style="opacity: 0.87; position: absolute; top: 85px; left: 340px; margin-top: -40px;"><div class="formErrorContent">* Trunk name['+alias+'] is already in use!<br></div><div class="formErrorArrow"><div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div></div></div>');
                return false;
            }

            var flg = false;
            var ips_arr = new Array();
            $('.clone').each(function(k){
                var ip = $(this).find('.add_trunk_ip').val();
                var port = $(this).find('.add_trunk_port').val();
                var str = ip + ':' + port;
                if(ips_arr.indexOf(str) !== -1){
                    flg = true;
                } else {
                    ips_arr[k] = str;
                }
            });
            if(flg) {

                showMessages_new("[{'code':1,'field':'','msg':'Trunk IP and Port is duplicate!'}]");
                return false;
            }

            if(validate){
                $("#myModal_add_trunk_form").submit();
            }

            return false;
        });

        $("#product-name").change(function () {
            ChangeProductNameEventHandler(this);
        });

    });

    $(document).ready(function () {
        let handleProduct = $("#product-name");
        ChangeProductNameEventHandler(handleProduct);
    });

</script>



<script type="text/javascript">
	$(function(){

		var ip_tr = $(".ip_tr").eq(0).remove();
		var ip_tr_edit = $(".ip_tr_edit").eq(0).remove();
		$(".ip_add_btn").click(function(){
			var $tbody_id = $(this).attr('tbody_id');
			var $table_type = $(this).attr('table_type');
			ip_tr.clone(true).prependTo("#"+$tbody_id);
		});

		$(".delete_btn").live('click',function(){
			var re_ip_id = $(this).attr('re_ip_id');
			var $this = $(this);
			var $this_div = $this.parent().parent().parent().parent().parent().parent();
			$this_div.hide();
			bootbox.confirm('<?php __('sure to delete'); ?>', function(result) {
				if(result) {
					$.ajax({
						'url': '<?php echo $this->webroot ?>clients/ajax_delete_resource_ip',
						'type': 'POST',
						'dataType': 'json',
						'data': {'re_ip_id': re_ip_id},
						'success': function(data) {
							if(data.flg)
							{
								jGrowl_to_notyfy('<?php __('succeed'); ?>',{theme:'jmsg-success'});
								$this.closest('tr').remove();
							}
							else
								jGrowl_to_notyfy('<?php __('failed'); ?>',{theme:'jmsg-error'});
							$this_div.show();
						}
					});
				}
				else
				{
					$this_div.show();
				}
			});

		});

		$(".save_cancel").live('click',function(){
			$(this).closest('tr').next().show();
			$(this).closest('tr').remove();
		});




		$(".ip_save_btn").live('click',function(){
			var ip = $(this).closest('tr').children().eq(0).children().eq(0).val();
			var port = $(this).closest('tr').children().eq(1).children().eq(0).val();
			var clear_tr = $(this).closest('tr').next().length?$(this).closest('tr').next():$(this).closest('tr');
			var resource_id = $(this).closest('table').attr('resource_id');
			var product_id = $(this).closest('table').attr('product_id');
			var product_name = $(this).closest('table').attr('product_name');
			var client_id = '<?php echo isset($client_id)?$client_id:'';?>';
			var re_ip_id = $(this).attr('re_ip_id');
			var flg1 = $(this).closest('tr').find('.ip').validationEngine('validate');
			var flg2 = $(this).closest('tr').find('.port').validationEngine('validate');
			if (flg1 || flg2)
				return false;
			var $this = $(this);
			$.ajax({
				'url': '<?php echo $this->webroot ?>clients/ajax_save_resource_ip',
				'type': 'POST',
				'dataType': 'json',
				'data': {'ip': ip, 'port': port,'type':'ip',resource_id:resource_id,re_ip_id:re_ip_id,product_id:product_id,product_name:product_name, client_id:client_id},
				'success': function(data) {
					if (!data.flg) {
						var msg = data.msg;
						jGrowl_to_notyfy(msg,{theme:'jmsg-error'});
					} else {

						var clone_result = clear_tr.clone(true);
						clone_result.children().eq(0).html(ip);
						clone_result.children().eq(1).html(port);
						clone_result.find('a').eq(0).attr('re_ip_id',data.re_ip_id);
						clone_result.find('a').eq(1).attr('re_ip_id',data.re_ip_id);
						$this.closest('tr').before(clone_result);
						clone_result.show();
						$this.closest('tr').remove();
						jGrowl_to_notyfy('IP is created successfully!',{theme:'jmsg-success'});
					}

				}
			});
		});


		$(".ip_edit_btn").live('click',function(){
			var re_ip_id = $(this).attr('re_ip_id');
			var $this = $(this);
			var hide_tr = $this.closest('tr');
			var ip = hide_tr.children().eq(0).html();
			var port = hide_tr.children().eq(1).html();
			var closest_tbody = $this.closest('tbody');
			ip_tr_edit.children().eq(0).find('input').val(ip);
			ip_tr_edit.children().eq(1).find('input').val(port);
			ip_tr_edit.find('a').eq(0).attr('re_ip_id',re_ip_id);
			hide_tr.before(ip_tr_edit.clone(true));
			hide_tr.hide();
		});

	})
</script>