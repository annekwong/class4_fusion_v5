<style type="text/css">
    .list input {
        font-size:1.0em;
    }
    .in-text, .in-password, .in-textarea {
        margin:0;padding:5px;
    }
    dl {
        padding:5px;overflow:hidden;
    }
    dl dt {
        font-size:14px;font-weight:bold;padding:1px;color:red;cursor:pointer;
    }
    dl dd {
        clear:both;height:30px;display:none;
    }
    dl dd span{
        width:24.5%;display:block;float:left;border:1px solid #eee;height:30px;line-height:30px;text-align:center;
    }
    dl dd span img {
        margin-top:6px;
    }
    #add2 {
        display:none;float:right;margin-right:17px;
    }
    #clone span{padding:5px 0;}
    .extra {
        display:none;
    }
    #container input {
        width:100px;
    }
    input[type="text"],select{margin-bottom: 0;}
    .extra input[type="text"],.extra select{margin-bottom: 10px;}
</style>


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Rate') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Mass Edit') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Mass Edit') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add1" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0);" ><i></i>
        <?php echo __('Create New') ?>
    </a>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rates/rates_list"><i></i> <?php __('Back'); ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="post" id="myform1"  action="<?php echo $this->webroot; ?>rates/delete_rate">
                    <div>
                        <label><?php echo __('Code') ?>:</label>
                        <input type="text" style="width:100px;" id="search-_q" class="" title="Search" value="" name="code">
                    </div>
                    <div>
                        <label><?php echo __('Code Name') ?>:</label>
                        <input type="text" style="width:100px;" id="search-_q" class="" title="Search" value="" name="codeName">
                    </div>
                    <div>
                        <label><?php echo __('Country') ?>:</label>
                        <input type="text" style="width:100px;" id="search-_q" class="" title="Search" value="" name="Country">
                    </div>
                    <div>
                        <label><?php echo __('Effective Date') ?>:</label>
                        <input type="text" id="search-_q" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" class="in-search default-value input in-text defaultText in-input" title="" value="" name="effectiveDate">
                    </div>
                    <div>
                        <label><?php echo __('End Date') ?>:</label>
                        <input type="text" id="search-_q" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" class="in-search default-value input in-text defaultText in-input" title="" value="" name="endDate">
                        <input type="hidden" name="ids" value="<?php echo $ids; ?>" />
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn search_submit input in-submit"><?php echo __('Delete') ?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="container" style="width:100%;">
                <form id="massform" name="massform" method="post" >
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <th><?php echo __('Code', true); ?></th>
                                <th><?php echo __('code_name', true); ?></th>
                                <th><?php echo __('country', true); ?></th>
                                <th><?php __('Rate')?></th>
                                <th><?php __('Setup Fee')?></th>
                                <th><?php __('Effective Date')?></th>
                                <th><?php __('End Date')?></th>
                                <th><?php __('Extra Fields')?></th>
                                <th><?php __('End Break-out')?></th>
                                <th><?php __('action')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><input type="text" class="input in-input in-text" style="font-weight:bold;width:100px;" name="code[]" /></td>
                                <td><input type="text" class="input in-input in-text" style="width:100px;" name="codename[]" /></td>
                                <td><input type="text" class="input in-input in-text" style="width:100px;" name="country[]" /></td>
                                <td><input type="text" class="input in-input in-text" value="0.000000" style="width:100px;" name="rate[]" /></td>
                                <td><input type="text" class="input in-input in-text" value="0.000000" style="width:100px;" name="setupfee[]" /></td>
                                <td><input type="text" class="input in-input in-text" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" value="<?php echo date("Y-m-d 00:00:00") ?>" style="width:100px;" name="effectdate[]" /></td>
                                <td><input type="text" class="input in-input in-text" onfocus="WdatePicker({startDate: '%y-%M-01 23:59:59', dateFmt: 'yyyy-MM-dd HH:mm:ss', alwaysUseStartDate: false})" style="width:100px;" name="enddate[]" /></td>
                                <td><a href="###" class="tpl-params-link"><b class="neg">»</b><b class="neg">»</b><b class="neg">»</b></a></td>
                                <td>
                                    <input type="checkbox" class="input in-input in-text" name="endbreakout[]" />
                                    <input type="hidden" name="endbreakouts[]" />
                                </td>
                                <td><i class="icon-remove"></i></td>
                            </tr>
                            <tr class="extra">
                                <td colspan="10">
                                    Min Time: <input type="text" class="input in-input in-text" value="1" style="font-weight:bold;width:100px;" name="mintime[]" /><?php __('sec')?> &nbsp;	
                                    Interval: <input type="text" class="input in-input in-text" value="1" style="font-weight:bold;width:100px;" name="interval[]" /><?php __('sec')?> &nbsp;	
                                    Grace Time: <input type="text" class="input in-input in-text" value="0" style="font-weight:bold;width:100px;" name="gracetime[]" /><?php __('sec')?> &nbsp;	
                                    Seconds: <input type="text" class="input in-input in-text" value="60"  style="font-weight:bold;width:100px;" name="seconds[]" /><?php __('sec')?> &nbsp;	
                                    Profile: <select class="in-decimal select in-select" name="profile[]" style="width:170px;">
                                        <option value=""> </option>
                                        <option value="206"><?php __('All')?></option>
                                        <option value="209"><?php __('daily')?></option>
                                        <option value="208"><?php __('weekly')?></option>
                                    </select> &nbsp;   	
                                    Time Zone: <select class="in-decimal select in-select" style="width:170px;" name="timezone[]">
                                        <option value=""> </option>
                                        <option value="-12:00">-12:00</option>
                                        <option value="-11:00">-11:00</option>
                                        <option value="-10:00">-10:00</option>
                                        <option value="-09:30">-09:30</option>
                                        <option value="-09:00">-09:00</option>
                                        <option value="-08:00">-08:00</option>
                                        <option value="-07:00">-07:00</option>
                                        <option value="-06:00">-06:00</option>
                                        <option value="-05:00">-05:00</option>
                                        <option value="-04:30">-04:30</option>
                                        <option value="-04:00">-04:00</option>
                                        <option value="-03:30">-03:30</option>
                                        <option value="-03:00">-03:00</option>
                                        <option value="-02:00">-02:00</option>
                                        <option value="-01:00">-01:00</option>
                                        <option value="00:00">00:00</option>
                                        <option value="0">00:00</option>
                                        <option value="01:00">01:00</option>
                                        <option value="02:00">02:00</option>
                                        <option value="03:00">03:00</option>
                                        <option value="03:30">03:30</option>
                                        <option value="04:00">04:00</option>
                                        <option value="04:30">04:30</option>
                                        <option value="05:00">05:00</option>
                                        <option value="05:30">05:30</option>
                                        <option value="06:00">06:00</option>
                                        <option value="06:30">06:30</option>
                                        <option value="07:00">07:00</option>
                                        <option value="08:00">08:00</option>
                                        <option value="09:00">09:00</option>
                                        <option value="09:30">09:30</option>
                                        <option value="10:00">10:00</option>
                                        <option value="10:30">10:30</option>
                                        <option value="11:00">11::00</option>
                                        <option value="11:30">11::30</option>
                                        <option value="12:00">12::00</option>
                                    </select> &nbsp;
                                    <?php __('Local rate')?>: <input type="text" class="input in-input in-text" value="0.000000"  style="font-weight:bold;width:100px;" name="localrate[]" />
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <?php if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
                            { ?>
                                <tr>
                                    <td colspan="10" style="text-align:center"><input type="submit" class="btn btn-primary" value="<?php echo __('submit', true); ?>" /></td>
                                </tr>
<?php } ?>
                        </tfoot>
                    </table>
                </form>

                <form id="onlyform" name="onlyform" action="<?php echo $this->webroot ?>rates/masseditend/<?php echo $ids; ?>" method="post">
                    <dl id="only">
                        <dt style="margin-bottom:10px;">
                        <span class="end_code" style="margin-left:10px;"><?php echo __('End Code', true); ?>...</span>
                        <span>
                            <?php if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
                            { ?>
                                <input id="add2" class="input btn" type="button" value="Add">
<?php } ?>
                        </span>
                        </dt>
                        <dd><span><?php echo __('Code', true); ?></span><span>End Date</span><span>End Breakout</span><span></span></dd>
                        <dd id="clone">
                            <span><input type="text" class="input in-input in-text" style="font-weight:bold;width:150px;" name="code[]" /></span>
                            <span><input type="text" onfocus="WdatePicker({startDate: '%y-%M-01 23:59:59', dateFmt: 'yyyy-MM-dd HH:mm:ss', alwaysUseStartDate: false})" class="input in-input in-text" style="width:150px;" name="enddate[]" /></span>
                            <span>
                                <input type="checkbox" class="input in-input in-text" name="endbreakout[]" />
                                <input type="hidden" name="endbreakouts[]" />
                            </span>
                            <span style="cursor:pointer;"><i class="icon-remove"></i></span>
                        </dd>
