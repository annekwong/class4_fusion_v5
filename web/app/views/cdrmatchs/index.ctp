<style type="text/css">
    #cdrmatchsearch {
        width:900px;
        margin:0 auto;
    }
    .boxouter {
        overflow:hidden;
        clear:both;
        border-bottom:1px dashed green;
        margin:4px 0;
    }
    label {
        width:120px;
        display:block;
    }
    div.block {
        float:left;
        width:260px;
        margin:10px;
    }
</style>


<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('CDR Reconciliation') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">CDR Reconciliation</h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">


        <div class="widget-body">
            <form id="cdrmatchsearch" name="cdrmatchsearch"  action="<?php echo $this->webroot ?>cdrmatchs/show" method="post" enctype="multipart/form-data">

                <div class="boxouter">
                    <div class="block">
                        <label>Type:</label>
                        <select name="type" class="input in-text in-select select">
                            <option value="client_cdr">Client CDR</option>
                            <option value="vendor_cdr">Vendor CDR</option>
                        </select>
                    </div>

                    <div class="block">
                        <label>Carrier:</label>
                        <select name="carrier" class="input in-text in-select select">
                            <?php foreach ($carriers as $carrier): ?>
                                <option value="<?php echo $carrier[0]['id'] ?>"><?php echo $carrier[0]['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="block">
                        <label>Format:</label>
                        <select name="format" class="input in-text in-select select">
                            <option value="0">Line-by-Line</option>
                            <option value="1">Aggregated Comparison</option>
                        </select>
                    </div>

                </div>
                <!--
                       <div class="boxouter">
                            <div class="block">
                            <label></label>
                            <input type="checkbox" name="is_compare_rate" />&nbsp;rate/no rate
                            </div>
                
                            <div class="block">
                            <label>duration:</label>
                            <input type="text" name="dur_time" />s
                            </div>
                
                            <div class="block">
                            <label>call time:</label>
                            <input type="text" name="call_time" />s
                            </div>
                       </div>
                -->       
                <div class="boxouter">
                    <div class="block">
                        <label>Start Time:</label>
                        <input type="text" name="starttime" class="input in-text in-input" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
                    </div>

                    <div class="block">
                        <label>End Time:</label>
                        <input type="text" name="endtime" class="input in-text in-input" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
                    </div>

                    <div class="block">
                        <label>GMT:</label>
                        <select name="gmt" class="input in-text in-select select">
                            <option value="-1200">GMT -12:00</option>
                            <option value="-1100">GMT -11:00</option>
                            <option value="-1000">GMT -10:00</option>
                            <option value="-0900">GMT -09:00</option>
                            <option value="-0800">GMT -08:00</option>
                            <option value="-0700">GMT -07:00</option>
                            <option value="-0600">GMT -06:00</option>
                            <option value="-0500">GMT -05:00</option>
                            <option value="-0400">GMT -04:00</option>
                            <option value="-0300">GMT -03:00</option>
                            <option value="-0200">GMT -02:00</option>
                            <option value="-0100">GMT -01:00</option>
                            <option selected="selected" value="+0000">GMT +00:00</option>
                            <option value="+0100">GMT +01:00</option>
                            <option value="+0200">GMT +02:00</option>
                            <option value="+0300">GMT +03:00</option>
                            <option value="+0330">GMT +03:30</option>
                            <option value="+0400">GMT +04:00</option>
                            <option value="+0500">GMT +05:00</option>
                            <option value="+0600">GMT +06:00</option>
                            <option value="+0700">GMT +07:00</option>
                            <option value="+0800">GMT +08:00</option>
                            <option value="+0900">GMT +09:00</option>
                            <option value="+1000">GMT +10:00</option>
                            <option value="+1100">GMT +11:00</option>
                            <option value="+1200">GMT +12:00</option>
                        </select>
                    </div>
                </div>

                <div class="boxouter">

                    <div class="block">
                        <label>Client / Vendor CDR:</label>
                        <input type="file" name="cdrfile" />
                    </div>

                    <div class="block">
                        <label>Example Format:</label>
                        <a target="_blank" href="<?php echo $this->webroot ?>cdrmatchs/example_file">example.csv</a>
                    </div>
                </div>

                <div style="text-align:center;">     
                    <input type="submit" name="sub" value="Submit" class="input in-submit btn btn-primary" />
                    &nbsp;
                    <input type="reset" value="reset" class="input in-submit btn btn-primary" />
                    &nbsp;
                    <input type="button" value="Show List" id="showlist" class="input in-submit btn btn-primary" />
                </div>

            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#cdrmatchsearch').submit(function() {
            if ($('input[name=starttime]').val() == '') {
                alert('Start Time required!');
                return false;
            }
            if ($('input[name=endtime]').val() == '') {
                alert('End Time required!');
                return false;
            }
        });

        $('#showlist').click(function() {
            window.location.href = "<?php echo $this->webroot ?>cdrmatchs/showlist";

        });

        $('select[name=type]').change(function() {
            var flag = 'client';
            if ($(this).val() == 'client_cdr') {
                flag = 'ingress';
            } else {
                flag = 'egress';
            }
            $.ajax({
                'url': '<?php echo $this->webroot ?>cdrmatchs/changecarrer/' + flag,
                'method': 'GET',
                'dateType': 'json',
                'success': function(data) {
                    $('select[name=carrier]').empty();
                    data = eval('(' + data + ')');
                    $.each(data, function(index, value) {
                        $('<option value="' + value.id + '">' + value.name + '</option>').appendTo('select[name=carrier]');
                    });
                }
            });
        });

        $('select[name=type]').change();
    });

</script>