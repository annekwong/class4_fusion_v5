<form>
    <table>
        <tr class="add_edit">
            <td></td>
            <td><?php
                if (isset($edit))
                {
//                    if(!empty($egresses)){
//                       unset($egresses[0]);
//                    }
                    echo $this->data['did'];
                    echo $xform->input('did', array('maxlength' => 20, 'type' => "hidden",'class' => 'did_number'));
                }
                else
                {
                    echo $xform->input('did', array('maxlength' => 20, 'type' => "text",'class' => 'did_number validate[required,custom[onlyNumber]]'));
                }
                ?>
            </td>
            <td><?php echo $xform->input('vendor_id', array('style' => 'width: 150px', 'options' => $ingresses,'class'=>'validate[required]','selected' => $appCommon->_get('ingress_id'))) ?></td>
            <td>
                <select style="width: 150px;" name="data[vendorBillingId]" class="validate[required]">
                    <?php
                    foreach ($billingRules as $key => $billingRule) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($this->data['vendor_billing_id'] ) &&  $this->data['vendor_billing_id'] == $key )? "selected" : "" ;?>><?php echo $billingRule; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td><?php echo $xform->input('client_id', array('style' => 'width: 150px', 'options' => $egresses,'class'=>'validate[required]','selected' => $appCommon->_get('egress_id'))) ?></td>
            <td>
                <select style="width: 150px;" name="data[clientBillingId]" class="validate[required]">
                    <?php
                    foreach ($billingRules as $key => $billingRule) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($this->data['client_billing_id'] ) &&  $this->data['client_billing_id'] == $key )? "selected" : "" ;?>><?php echo $billingRule; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                $actionAni = 2;
                $actionDnis = 2;

                if (isset($this->data['action'])) {
                    if ($this->data['group']) {
                        if (is_array($this->data['group'])) {
                            foreach ($this->data['group'] as $key => $item) {
                                if ($item == 1) {
                                    $actionAni = $this->data['action'][$key];
                                } elseif ($item == 2) {
                                    $actionDnis = $this->data['action'][$key];
                                }
                            }
                        }
                    }
                }
                ?>

                <input type="hidden" id="ani_dnis" data-action= "<?php echo $actionAni;?>" data-group= "<?php echo isset($this->data['group']) ? $this->data['group'] : '';?>" value="<?php echo isset($this->data['ani_dnis']) ? $this->data['ani_dnis'] : '';?>">
                <input type="hidden" id="ani" data-action= "<?php echo $actionAni;?>" data-group= "<?php echo isset($this->data['group']) ? $this->data['group'] : '';?>" value="<?php echo isset($this->data['ani']) ? $this->data['ani'] : '';?>">
                <input type="hidden" id="dnis" data-action= "<?php echo $actionDnis;?>" data-group= "<?php echo isset($this->data['group']) ? $this->data['group'] : '';?>" value="<?php echo isset($this->data['dnis']) ? $this->data['dnis'] : '';?>">
            </td>
            <td>
                <input type="checkbox" name="data[enable_for_clients]" <?php echo $this->data['enable_for_clients'] ? 'checked' : ''; ?>>
            </td>
            <td></td>
            <td></td>
            <td align="center" style="text-align:center" class="last">
                <a id="save" href="javascript:void(0)" title="Save">
                    <i class="icon-save"></i>
                </a>
                <a id="delete" title="Exit">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
        <!--        <tr style="height: auto;">-->
        <!--            <td colspan="9">-->
        <!--                <div class="jsp_resourceNew_style_2" style="padding:5px;display: none;">-->
        <!--                    <table class="list sub-table footable table table-striped tableTools table-bordered table-primary table-black">-->
        <!--                        <thead>-->
        <!--                        <tr>-->
        <!--                            <th colspan="4">ANI</th>-->
        <!--                            <th colspan="4">DNIS</th>-->
        <!--                        </tr>-->
        <!--                        <tr>-->
        <!--                            <th>--><?php //__('Action')?><!--</th>-->
        <!--                            <th>--><?php //__('Prefix')?><!--</th>-->
        <!--                            <th>--><?php //__('Num of Digits')?><!--</th>-->
        <!--                            <th>--><?php //__('New Number')?><!--</th>-->
        <!--                            <th>--><?php //__('Action')?><!--</th>-->
        <!--                            <th>--><?php //__('Prefix')?><!--</th>-->
        <!--                            <th>--><?php //__('Num of Digits')?><!--</th>-->
        <!--                            <th>--><?php //__('New Number')?><!--</th>-->
        <!--                        </tr>-->
        <!--                        </thead>-->
        <!--                        <tbody>-->
        <!--                        <tr>-->
        <!--                            <td>-->
        <!--                                <select data-group="1" class="width120 actions" name = "ani[actions]">-->
        <!--                                    --><?php //foreach ($actions as $key => $action) :?>
        <!--                                        <option value="--><?php //echo $key;?><!--">-->
        <!--                                            --><?php //echo $action;?>
        <!--                                        </option>-->
        <!--                                    --><?php //endforeach;?>
        <!--                                </select>-->
        <!--                            </td>-->
        <!--                            <td>-->
        <!--                                <input data-group="1" data-action="1" class="digits input in-text" type="text" check="MyNum" maxlength="10" value="" name="ani[digits]" disabled="disabled">-->
        <!--                            </td>-->
        <!--                            <td>-->
        <!--                                <select  data-group="1" data-action="3" class="width120 deldigits" name = "ani[deldigits]" disabled="disabled">-->
        <!--                                    --><?php //foreach ($del_digits as $key => $del_digit) :?>
        <!--                                        <option value="--><?php //echo $key;?><!--">-->
        <!--                                            --><?php //echo $del_digit;?>
        <!--                                        </option>-->
        <!--                                    --><?php //endforeach;?>
        <!--                                </select>-->
        <!--                            </td>-->
        <!--                            <td>-->
        <!--                                <input  data-group="1" data-action="2" type="text" name="ani[ani]" value="" class="ani" disabled="disabled">-->
        <!--                            </td>-->
        <!--                            <td>-->
        <!--                                <select  data-group="2" class="width120 actions" name = "dnis[actions]">-->
        <!--                                    --><?php //foreach ($actions as $key => $action) :?>
        <!--                                        <option value="--><?php //echo $key;?><!--">-->
        <!--                                            --><?php //echo $action;?>
        <!--                                        </option>-->
        <!--                                    --><?php //endforeach;?>
        <!--                                </select>-->
        <!--                            </td>-->
        <!--                            <td>-->
        <!--                                <input data-group="2" data-action="1" class="digits input in-text" type="text" check="MyNum" maxlength="10" value="" name="dnis[digits]" disabled="disabled">-->
        <!--                            </td>-->
        <!--                            <td>-->
        <!--                                <select  data-group="2" data-action="3" class="width120 deldigits" name = "dnis[deldigits]" disabled="disabled">-->
        <!--                                    --><?php //foreach ($del_digits as $key => $del_digit) :?>
        <!--                                        <option value="--><?php //echo $key;?><!--">-->
        <!--                                            --><?php //echo $del_digit;?>
        <!--                                        </option>-->
        <!--                                    --><?php //endforeach;?>
        <!--                                </select>-->
        <!--                            </td>-->
        <!--                            <td>-->
        <!--                                <input data-group="2" data-action="2" type="text" name="dnis[dnis]" value="" class="dnis" disabled="disabled">-->
        <!--                            </td>-->
        <!--                        </tr>-->
        <!--                        </tbody>-->
        <!--                    </table>-->
        <!--                </div>-->
        <!--            </td>-->
        <!--        </tr>-->
    </table>
</form>

<script type="text/javascript">

    var ajax_get_country_state_by_did = function(){
        var did = $(this).val();
        var did_length = did.length;
        if (did_length == 6 || did_length == 7)
        {
            $.ajax({
                'url': '<?php echo $this->webroot ?>jurisdictionprefixs/ajax_get_country_state_by_did',
                'type': 'POST',
                'dataType': 'json',
                'data': {'did': did},
                'success': function(data) {
                    var city = data.city;
                    var state = data.state;
                    var lata = data.lata;
                    $("#DIDCountry").find('input').val('US');
//                    $("#DIDCity").find('input').val(city);
                    $("#DIDState").find('input').val(state);
                    $("#DIDLata").val(lata);
                }
            });
        }
    }

    $("a#delete").click(function () {
        $('.jsp_resourceNew_style_2.expanded').removeClass('expanded').slideUp('fast');
    });


</script>