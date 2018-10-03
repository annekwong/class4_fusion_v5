<style>
    input[readonly] {
        background: #eee;
        border: 1px solid #bbb;
    }

    select[name=target] {
        width: 100px;
        max-width: 100px;
    }

    select[name=action] {
        width: 100px;
        max-width: 100px;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/manipulation"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/manipulation">
            <?php echo __('DID Manipulation', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('DID Manipulation', true); ?></h4>

</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form id="like_form" method="get">
                    <div>
                        <label><?php echo __('Vendor', true); ?>:</label>
                        <select name="ingress_id">
                            <option value=""><?php __('All') ?></option>
                            <?php
                            foreach ($ingresses as $key => $ingress)
                            {
                                ?>
                                <option <?php if (isset($_GET['ingress_id']) && $_GET['ingress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $ingress ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label><?php echo __('Client', true); ?>:</label>
                        <select name="egress_id">
                            <option value=""><?php __('All') ?></option>
                            <?php foreach ($egresses as $key => $egress): ?>
                                <?php if($egress): ?>
                                    <option <?php if (isset($_GET['egress_id']) && $_GET['egress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key ?>"><?php echo $egress ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <input type="hidden" name="search" value="1" />
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="widget-body">

                <?php if (empty($data)) { ?>
                    <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <?php } else { ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="overflow_x">
                            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="overflow: auto;overflow-x: hidden">
                                <thead>
                                <tr>
                                    <th rowspan="2">DID</th>
                                    <th rowspan="2">Vendor</th>
                                    <th rowspan="2">Vendor IP</th>
                                    <th rowspan="2">Client</th>
                                    <th rowspan="2">Client IP</th>
                                    <th colspan="5">ANI Manipulation</th>
                                    <th colspan="5">DNIS Manipulation</th>
                                </tr>
                                <tr>
                                    <th>Match Prefix</th>
                                    <th>Action</th>
                                    <th>Remove Digit</th>
                                    <th>Append Code</th>
                                    <th>Replace With</th>
                                    <th>Match Prefix</th>
                                    <th>Action</th>
                                    <th>Remove Digit</th>
                                    <th>Append Code</th>
                                    <th>Replace With</th>
                                </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($data as $item) { ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="did[]" value="<?php echo $item[0]['code']; ?>">
                                                <?php echo $item[0]['code']; ?>
                                            </td>
                                            <td>
                                                <?php foreach ($ingresses as $key => $ingress) {
                                                    if ($key == $item[0]['vendor_id']) {
                                                        echo $ingress;
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $item[0]['vendor_ip']; ?>
                                            </td>
                                            <td>
                                                <?php foreach ($egresses as $key => $egress) {
                                                    if ($key == $item[0]['client_id']) {
                                                        echo $egress;
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $item[0]['client_ip']; ?>
                                            </td>
                                            <td>
                                                <input type="text" name="aniPrefix[]" maxlength="16">
                                            </td>
                                            <td>
                                                <select name="aniAction[]" id="" onchange="actionChange_event(this)">
                                                    <option value="0">None</option>
                                                    <option value="1">Append</option>
                                                    <option value="2">Remove</option>
                                                    <option value="3">Replace</option>
                                                    <option value="4">Replace Partial</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="aniRemoveDigit[]" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="aniAppendCode[]" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="aniReplaceWith[]" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="dnisPrefix[]" maxlength="16">
                                            </td>
                                            <td>
                                                <select name="dnisAction[]" id="" onchange="actionChange_event(this)">
                                                    <option value="0">None</option>
                                                    <option value="1">Append</option>
                                                    <option value="2">Remove</option>
                                                    <option value="3">Replace</option>
                                                    <option value="4">Replace Partial</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="dnisRemoveDigit[]" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="dnisAppendCode[]" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="dnisReplaceWith[]" readonly>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    function actionChange_event(el) {
        var parent = $(el).parent();

        $(parent).next().find('input').prop('readonly', true);
        $(parent).next().next().find('input').prop('readonly', true);
        $(parent).next().next().next().find('input').prop('readonly', true);


        switch ($(el).val()) {
            case '1':
                $(parent).next().next().find('input').prop('readonly', false);
                break;
            case '2':
                $(parent).next().find('input').prop('readonly', false);
                break;
            case '3':
                $(parent).next().next().next().find('input').prop('readonly', false);
                break;
            case '4':
                $(parent).next().next().next().find('input').prop('readonly', false);
                break;
        }
    }

//    $('tr').unbind('click');
</script>