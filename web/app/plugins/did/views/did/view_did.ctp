<?php $data = $p->getDataArray(); ?>
<style>
    table input {
        width:100px;
    }
    .parentFormundefined{
        z-index: 9999;
    }
    #container select{width: 100px;}

    table.table-black {
        border: 1px solid #ccc;
    }

    table.table-black thead tr th {
        color: #000;
        background: rgb(127, 175, 0) !important;
    }
    input.no-input-style{
        border: none;
        cursor: default;
    }
    .require_auth input {
        margin: 0 10px 0 3px;
    }

</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/repository"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if (isset($_GET['ingress_id'])): ?>
        <li>Vendor [<?php echo $ingresses[$_GET['ingress_id']] ?>]</li>
        <li class="divider"><i class="icon-caret-right"></i></li>
    <?php endif ?>
    <li><a href="<?php echo $this->webroot ?>did/repository">
        <?php echo __('DID Repository', true); ?></a></li>
          <li class="divider"><i class="icon-caret-right"></i></li>
          <li> <a href="<?php echo $this->webroot ?>did/repository">
          <?php echo  $_GET['orig_client_name']; ?>
          </a>
          </li>
</ul>
<div class="buttons pull-right newpadding">
    <a class="link_back btn btn-icon glyphicons btn-inverse circle_arrow_left" href="<?php echo $this->webroot ?>did/clients"><i></i> <?php echo __('Back', true); ?></a>
</div>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('DID', true); ?>[ <?php echo  $_GET['orig_client_name']; ?> ]</h4>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <div class="widget-body">
                <?php
                if (empty($data))
                {
                    ?>
                    <h2 class="msg center"><?php echo __('no_data_found') ?></h2>

                    <?php
                }
                else
                {
                    ?>
                    <div class="clearfix"></div>

                    <div class="overflow_x">
                        <table id="repository" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="overflow: auto;overflow-x: hidden">
                            <thead>
                            <tr>
                                <th rowspan="2"><?php echo $appCommon->show_order('did', 'DID'); ?></th>
                                <th rowspan="2"><?php echo $appCommon->show_order('monthly_charge', 'Price/DID/Month'); ?></th>
                                <th rowspan="2"><?php echo $appCommon->show_order('min_price', 'Price/Minute'); ?></th>
                                <th rowspan="2"><?php echo $appCommon->show_order('payphone_subcharge', 'Payphone Subcharge'); ?></th>
                                <th rowspan="2"><?php __('Assigned Date') ?></th>
                                <th rowspan="2"><?php __('Action') ?></th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            foreach ($data as $item)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $item[0]['did']; ?></td>
                                    <td><?php echo $item[0]['monthly_charge']; ?></td>
                                    <td class="client_name"><?php echo $item[0]['min_price'] ?></td>
                                    <td><?php echo $item[0]['payphone_subcharge']; ?></td>
                                    <td class="assigned_time_td"><?php echo $item[0]['start_date']; ?></td>
                                    <td>
                                        <a title="<?php __('Delete') ?>" onclick="return myconfirm('Are you sure to delete the number[<?php echo $item[0]['did'] ?>] ?', this);" class="delete" href='<?php echo $this->webroot; ?>did/did/delete_did/<?php echo base64_encode($item[0]['id']); ?>/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>?orig_client_id=<?php echo $orig_client_id ?>&orig_client_name=<?php echo $orig_client_name ?>'>
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="row-fluid separator">
                            <div class="pagination pagination-large pagination-right margin-none">
                                <?php echo $this->element('page'); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $("#mass_assign_btn").click(function(){
            var $mass_assign_checked = $('.multi_select:checked');
            if ($mass_assign_checked.size() == 0){
                jGrowl_to_notyfy('<?php __('Nothing is selected'); ?>', {theme: 'jmsg-error'});
                return false;
            }
        });

        $('#did_assign_submit').click(function() {
//            var client_id = parseInt($("#myModal_DidAssign").find('#egress_id').val());
//            if (!client_id)
//            {
//                jGrowl_to_notyfy("<?php //__("You need to add the client"); ?>//", {theme: 'jmsg-error'});
//                return false;
//            }
            var selected = new Array();
            $('.multi_select').each(function() {
                var $this = $(this);
                if ($this.is(':checked')) {
                    selected.push($this.val());
                }
            });
            var selected_str = selected.join(',');
            $('#selected_did').val(selected_str);

            $('#myModal_DidAssign_form').submit();
        });

        $('#add').on('click', function(){
            $('#save').attr('title', 'Save');
        });

    })
</script>

<script>
    $(function () {
        $('a.expand').click(function () {
            $(this).closest('tr').find('.edit_item').click();
        });
    });
</script>