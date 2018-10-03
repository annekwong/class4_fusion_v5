


<!--
<div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
    <div class="widget-head"><h4 class="heading"><?php __('DTMF Settings') ?></h4></div>
    <div class="widget-body">
        <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
            <colgroup><col width="37%"><col width="63%">

            </colgroup>
            <tr>
                <td class="align_right padding-r10"><?php __('DTMF INFO'); ?></td>
                <td>
                    <?php $info = isset($post['Gatewaygroup']['info']) ? $post['Gatewaygroup']['info'] : "0"; ?>
                    <?php echo $form->input('info', array('options' => array('Disable', 'Enable'), 'label' => false, 'div' => false, 'value' => $info)); ?>
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('DTMF RFC2833'); ?></td>
                <td>
                    <?php $rfc2833 = isset($post['Gatewaygroup']['rfc2833']) ? $post['Gatewaygroup']['rfc2833'] : "0"; ?>
                    <?php echo $form->input('rfc2833', array('options' => array('Disable', 'Enable'), 'label' => false, 'div' => false, 'value' => $rfc2833)); ?>
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('DTMF INBAND'); ?></td>
                <td>
                    <?php $inband = isset($post['Gatewaygroup']['inband']) ? $post['Gatewaygroup']['inband'] : "0"; ?>
                    <?php echo $form->input('inband', array('options' => array('Disable', 'Enable'), 'label' => false, 'div' => false, 'value' => $inband)); ?>
                </td>
            </tr>

        </table>
    </div>
</div>
-->

<div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
    <div class="widget-head"><h4 class="heading"><?php __('Codecs Settings') ?></h4></div>
    <div class="widget-body">
        <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
            <colgroup><col width="37%"><col width="63%">

            </colgroup>
            <fieldset>
                <tr>
                    <td class="align_right padding-r10"><?php __('Codecs') ?></td>
                    <td>
                        <table class="form">
                            <tr>
                                <td>
                                    <?php
                                    echo $form->input('select1', array('id' => 'select1', 'options' => $nousecodes, 'multiple' => true,
                                        'style' => 'width: 200px; height: 250px;', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                    ?>
                                </td>
                                <td>
                                    <input class="input in-submit btn"  style="width: 60px; height: 30px; margin-left: 0px;" onclick="DoAdd();" type="button" value="<?php __('add') ?>"/>
                                    <br/><br/>
                                    <input class="input in-submit btn" type="button"   style="width: 60px; height: 30px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete') ?>"  />
                                </td>
                                <td>
                                    <?php
                                    echo $form->input('select2', array('id' => 'select2', 'options' => $usecodes, 'multiple' => true,
                                        'style' => 'width: 200px; height:250px;', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                    ?>
                                </td>
                                <td>
                                    <input class="input in-submit btn" style="width: 60px; height: 30px; margin-left: 0px;" onclick="moveOption('select2', 'up');"  type="button"  value="<?php __('up') ?>"  />
                                    <br/><br/>
                                    <input  type="button" class="input in-submit btn"  style="width: 60px; height: 30px; margin-left: 0px;"  onclick="moveOption('select2', 'down');"   value="<?php __('Down') ?>"  />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </fieldset>


        </table>
    </div>
</div>