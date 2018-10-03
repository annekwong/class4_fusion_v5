<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Trunk Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('DID Orders') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Shopping Cart') ?></li>
</ul>

<div class="heading-buttons">
    <h1><?php __('Block List')?></h1>
    <div class="buttons pull-right">

        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>did/orders/browse"><i></i><?php __('Back')?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("shop_cart_tab", array('active' => 'multiples')) ?>
        </div>
        <div class="widget-body">
            <div id="container">
                <form method="post">
                    <table  class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Country')?></th>
                                <th><?php __('Rate Center')?></th>
                                <th><?php __('State')?></th>
                                <th><?php __('City')?></th>
                                <th><?php __('LATA')?></th>
                                <th><?php __('Availability')?></th>
                                <th><?php __('Amount')?></th>
                                <th><?php __('Trunk/IP Address/Prefix')?></th>
                                <th><?php __('Remove')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $item['country']; ?><input type="hidden" name="country[]" value="<?php echo $item['country']; ?>" /></td>
                                    <td><?php echo $item['rate_center']; ?><input type="hidden" name="rate_center[]" value="<?php echo $item['rate_center']; ?>" /></td>
                                    <td><?php echo $item['state']; ?><input type="hidden" name="state[]" value="<?php echo $item['state']; ?>" /></td>
                                    <td><?php echo $item['city']; ?><input type="hidden" name="city[]" value="<?php echo $item['city']; ?>" /></td>
                                    <td><?php echo $item['lata']; ?><input type="hidden" name="lata[]" value="<?php echo $item['lata']; ?>" /></td>
                                    <td><?php echo $item['count']; ?></td>
                                    <td>
                                        <select name="amount[]">
                                            <?php
                                            for ($i = 0; $i <= 1000; $i += 10):
                                                ?>
                                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                                <?php
                                            endfor;
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="egresses_id[]" style="width:auto;">
                                            <?php foreach ($egresses as $egress): ?>
                                                <option value="<?php echo $egress[0]['resource_id'] ?>"><?php echo $egress[0]['name'] ?>/<?php echo $egress[0]['ip'] ?>/<?php echo $egress[0]['prefix'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>

                                        <input type="checkbox" name="remove[]" value="1" />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="text-align:center">
                        <input type="submit" value="<?php __('Submit')?>" class="btn btn-primary" />
                </form>
            </div>
        </div>
    </div>
</div>

