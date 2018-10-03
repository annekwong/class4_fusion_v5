

<script type="text/javascript">
    function add() {
        if (jQuery('.add').html() != null) {
            return
        }
        jQuery('table.list tbody').append(
                jQuery('<tr/>').append(
                jQuery('<td/>').html('<input style="display: none;">')
                ).append(
                jQuery('<td/>').html('<input style="display: none;">')
                ).append(
                jQuery('<td/>').append(jQuery('<input class="marginTop9 width90 input in-text" maxLength="16">').xkeyvalidate({type: 'strName'}))
                ).append(
                jQuery('<td/>').html('<input style="display: none;">')
                ).append(
                jQuery('<td/>').html('<a onclick="save_code(this.parentNode.parentNode);" href="#" style="" class="marginTop9"><img src="<?php echo $this->webroot ?>images/menuIcon_004.gif"></a><a onclick="jQuery(&quot;#rec_strategy&quot;).removeAttr(&quot;add&quot;);this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)" href="javascript:void(0)" style=" margin-left: 10px;" class="marginTop9"><i class="icon-remove"></i></a>')
                ).addClass('add')
                );
        jQuery('table.list tr:nth-child(2n+1)').addClass('row-1').removeClass('row-2');
        jQuery('table.list tr:nth-child(2n)').addClass('row-2').removeClass('row-1');
        return
    }
    function save_code(tr) {
        var params = {
            name: tr.cells[2].getElementsByTagName('input')[0].value
        };
        if (/[^0-9A-Za-z-\_\s]+/.test(params['name'])) {
            jQuery(tr.cells[2].getElementsByTagName('input')[0]).addClass('invalid');
            jGrowl_to_notyfy('Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!', {theme: 'jmsg-error'});
            return false;
        }
        jQuery.post('<?php echo $this->webroot ?>routestrategys/add', params, function(data) {
            var p = {theme: 'jmsg-success', beforeClose: function() {
                    location.reload();
                }, life: 100};
            var tmp = data.split("|");
            if (tmp[1].trim() == 'false') {
                p = {theme: 'jmsg-alert', life: 500};
            }
            jGrowl_to_notyfy(tmp[0], p);
        });
    }
    function edit(currRow) {
        var columns = [
            {}, {},
            {className: ' width90 input in-text  check_strNum'},
            {},
            {}
        ];
        editRow(currRow, columns);
        var btn = currRow.cells[4].getElementsByTagName("a")[0];
        if (btn) {
            var cancel = currRow.cells[4].getElementsByTagName("a")[1].cloneNode(true);
            cancel.title = "<?php __('cancel') ?>";
            cancel.getElementsByTagName("img")[0].src = "<?php echo $this->webroot ?>images/rerating_queue.png";
            cancel.onclick = function() {
                location.reload();
            }
            currRow.cells[4].appendChild(cancel);

            btn.getElementsByTagName("img")[0].src = "<?php echo $this->webroot ?>images/menuIcon_004.gif";
            jQuery(btn).attr('style', 'margin-left:31px');
            btn.onclick = function() {
                var params = {
                    name: currRow.cells[2].getElementsByTagName('input')[0].value,
                    id: currRow.cells[1].innerHTML.trim()
                };

                jQuery.post('<?php echo $this->webroot ?>routestrategys/update', params, function(data) {
                    var p = {theme: 'jmsg-success', beforeClose: function() {

                            var str = location.toString();
                            if (str.indexOf("?") != '-1') {

                                location = location;

                            } else {
                                location = location.toString() + "?edit_id=" + params.id;
                            }

                        }, life: 100};
                    var tmp = data.split("|");
                    if (tmp[1].trim() == 'false')
                        p = {theme: 'jmsg-alert', life: 500};
                    jGrowl_to_notyfy(tmp[0], p);
                });
            }
        }
        jQuery('input.check_strNum').xkeyvalidate({type: 'strName'}).attr('maxLength', '16');
    }
</script>
<div id="cover"></div>
<?php $w = $session->read('writable') ?>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('RoutingStrategies') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('RoutingStrategies') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">

        <!--a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php __('Create New') ?></a-->
        <a class="btn btn-primary btn-icon glyphicons remove" href="###"  onclick="deleteAll('<?php echo $this->webroot; ?>routestrategys/del_strategy/all');"><i></i> <?php __('Delete All') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" href="###" rel="popup" onclick="deleteSelected('rec_strategy', '<?php echo $this->webroot; ?>routestrategys/del_strategy/selected');"><i></i> <?php __('Delete Seleted') ?></a>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>dynamicroutes/view"><i></i><?php __('Back') ?></a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">


        <div class="filter-bar">

            <form action="" method="get">
                <!-- Filter -->
                <div>
                    <label><?php __('Search') ?>:</label>
                    <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                </div>
                <!-- // Filter END -->

                <!-- Filter -->
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                </div>
                <!-- // Filter END -->


            </form>
        </div>

        </li>
        <!--  <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>-->
        </ul>


        <div class="widget-body">
            <div id="container">

                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <col style="width: 5%;">
                    <col style="width: 12%;">
                    <col style="width: 23%;">
                    <col style="width: 25%;">
                    <col style="width: 23%;">
                    <thead>
                        <tr>
                            <th><input type="checkbox" onclick="checkAllOrNot(this, 'rec_strategy');" value=""/></th>
                            <th> <?php echo $appCommon->show_order('route_strategy_id', __('ID', true)) ?></th>
                            <th> <?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                            <th> <?php echo $appCommon->show_order('routes', __('Usage Count', true)) ?></th>
                            <th class="last"><?php echo __('action') ?></th>
                        </tr>
                    </thead>
                    <tbody id="rec_strategy">
                        <?php
                        $mydata = $p->getDataArray();
                        $loop = count($mydata);
                        for ($i = 0; $i < $loop; $i++)
                        {
                            ?>
                            <tr class="row-1">
                                <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['route_strategy_id'] ?>"/></td>
                                <td><?php echo $mydata[$i][0]['route_strategy_id'] ?></td>
                                <td style="font-weight: bold;"><a style="width:80%;display:block" href="<?php echo $this->webroot ?>routestrategys/routes_list/<?php echo base64_encode($mydata[$i][0]['route_strategy_id']) ?>" class="link_width"><?php echo $mydata[$i][0]['name'] ?></a></td>

                                <td>

                                    <?php echo $mydata[$i][0]['routes'] ?>


                                </td>
                                <td align="center">
                                    <?php
                                    if ($w == true)
                                    {
                                        ?>
                                        <a title="<?php echo __('edit') ?>" " href="javascript:void(0)" onclick="edit(this.parentNode.parentNode)">
                                            <i class="icon-edit"></i>
                                        </a>
                                        <a title="<?php echo __('del') ?>"  href="javascript:void(0)" style="margin-left: 10px;" onclick="del(this, '<?php echo $this->webroot ?>routestrategys/del_strategy/<?php echo $mydata[$i][0]['route_strategy_id'] ?>', '<?php echo $mydata[$i][0]['name'] ?>');">
                                            <i class="icon-remove"></i>
                                        </a>
    <?php } ?>
                                </td>
                            </tr>
<?php } ?>		
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
<?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <?php
                $d = $p->getDataArray();
                if (count($d) == 0)
                {
                    ?>
                    <div class="msg"><?php echo __('no_data_found') ?></div>
<?php } ?>
            </div>
            <div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity = 0.5" onmouseover="this.style.opacity = 1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function del(obj, url, d_name) {
        if (confirm("Are you sure to delete,routing plan  " + d_name))
            location = url;
    }
</script>