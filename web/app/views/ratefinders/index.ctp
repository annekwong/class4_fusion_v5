
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Finder') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Finder') ?></h4>
    <!--    <div class="buttons pull-right">
            <a class="btn btn-default btn-icon glyphicons left_arrow" href="<?php echo $this->webroot; ?>ratefinders">
                <i></i>
                Back
            </a>
        </div>-->
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php if(!empty($data)){ ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
                <tr>
                    <th><?php echo $header_title; ?></th>
                    <th><?php __('Code')?></th>
                    <th><?php __('Rate')?></th>
                    <th><?php __('Effective Date')?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $items){ ?>
                <tr>
                    <td><?php echo $items[0]['alias'] ?></td>
                    <td><?php echo $items[0]['code'] ?></td>
                    <td><?php echo $items[0]['rate'] ?></td>
                    <td><?php echo $items[0]['effective_date'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
            <?php }elseif ($search_flg){ ?>
            <div class="msg center">
                <br />
                <h3><?php __('Data can not be found')?>.</h3>
                        </div>
            <?php } ?>
            <form action="" method="post">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <tbody>
                        <tr>
                            <td class="align_right"><?php __('Lookup Rate By')?></td>
                            <td>
                                <select name="by_type" id="by_type">
                                    <option value="1" <?php
                                    if ($post_data['by_type'] == 1)
                                    {
                                        ?> selected="selected" <?php } ?>><?php __('Code')?></option>
                                    <option value="2" <?php
                                            if ($post_data['by_type'] == 2)
                                            {
                                                ?> selected="selected" <?php } ?>><?php __('Code Name')?></option>
                                </select>
                                <span <?php
                                            if ($post_data['by_type'] == 2)
                                            {
                                                ?> class="hidden" <?php } ?> id="code_search" >
                                    &nbsp;&nbsp;
                                    <input type="text" name="code_search" value="<?php echo $post_data['code_search'] ?>"/>
                                </span>
                                <span <?php
                                            if ($post_data['by_type'] == 1)
                                            {
                                                ?> class="hidden" <?php } ?> id="code_deck">
                                    Code Deck:&nbsp;&nbsp;
                                    <select name="code_deck" id="code_deck_select">
                                        <option value="" ></option>
                                        <?php
                                        foreach ($code_deck as $item)
                                        {
                                            ?>
                                        <option value="<?php echo $item[0]['code_deck_id']; ?>"<?php if($post_data['code_deck'] == $item[0]['code_deck_id']){ ?>selected="selected"<?php } ?>><?php echo $item[0]['name']; ?></option>
                                <?php } ?>    
                                    </select>
                                </span>
                                <span <?php
                                if ($post_data['by_type'] == 1)
                                {
                                    ?> class="hidden" <?php } ?> id="code_name">
                                    Code Name:&nbsp;&nbsp;
                                    <select name="code_name" id="code_name_select">
                                        <?php foreach ($code_arr as $key=>$item){ ?>
                                        <option value="<?php echo $key; ?>"<?php if($key == $post_data['code_name']){?> selected="selected" <?php } ?>><?php echo $item; ?></option>
                                        <?php } ?>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right"><?php __('Lookup Rate From')?></td>
                            <td>
                                <select name="from_type" id="from_type">
                                    <option value="1"<?php
                                if ($post_data['from_type'] == 1)
                                {
                                    ?> selected="selected" <?php } ?>><?php __('Ingress Trunks')?></option>
                                    <option value="2"<?php
                                            if ($post_data['from_type'] == 2)
                                            {
                                    ?> selected="selected" <?php } ?>><?php __('Egress Trunks')?></option>
                                    <option value="3"<?php
                                        if ($post_data['from_type'] == 3)
                                        {
                                            ?> selected="selected" <?php } ?>><?php __('Routing Plan')?></option>
                                </select>
                                <span id="ingress_trunk" class="from_type" >
                                    <select name="ingress_trunk[]" multiple="multiple" style="width:250px;height:400px;" >
<?php
foreach ($ingress_trunk as $key => $item)
{
    ?>
                                            <option value="<?php echo $key; ?>"<?php if (in_array($key, $post_data['ingress_trunk']))
                                        {
        ?> selected="selected" <?php } ?>><?php echo $item; ?></option>
                                                <?php } ?>    
                                    </select>
                                </span>
                                <span class="hidden from_type" id="egress_trunk">
                                    <select name="egress_trunk[]" multiple="multiple" style="width:250px;height:400px;">
<?php
foreach ($egress_trunk as $key => $item)
{
    ?>
                                            <option value="<?php echo $key; ?>"<?php if (in_array($key, $post_data['egress_trunk']))
                                        {
        ?> selected="selected" <?php } ?>><?php echo $item; ?></option>
                                                <?php } ?>    
                                    </select>
                                </span>
                                <span class="hidden from_type" id="route_plan">
                                    <select name="route_plan">
                                        <option value="" ></option>
<?php
foreach ($route_plan as $key => $item)
{
    ?>
                                            <option value="<?php echo $key; ?>"<?php if (!strcmp($key, $post_data['route_plan']))
    {
        ?> selected="selected" <?php } ?>><?php echo $item; ?></option>
<?php } ?>    
                                    </select>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="center">
                    <input type="submit" value="Submit" class="input in-submit btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function() {
        <?php if(empty($data) && $search_flg){ ?>
                jGrowl_to_notyfy('The data not found!', {theme: 'jmsg-error'});
        <?php } ?>
        var from_type_first = '<?php echo $post_data['from_type']; ?>';
        $(".from_type").addClass('hidden');
        switch (from_type_first)
        {
            case '1':
                $("#ingress_trunk").removeClass('hidden');
                break;
            case '2':
                $("#egress_trunk").removeClass('hidden');
                break;
            case '3':
                $("#route_plan").removeClass('hidden');
                break;
        }

        $("#by_type").change(function() {
            if ($(this).val() == 1)
            {
                $("#code_name").addClass('hidden');
                $("#code_search").removeClass('hidden');
                $("#code_deck").addClass('hidden');
            }
            else
            {
                $("#code_name").removeClass('hidden');
                $("#code_deck").removeClass('hidden');
                $("#code_search").addClass('hidden');
            }
        });


        $("#from_type").change(function() {
            $(".from_type").addClass('hidden');
            var from_type = $(this).val();
            switch (from_type)
            {
                case '1':
                    $("#ingress_trunk").removeClass('hidden');
                    break;
                case '2':
                    $("#egress_trunk").removeClass('hidden');
                    break;
                case '3':
                    $("#route_plan").removeClass('hidden');
                    break;
            }
        });

        $("#code_deck_select").change(function() {
            $("#code_name_select").html('');
            var code_deck_id = $(this).val();
            $.ajax({
                'url': '<?php echo $this->webroot ?>ratefinders/ajax_get_code_name',
                'type': 'POST',
                'dataType': 'json',
                'data': {'code_deck_id': code_deck_id},
                'success': function(data) {
                    if (!data)
                    {
                            jGrowl_to_notyfy('The code deck has not code!', {theme: 'jmsg-error'});
                    } else
                    {
                        $("#code_name").removeClass('hidden');
                        $.each(data, function(index, item) {
                            $("#code_name_select").append("<option value='" + item + "'>" + item + "</option>");
                        });
                    }

                }
            });

        });

    });

</script>
