<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Table') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Assign Rate Deck') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Assign Rate Deck') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons settings" onclick="assignSelected('assign_table', '<?php echo $this->webroot ?>rates/assign_selected?<?php echo 'rate_table_id=' . $this->params['pass'][0] . '&' . $this->params['getUrl']?>', 'seleted');"  href="###"><i></i> <?php echo __('Assign Selected') ?></a>
<!--    <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons settings"  href="###"><i></i> --><?php //echo __('Assign All') ?><!--</a>-->
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
         <div class="filter-bar">
          <form method="get" name="myform">
              <div>
                  <?php __('Carrier')?>:
                  <select name="client_id" >
                      <option value="0"></option>
                      <?php if (!empty($this->data)): ?>
                      <?php foreach ($this->data as $item): ?>
                       <option value="<?php echo isset($item['client']['client_id'])?$item['client']['client_id']:'';?>"><?php echo isset($item['client']['name'])?$item['client']['name']:'';?></option>
                      <?php endforeach; ?>
                      <?php endif; ?>
                  </select>
                  <?php __('Ingress')?>:
                   <select name="resource_id" >
                        <option value="0"></option>
                        <?php if (!empty($this->data)): ?>
                        <?php foreach ($this->data as $item): ?>
                         <option value="<?php echo isset($item['resource']['resource_id'])?$item['resource']['resource_id']:'';?>"><?php echo isset($item['resource']['alias'])?$item['resource']['alias']:'';?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                   </select>
                  <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
              </div>
          </form>
         </div>
            <?php if (!empty($this->data)): ?>
                <div class="clearfix"></div>
                <div class="scroll_div overflow_x">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                        <thead>
                        <tr>
                            <th><input type="checkbox" onclick="checkAllOrNot(this, 'assign_table');" value=""/></th>
                            <th><?php echo $appCommon->show_order('client.name', __('Carrier', true)); ?></th>
                            <th><?php echo $appCommon->show_order('resource.alias',__('Ingress', true)); ?></th>
                            <th><?php echo __('Prefix', true); ?></th>
                            <th ><?php echo __('Current Rate Table', true); ?></th>
                            <th ><?php echo __('Current Rate Deck', true); ?></th>
                        </tr>
                        </thead>
                        <tbody id="assign_table">
                        <?php foreach ($this->data as $item): ?>
                            <tr class="row-1">
                                <td><input type="checkbox" value="<?php echo $item['ResourcePrefix']['id'] ?>" <?php if(in_array($item['ResourcePrefix']['id'],$selected)) echo 'checked="checked"'?>/></td>
                                <td><?php echo $item['client']['name'] ?></td>
                                <td class="ingress"><?php echo $item['resource']['alias'] ?></td>
                                <td class="prefix"><?php echo $item['ResourcePrefix']['tech_prefix'] ?></td>
                                <td class="rate_table_name"><?php echo $current_rate_table; ?></td>
                                <td class="current_rate_table"></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php else: ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
            <?php endif; ?>
        </div>
    </div>
</div>




<script type="text/javascript">

    function assignSelected(tabid, url, msg) {
        var ids = '',
            chx = document.getElementById(tabid).getElementsByTagName("input"),
            loop = chx.length,
            html = "",
            trunk = "",
            prefix = "",
            rate_table_name = $(".rate_table_name").text(),
            msg = "<h4>Please confirm assigning rate table [ "+rate_table_name+" ] to the following ingress trunks:</h4>";

        html += msg + "<br><table class='list footable table table-striped table-bordered table-primary default footable-loaded dataTable floatThead-table'><tbody>";
        html += "<thead><tr><th>Trunk</th><th>Prefix</th></tr></thead>";
        for (var i = 0; i < loop; i++) {
            var c = chx[i];
            if (c.type == "checkbox") {
                if (c.checked == true && c.value != '') {
                    ids += c.value + ",";
                    trunk = $(c).closest('tr').find('.ingress').text();
                    prefix = $(c).closest('tr').find('.prefix').text() ? $(c).closest('tr').find('.prefix').text() : " no prefix";
                    html +="<tr><td>" + trunk +"</td>"
                    html +="<td>"+ prefix +"</td></tr>"
                }
            }
        }
        html += "</tbody></table>";
        if (ids == '' || ids.length < 1) {
            jGrowl_to_notyfy("Please select the item which you would like to assign!", {theme: 'jmsg-error'});
             return
        }
        console.log(html);
        bootbox.confirm(html, function(result) {
            if (result)
            {
                    ids = ids.substring(0, ids.length - 1); // 去掉最后逗号
                    if (url.indexOf("?") != -1) {
                        url = url + "&ids=" + ids;
                    } else {
                        url = url + "?ids=" + ids;
                    }
                    location = url;
            }
        });
    }
    $(function() {
        setTimeout(function(){
              $('.ColVis_collection .ColVis_radio input').each(function(index, val){
                         if(!$(this).is(':checked')){
                             $(this).click();
                         }
                    })
        }, 1000);
        var client_id = '<?php echo isset($_GET["client_id"])? $_GET["client_id"] :"";?>';
        if(client_id){
            $('select[name="client_id"]').val(client_id);
        }
        var resource_id = '<?php echo isset($_GET["resource_id"])? $_GET["resource_id"] :"";?>';
        if(client_id){
            $('select[name="resource_id"]').val(resource_id);
        }

    })
</script>