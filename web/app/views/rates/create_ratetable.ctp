<style type="text/css">
    .ocn_lata {display:none;}
</style>
<?php if ( !$appCommon->_get('is_ajax') ): ?>
    <ul class="breadcrumb">
        <li><?php __('You are here') ?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>rates/rates_list"><?php __('Switch') ?></a></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>rates/create_ratetable">
                <?php echo __('Create Rate Table') ?></a></li>
    </ul>

    <div class="heading-buttons">
        <h4 class="heading"><?php echo __('Create Rate Table') ?></h4>

    </div>
    <div class="separator bottom"></div>
    <div class="buttons pull-right newpadding">
        <a  class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>rates/rates_list"><i></i> <?php echo __('Back') ?></a>
    </div>
    <div class="clearfix"></div>

<?php endif; ?>
<div class="innerLR">

    <div class="widget widget-heading-simple">
        <div class="widget-body">
            <form method="post" action="" id="myform">
                <table class="table tableTools table-bordered  table-white">
                    <tr>
                        <td style="text-align:right;">
                            <?php __('Rate Table Name'); ?>:
                        </td>
                        <td colspan="7">
                            <input type="text"  name="rate_table_name" class="validate[required] width220"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">
                            <?php __('Code Deck'); ?>:
                        </td>
                        <td>
                            <select name="code_deck">
                                <option selected="selected"></option>
                                <?php foreach ($code_decks as $code_deck): ?>
                                    <option value="<?php echo $code_deck[0]['code_deck_id']; ?>"><?php echo $code_deck[0]['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">
                            <?php __('Currency'); ?>:
                        </td>
                        <td>
                            <select name="currency">
                                <?php foreach ($currencies as $currency): ?>
                                    <option value="<?php echo $currency[0]['currency_id']; ?>" <?php if(!strcmp($default_currency,$currency[0]['currency_id'])){ ?>selected="selected"<?php } ?>><?php echo $currency[0]['code']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">
                            <?php __('Rate Type'); ?>:
                        </td>
                        <td>
                            <?php echo $form->input('rate_type',array('type'=>'select','options'=> $rate_type_arr,'div'=>false,
                                'label'=>false,'selected'=>$default_us_ij_rule,'name'=>'rate_type')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">
                            <?php __('Type'); ?>:
                        </td>
                        <td>
                            <select name="type">
                                <option value="0"><?php __('DNIS')?></option>
                                <option value="1"><?php __('LRN')?></option>
                                <option value="2"><?php __('LRN BLOCK')?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">
                            <?php __('Define By'); ?>:
                        </td>
                        <td>
                            <select name="define_by">
                                <option value="0"><?php __('Code')?></option>
                                <option value="1"><?php __('Code Name')?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php if ( !$appCommon->_get('is_ajax') ): ?>
                    <div id="buttons pull-right" style="text-align:right;margin:10px;">
                        <a href="###" id="new"  class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> <?php __('Create New'); ?></a>
                        <a href="###" id="delete_selected"  class="btn btn-icon btn-primary glyphicons remove"><i></i> <?php __('Delete Selected'); ?></a>
                        <a href="###" id="delete_all"  class="btn btn-icon btn-primary glyphicons remove"><i></i> <?php __('Delete All'); ?></a>
                    </div>

                    <table id="ratelist" class="list-form list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded">
                        <thead>
                        <tr>
                            <th><input type="checkbox" /></th>
                            <th><?php __('Code'); ?></th>
                            <th><?php __('Code Name'); ?></th>
                            <th><?php __('Country'); ?></th>
                            <th><?php __('OCN'); ?></th>
                            <th><?php __('LATA'); ?></th>
                            <th><?php __('Rate'); ?></th>
                            <th><?php __('Intra Rate'); ?></th>
                            <th><?php __('Inter Rate'); ?></th>
                            <th><?php __('Effective Date'); ?></th>
                            <th><?php __('End Date'); ?></th>
                            <th><?php __('Extra Fields'); ?></th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="create_new hidden">
                            <td><input type="checkbox" /></td>
                            <td><input type="text" name="code[]" style="width:100px;" /></td>
                            <td><input type="text" class="code_name" name="code_name[]" style="width:100px;" /></td>
                            <td><input type="text" class="country" name="country[]" style="width:100px;" /></td>
                            <td><input type="text"  rel="format_number" name="ocn[]" style="width:100px;"  /></td>
                            <td><input type="text"  rel="format_number" name="lata[]" style="width:100px;"  /></td>
                            <td><input type="text" name="rate[]" value="0.00000" style="width:100px;" class="rate_value validate[required,custom[number]]" /></td>
                            <td><input type="text" name="intra_rate[]" style="width:100px;" /></td>
                            <td><input type="text" name="inter_rate[]" style="width:100px;" /></td>
                            <td>
                                <input type="text" name="effective_date[]" style="width:120px;" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" value="<?php echo date("Y-m-d 00:00:00") ?>" />
                                <select name="effective_date_gmt[]" style="width:100px;">
                                    <option value="-12">GMT -12:00</option>
                                    <option value="-11">GMT -11:00</option>
                                    <option value="-10">GMT -10:00</option>
                                    <option value="-09">GMT -09:00</option>
                                    <option value="-08">GMT -08:00</option>
                                    <option value="-07">GMT -07:00</option>
                                    <option value="-06">GMT -06:00</option>
                                    <option value="-05">GMT -05:00</option>
                                    <option value="-04">GMT -04:00</option>
                                    <option value="-03">GMT -03:00</option>
                                    <option value="-02">GMT -02:00</option>
                                    <option value="-01">GMT -01:00</option>
                                    <option selected="selected" value="+00">GMT +00:00</option>
                                    <option value="+01">GMT +01:00</option>
                                    <option value="+02">GMT +02:00</option>
                                    <option value="+03">GMT +03:00</option>
                                    <option value="+03">GMT +03:30</option>
                                    <option value="+04">GMT +04:00</option>
                                    <option value="+05">GMT +05:00</option>
                                    <option value="+06">GMT +06:00</option>
                                    <option value="+07">GMT +07:00</option>
                                    <option value="+08">GMT +08:00</option>
                                    <option value="+09">GMT +09:00</option>
                                    <option value="+10">GMT +10:00</option>
                                    <option value="+11">GMT +11:00</option>
                                    <option value="+12">GMT +12:00</option>
                                    <option value=""></option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="end_date[]" style="width:120px;" onfocus="WdatePicker({startDate: '%y-%M-01 23:59:59', dateFmt: 'yyyy-MM-dd HH:mm:ss', alwaysUseStartDate: false})" />
                                <select name="end_date_gmt[]" style="width:100px;">
                                    <option value="-12">GMT -12:00</option>
                                    <option value="-11">GMT -11:00</option>
                                    <option value="-10">GMT -10:00</option>
                                    <option value="-09">GMT -09:00</option>
                                    <option value="-08">GMT -08:00</option>
                                    <option value="-07">GMT -07:00</option>
                                    <option value="-06">GMT -06:00</option>
                                    <option value="-05">GMT -05:00</option>
                                    <option value="-04">GMT -04:00</option>
                                    <option value="-03">GMT -03:00</option>
                                    <option value="-02">GMT -02:00</option>
                                    <option value="-01">GMT -01:00</option>
                                    <option selected="selected" value="+00">GMT +00:00</option>
                                    <option value="+01">GMT +01:00</option>
                                    <option value="+02">GMT +02:00</option>
                                    <option value="+03">GMT +03:00</option>
                                    <option value="+03">GMT +03:30</option>
                                    <option value="+04">GMT +04:00</option>
                                    <option value="+05">GMT +05:00</option>
                                    <option value="+06">GMT +06:00</option>
                                    <option value="+07">GMT +07:00</option>
                                    <option value="+08">GMT +08:00</option>
                                    <option value="+09">GMT +09:00</option>
                                    <option value="+10">GMT +10:00</option>
                                    <option value="+11">GMT +11:00</option>
                                    <option value="+12">GMT +12:00</option>
                                    <option value=""></option>
                                </select>
                            </td>
                            <td>
                                <a class="tpl-params-link" title="Additional properties" href="###">
                                    <small id="tpl-params-text">1 / 1 / 0 / <?php __('undefined')?></small>
                                    <b class="neg">»</b>
                                </a>
                            </td>
                            <td>
                                <a href="###" class="deletebtn">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                        <tr style="display:none" class="subAddRow">
                            <td colspan="11">
                                <?php __('Setup Fee'); ?>:
                                <input type="text" name="setup_fee[]" style="width:50px;" value="0.000000" />
                                <?php __('Min Time'); ?>:
                                <input type="text" name="min_time[]" style="width:50px;" value="1" class="validate[custom[integer]]"/><?php __('sec')?>
                                <?php __('Interval'); ?>:
                                <input type="text" name="interval[]" style="width:50px;" value="1" class="validate[custom[integer]]"/>
                                <?php __('Grace Time'); ?>:
                                <input type="text" name="grace_time[]" style="width:50px;" value="0" class="validate[custom[integer]]"/><?php __('sec')?>
                                <?php __('Seconds'); ?>:
                                <input type="text" name="second[]" style="width:50px;" value="60" class="validate[custom[integer]]"/><?php __('sec')?>
                                <?php __('Profile'); ?>:
                                <select name="profile[]" style="width:80px;">
                                    <option></option>
                                    <?php foreach ($timeprofiles as $timeprofile): ?>
                                        <option value="<?php echo $timeprofile[0]['time_profile_id']; ?>"><?php echo $timeprofile[0]['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <?php __('Local Rate'); ?>:
                                <input type="text" name="local_rate[]" style="width:80px;"  />
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr >
                            <td colspan="11" class="center"> <input class="btn btn-primary" type="submit" value="<?php __('Submit')?>" />&nbsp;&nbsp;&nbsp;<input class="btn btn-default" type="button" id="backbtn" value="<?php __('Cancel')?>" /></td>
                        </tr>
                        </tfoot>
                    </table>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript">

    function rateTypeChange() {
        if($("#rate_type option:selected").text() == "A-Z") {
            $("select[name=type]").parent().parent().hide();
            $("#myform").prepend("<input id='tmp_type' type='hidden' name='type' value='" + $("#rate_type option:selected").val() + "'/>");
        } else {
            $("#tmp_type").remove();
            $("select[name=type]").parent().parent().show();
        }
    }

    function getCountryAndCodeName() {
        let value = $('select[name="code_deck"]').val();
        if(value != '') {
            $.post("<?php echo $this->webroot;?>rates/getCodeDeckData", {
                codeDeckId: value
            }, function (data) {
                console.log(data);
            });
        }
    }

    $(function() {

        $("#myform").submit(function () {
            $('#ratelist tbody tr:first').remove();
        });

        $( 'input[name="code[]"]' ).on('blur', function() {
            let code = $(this).val();
            let codeDeckID = $('select[name="code_deck"]').val();
            let self = this;
            if(codeDeckID && code){
                jQuery.ajax({
                    'url'      : "<?php echo $this->webroot ?>rates/getCodeData/" + code + "/" + codeDeckID,
                    'type'     : "GET",
                    "dataType" : "json",
                    "success"  : function(response) {
                        if(response.status && response.data){
                            $(self).closest('tr').find('.code_name').val(response.data.name);
                            $(self).closest('tr').find('.country').val(response.data.country);
                        }else if(response.status){
                            $(self).closest('tr').find('.code_name').val('');
                            $(self).closest('tr').find('.country').val('');
                        }
                    }
                });
            }
        });

        var tr1 = $('#ratelist tbody tr:first');
        $('#ratelist tbody tr:first td:last').html('');
        //var tr2 = $('#ratelist tbody tr:first').remove();

        $('#rate_type').change(function() {

            $('table:not(:first):not(:last)').find('th').show();
            $('table:not(:first):not(:last)').find('td').show();

            if ($(this).val() == '0' || $(this).val() == '1') {
                $('table:not(:first):not(:last)').find('th:nth-child(5)').hide();
                $('table:not(:first):not(:last)').find('th:nth-child(6)').hide();
                $('table:not(:first):not(:last)').find('th:nth-child(8)').hide();
                $('table:not(:first):not(:last)').find('th:nth-child(9)').hide();
                $('table:not(:first):not(:last)').find('td:nth-child(5)').hide();
                $('table:not(:first):not(:last)').find('td:nth-child(6)').hide();
                $('table:not(:first):not(:last)').find('td:nth-child(8)').hide();
                $('table:not(:first):not(:last)').find('td:nth-child(9)').hide();
            } else if ($(this).val() == '2') {
                $('table:not(:first):not(:last)').find('th:nth-child(5)').hide();
                $('table:not(:first):not(:last)').find('th:nth-child(6)').hide();
                $('table:not(:first):not(:last)').find('td:nth-child(5)').hide();
                $('table:not(:first):not(:last)').find('td:nth-child(6)').hide();
            }
        });


        $('#rate_type').change();


        $('.country').live('click', function() {
            $(this).autocomplete(countries)
        });

        $('.code_name').live('click', function() {
            $(this).autocomplete(cities)
        });

        $('#new').click(function() {
            tr1.clone(true).appendTo('#ratelist tbody').removeClass('hidden');

            $('#ratelist tbody tr:last td:last').html('' +
                '<a href="###" class="deletebtn">'+
                '<i class="icon-remove"></i>'+
                '</a>');
            $('#rate_type').change();
            jQuery('#ratelist').find('input[rel=format_number]').xkeyvalidate({type: 'Num'}).attr('maxLength', '16');
            $('.subAddRow:first').clone(true).appendTo('#ratelist tbody');
        });

        $('.tpl-params-link').live('click', function() {
            var mintime = $(this).parent().parent().next().find('input[name="min_time[]"]').val();
            var interval = $(this).parent().parent().next().find('input[name="interval[]"]').val();
            var gracetime = $(this).parent().parent().next().find('input[name="grace_time[]"]').val();
            var profile = $(this).parent().parent().next().find('select option:selected').text();
            $(this).find('small').text(mintime + ' / ' + interval + ' / ' + gracetime + ' / ' + profile);
            $(this).parent().parent().next().toggle().trigger('click');
        });

        $('.deletebtn').live('click', function() {
            $(this).parent().parent().next().remove().end().remove();
        });

        $('#ratelist thead input:checkbox').click(function() {
            $('#ratelist tbody input:checkbox').attr('checked', $(this).attr('checked'));
        });

        $('#delete_selected').click(function() {
            $('#ratelist tbody tr:has(input:checked)').next().remove().end().remove();
        });

        $('#delete_all').click(function() {
            $('#ratelist tbody').empty();
        });

        $('#backbtn').click(function() {
            window.location.href = "<?php echo $this->webroot ?>rates/rates_list";
        });

        $('#rate_type').change(function () {
            rateTypeChange();
        });

        $('select[name="code_deck"]').change(function () {
            getCountryAndCodeName();
        });
    });

    $(document).ready(function () {
        rateTypeChange();
    });
</script>