<?php if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
{ ?>
                            <dd id="btndd" style="margin:20px auto; text-align:center;">
                                <input type="submit" class="btn btn-primary" id="onlybtn" value="<?php echo __('submit', true); ?>" />
                            </dd>
<?php } ?>
                    </dl>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('dl .end_code').click(function() {

            $('dl dd').toggle();
            $('#add2').toggle();
        });

        $("td input[name='endbreakouts[]']").val(false);
        $("#only dd span input[name='endbreakouts[]']").val(false);

        $("td input[name='endbreakout[]']").live('click', function() {
            $(this).next().val($(this).attr('checked'));
        });

        $("#only dd span input[name='endbreakout[]']").live('click', function() {
            $(this).next().val($(this).attr('checked'));
        });

        $('#add1').click(function() {
            var temp1 = $('table.list tbody tr:first-child').clone(true).appendTo('table.list tbody');
            var temp2 = $('table.list tbody tr:nth-child(2)').clone(true).hide().appendTo('table.list tbody');
            temp1.find("input[name=code[]], input[name=codename[]], input[name=enddate[]]").val('');
            temp1.find("input[name=rate[]], input[name=setupfee[]]").val('0.000000');
            temp1.find("input[name=endbreakout[]]").val(false);
            temp1.find("input[name=effectdate[]]").val('<?php echo date("Y-m-d 00:00:00") ?>');
            temp1.find("input[name=endbreakout[]]").attr('checked', false);
            temp2.find("input[name=mintime[]], input[name=interval[]]").val('1');
            temp2.find("input[name=gracetime[]]").val('0');
            temp2.find("input[name=seconds[]]").val('60');
            temp2.find("input[name=profile[]], input[name=timezone[]]").val('');
            temp2.find("input[name=localrate[]]").val('0.000000');
        });

        $('#add2').click(function() {

            var temp1 = $('#clone').clone(true).insertBefore('#btndd');
            temp1.find('input[name=code[]], input[name=enddate[]]').val('');
            temp1.find("input[name=endbreakout[]]").attr('checked', false);
            return false;
        });

        $('img.delete').live('click', function() {
            if ($('#only dd').size() > 3) {
                $(this).parent().parent().remove();
            }
        });

        $('td.last img').css({'cursor': 'pointer'}).live('click', function() {
            if ($('table.list tbody tr').size() > 2) {
                $(this).parent().parent().next().remove();
                $(this).parent().parent().remove();
            }
        });

        $('a.tpl-params-link').live('click', function() {
            $(this).parent().parent().next().toggle();
        });
    });



    $(function() {
        $('#massform').submit(function() {
            var more = false;
            if (isMathOrEqual($(this))) {
                showMessages("[{'field':'','code':'101','msg':'The code must be unique！'}]");
                return false;
            }
            $(this).submit();
        });

        $('#onlyform').submit(function() {
            var flag = true;
            var inputcode = $("input[name=code[]]", $(this));
            var codes = new Object();
            inputcode.each(function(index) {
                codes[$(this).val()] = '1';
            });
            var k = 0;
            for (var i in codes) {
                k++;
            }
            if (inputcode.length > k) {
                showMessages("[{'field':'','code':'101','msg':'The code must be unique！'}]");
                flag = false;
            }

            $('#only dd span input[name=enddate[]]').each(function() {
                if ($(this).val() == '') {
                    showMessages("[{'field':'','code':'101','msg':'The end date required！'}]");
                    flag = false;
                }
            });

            if (!flag) {
                return false;
            }
        });
    });

    function isMathOrEqual($this) {
        var flag = false;
        $('input[name=code[]]', $this).each(function(index) {
            var match_arr = new Array();
            $(this).parent().parent().siblings().each(function(index) {
                match_arr.push($('input[name=code[]]', $(this)).val());
            });
            if ($(this).parent().parent().find('input[name=endbreakout[]]').attr('checked')) {
                if (isMatch(match_arr, $(this).val())) {
                    flag = true;
                }
            } else {
                if (isEqual(match_arr, $(this).val())) {
                    flag = true;
                }
            }
        });
        return flag;
    }


    function isMatch(arr, val) {
        var reg = new RegExp('^' + val);
        if (arr[0] == undefined) {
            return false;
        }
        for (var i = 0; i < arr.length; i++) {
            return reg.test(arr[i]);
        }
    }

    function isEqual(arr, val) {
        if (arr.length == 1) {
            return false;
        }
        for (var i = 0; i < arr.length; i++) {
            return arr[i] == val;
        }
    }

</script>