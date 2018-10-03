<style type="text/css">
    #note_window {
        border:1px solid #ccc;
        border-radius: 15px;
        background:#fff;
        max-width:500px;
        max-height: 200px;
        width:500px;
        height:200px;
        display:none;
    }

    #note_window p {
        padding:10px;
    }

    #note_window h1 {
        text-align:right;
        padding-right:20px;
        paddign-top:10px;
    }
    .list .jsp_resourceNew_style_2 tbody td {font-size: 12px;}
    .list .jsp_resourceNew_style_2 tbody td:hover {font-size: 12px;}
</style>

<?php echo $this->element('magic_css_three'); ?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports/status">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports/status">
        <?php echo __('Daily Switch Usage') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Daily Switch Usage') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">

            <?php if (count($data) == 0): ?>
                <center>
                    <h2 class="msg center">No Payment Record for the period of  
                        <?php echo isset($_GET['start']) ? $_GET['start'] : date("Y-m-d", strtotime("-1 day")); ?> - <?php echo isset($_GET['start']) ? $_GET['end'] : date("Y-m-d"); ?>
                    </h2>
                </center>
            <?php else: ?>

                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Date') ?></th>
                            <th><?php __('max CPS') ?></th>
                            <th><?php __('Call Count') ?></th>

                        </tr>
                    </thead>

                    <?php foreach ($data as $item): ?>
                        <tbody>
                            <tr>

                                <td><?php echo $item[0]['time']; ?></td>
                                <td><?php echo $item[0]['max_cps']; ?></td>
                                <td><?php echo $item[0]['call_count']; ?></td>

                            </tr>

                        </tbody>
                        <?php
                    endforeach;
                    ?>
                </table>


                <div class="clearfix"></div>
            <?php endif; ?>
            <br />
            <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                <div style="margin:0px auto; text-align:center;">
                    <form name="myform" method="get" id="myform">
                        <input type="hidden" id="is_export" name="is_export" value="0" />
                        <?php __('Period')?>:
                        <input type="text" name="start"  class="input-small" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: 'en'})" value="<?php echo isset($_GET['start']) ? $_GET['start'] : date("Y-m-d", strtotime("-7 day")); ?>" />
                        ~
                        <input type="text" name="end" class="input-small" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: 'en'})" value="<?php echo isset($_GET['start']) ? $_GET['end'] : date("Y-m-d"); ?>" />
                        <?php __('GMT')?>:
                        <select name="gmt" id='gmt' class="input-small">
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
                            <option value="+0000">GMT +00:00</option>
                            <option value="+0100">GMT +01:00</option>
                            <option value="+0200">GMT +02:00</option>
                            <option value="+0300">GMT +03:00</option>
                            <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option>
                        </select>           

                        <input type="submit" class="btn btn-primary" style="margin-bottom:10px;" value="<?php echo __('submit', true); ?>" />
                    </form>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        var gmt = '<?php echo $gmt; ?>';
        $("#gmt").val(gmt);
    });
</script>