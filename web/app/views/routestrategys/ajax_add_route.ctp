<?php
switch ($div)
{
    case 1:
        ?>
        <div id="pop-static-div" class="pop-div" style="height:auto;">

            <div class="pop-content" id="pop-static-content">

                <lable><?php __('Name')?>:</lable><input type="text" name="pname" id="pname" style="width:150px;"/>
                <p>
                <div onclick="checkPname()" class="btn btn-primary"><a id="massbtn" class="input in-button"><?php echo __('submit', true); ?></a></div>

            </div>
        </div>
        <?php
        break;
    case 2:
        ?>
        <div id="pop-static-div1" class="pop-div" style="height:auto">
            <div class="pop-thead">
                <span></span>
                <span class="float_right"><a href="javascript:closeDiv('pop-static-div1')" id="pop-static-close" class="pop-close">&nbsp;</a></span>
            </div>
            <div class="pop-content" id="pop-static-content1">


            </div>
            <?php echo $this->element('dynamicroutes/massAdd') ?>
        </div>
        <div id="pop-static-clarity1" class="pop-static-clarity" ></div>
        <?php
        break;
    case 3:
        ?>
        <div id="pop-static-div1" class="pop-div" style="height:auto">
            <div class="pop-thead">
                <span></span>
                <span class="float_right"><a href="javascript:closeDiv('pop-static-div1')" id="pop-static-close" class="pop-close">&nbsp;</a></span>
            </div>
            <div class="pop-content" id="pop-static-content1">


            </div>
            <?php echo $this->element('routestrategys/massAdd') ?>
        </div>
        <div id="pop-static-clarity1" class="pop-static-clarity" ></div>
        <?php
        break;
    default :
        ?>
        <div></div>

<?php } ?>
        <div><input type="hidden" id="pname_show" /></div>

<script type="text/javascript">
    var product_id = '<?php echo $product_id; ?>';
    var pname = '<?php echo $pname; ?>';
    if (product_id) {
        $(".marginTopStatic").append("<option value = '" + product_id + "'>" + pname + "</option>");
        $(".marginTopStatic").attr('value', pname);
        $(".marginTopStatic").parent().attr('itemvalue', product_id);
        $("#product_id").val(product_id);
    }
    function checkPname() {
        $.post('<?php echo $this->webroot; ?>routestrategys/addStaticRouting', {name: $("#pname").val()},
        function(data) {
            if (data == 'nameIsNull') {
                jQuery.jGrowlError('The field name cannot be NULL!');
            } else if (data == 'nameNotPreg') {
                jQuery.jGrowlError('Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!');
            } else if (data == 'nameLength') {
                jQuery.jGrowlError('The length of the name must be less than 30');
            } else if (data == 'nameIsHave') {
                jQuery.jGrowlError('name is already in use!');
            } else if (data == 'no') {
                jQuery.jGrowlError('add failed');
            } else {
                var pname = $("#pname").val();
                closeDiv('pop-static-div');
                showDiv('pop-static-div1', '800', 'auto', '<?php echo $this->webroot; ?>routestrategys/ajax_add_route/pop-static-div1/' + data+ '/'+pname);



            }
        }

        );
    }
</script>