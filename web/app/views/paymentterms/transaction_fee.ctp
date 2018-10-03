<script>
    var str = "";
    function del(id) {
        if (confirm("do you want to deleted!")) {
            location = "<?php echo $this->webroot ?>paymentterms/del_transaction_fee/" + id
        }
    }


    function upd(obj, id) {
        var tr = $(obj).parent().parent();
        str = tr.html();

        //alert(tr.innerHTML);
        tr = tr.get(0);

        tr.innerHTML = "<td><input type='text' name='min_rate' value='" + tr.cells[0].innerHTML + "' class='in-text'></td>\n\
                        <td><input type='text' name='max_rate' value='" + tr.cells[1].innerHTML + "' class='in-text'></td>\n\
                        <td><input type='text' name='charge_value' value='" + (tr.cells[2].innerHTML.split('%'))[0] + "' class='in-text'></td>\n\
                        <td><a><i onclick= \"save(this," + id + ")\" class='icon-save' /></i></a>\n\
                        <a><i onclick= \"notEdit(this)\" class='icon-remove' ></i></a></td>";
        //tr.cells[0].innerHTML = "";
    }

    function save(obj, upd_id) {
        var min_rate = $($(obj).parent().parent().parent().get(0).cells[0]).find('input').val();
        var max_rate = $($(obj).parent().parent().parent().get(0).cells[1]).find('input').val();
        var charge_value = $($(obj).parent().parent().parent().get(0).cells[2]).find('input').val();

        if (min_rate == '' || isNaN(min_rate)) {
            jGrowl_to_notyfy("Min Rate Must be digital", {theme: 'jmsg-error'});
            return false;
        }
        if (max_rate == '' || isNaN(max_rate)) {
            jGrowl_to_notyfy("Max Rate Must be digital!", {theme: 'jmsg-error'});
            return false;
        }
        if (charge_value == '' || isNaN(charge_value)) {
            jGrowl_to_notyfy("Charge Value Must be digital!", {theme: 'jmsg-error'});
            return false;
        }

        if (min_rate > max_rate || min_rate == max_rate) {
            jGrowl_to_notyfy("Min Rate must be less than Max Rate", {theme: 'jmsg-error'});
            return false;
        }

        if (charge_value < 0 || charge_value > 100) {
            jGrowl_to_notyfy("Charge Value Must be more than 100 less than zero!", {theme: 'jmsg-error'});
            return false;
        }

        $.post("<?php echo $this->webroot ?>paymentterms/update_transaction_fee",
                {min: min_rate, max: max_rate, charge: charge_value, id: upd_id},
        function(data) {
            location.reload();
            /* if(data == 'yes'){
             location.reload() 
             }else{
             location.reload() 
             }*/
        }
        );


    }

    function save1(obj) {
        var min_rate = $($(obj).parent().parent().parent().get(0).cells[0]).find('input').val();
        var max_rate = $($(obj).parent().parent().parent().get(0).cells[1]).find('input').val();
        var charge_value = $($(obj).parent().parent().parent().get(0).cells[2]).find('input').val();

        if (min_rate == '' || isNaN(min_rate)) {
            jGrowl_to_notyfy("Min Rate Must be digital", {theme: 'jmsg-error'});
            return false;
        }
        if (max_rate == '' || isNaN(max_rate)) {
            jGrowl_to_notyfy("Max Rate Must be digital!", {theme: 'jmsg-error'});
            return false;
        }
        if (charge_value == '' || isNaN(charge_value)) {
            jGrowl_to_notyfy("Charge Value Must be digital!", {theme: 'jmsg-error'});
            return false;
        }

        if (min_rate > max_rate || min_rate == max_rate) {
            jGrowl_to_notyfy("Min Rate must be less than Max Rate", {theme: 'jmsg-error'});
            return false;
        }

        if (charge_value < 0 || charge_value > 100) {
            jGrowl_to_notyfy("Charge Value Must be more than 100 less than zero!", {theme: 'jmsg-error'});
            return false;
        }

        $.post("<?php echo $this->webroot ?>paymentterms/add_transaction_fee",
                {min: min_rate, max: max_rate, charge: charge_value},
        function(data) {
            //alert(data);
            location.reload();
            /* if(data == 'yes'){
             location.reload() 
             }else{
             location.reload() 
             }*/
        }
        );


    }

    function notEdit(obj) {
        location.reload();
    }

    function notEdit1(obj) {
        $(obj).parent().parent().parent().remove();
    }

    function addTransaction() {
        $("#list").show();
        $("#list").append("<tr><td><input type='text' name='min_rate'  class='in-text'></td>\n\
                            <td><input type='text' name='max_rate'  class='in-text'></td>\n\
                            <td><input type='text' name='charge_value'  class='in-text'></td>\n\
                            <td><a><i onclick= \"save1(this)\" class='icon-save' ></i></a>\n\
                            <a><i onclick= \"notEdit1(this)\" class='icon-remove' ></i></a></th></tr>");
    }
</script>


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Transaction') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Transaction Fee') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Transaction Fee') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" onclick="addTransaction()" href="#"><i></i> <?php __('Create New') ?></a>
    </div>
    <?php $w = $session->read('writable'); ?>
    <div class="clearfix"></div>

<div id="container">
    <div id="list_div">
        <div id="toppage"></div>
        <div class="innerLR">


            <div class="clearfix"></div>
            <?php
            if (count($res) == 0)
            {
                ?>
                <div class="msg center"  id="msg_div">
                    <h2><?php echo __('no_data_found') ?></h2>
                </div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="list" style="display: none;">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('min_rate', __('Min Rate', true)) ?></th>
                            <th><?php echo $appCommon->show_order('max_rate', __('Max Rate', true)) ?></th>
                            <th><?php echo $appCommon->show_order('charge_value', __('Charge Value(%)', true)) ?></th>
                            <th> <?php __('Action')?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <?php
            }
            else
            {
                ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="list">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('min_rate', __('Min Rate', true)) ?></th>
                            <th><?php echo $appCommon->show_order('max_rate', __('Max Rate', true)) ?></th>
                            <th><?php echo $appCommon->show_order('charge_value', __('Charge Value(%)', true)) ?></th>
                            <th> <?php __('Action')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($res as $re)
                        {
                            ?>
                            <tr>
                                <td><?php echo $re[0]['min_rate'] ?></td>
                                <td><?php echo $re[0]['max_rate'] ?></td>
                                <td><?php echo round($re[0]['charge_value'], 3) . "%" ?></td>
                                <td>
                                    <a class="edit" title="<?php __('edit')?>" onclick= "upd(this,<?php echo $re[0]['id'] ?>)" >
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a title="<?php __('delete')?>" onclick="myconfirm('do you want to deleted?',this);return false;" href="<?php echo $this->webroot; ?>paymentterms/del_transaction_fee/<?php echo $re[0]['id'] ?>" >
                                        <i class="icon-remove"></i>
                                    </a>

                                </td>
                            </tr>
                            <?php
                        }
                        ?>     
                    <tbody>
                </table>
                <?php
            }
            ?> 
        </div>
    </div>
</div>