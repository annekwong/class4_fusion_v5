<style>
    .hidden {
        display: none;
    }

</style>
<?php echo $form->create('ResourceBlock', Array('action' => 'add', 'method' => 'post')) ?>
<table>
    <tr >
        <td class="footable-first-column expand" data-class="expand"></td>
        <td><?php echo $form->input('time_profile_id', Array('type' => 'select', 'div' => false, 'class' => 'input-small', 'label' => false, 'options' => $appBlocklists->_get_select_options($TimeProfileList, 'TimeProfile', 'time_profile_id', 'name'), 'empty' => ' ')) ?></td>
        <td>
            <select name="data[ResourceBlock][type]" id="type">
                <option value="1">Block By Trunk</option>
                <option value="2">Block By Trunk Group</option>
            </select>
        </td>
        <td>
        <?php echo $form->input('egress_client_id', array('label' => false, 'empty' => 'All Carriers', 'class' => 'input-small', 'div' => false, 'options' => $appBlocklists->_get_select_options($EgressClient, 'Client', 'client_id', 'name'))); ?>
        </td>
        <td>
        <?php echo $form->input('engress_res_id', array('label' => false, 'div' => false, 'class' => 'input-small', 'type' => 'select', 'v' => $this->data['ResourceBlock']['engress_res_id'])); ?>
            <div id="egress_groups" class="hidden">
                <select name="data[ResourceBlock][egress_group_id]" id="" class="input-small select in-select">
                    <option></option>
                    <?php
                    foreach ($egress_group as $item):
                    ?>
                        <option value="<?php echo $item['TrunkGroup']['group_id'];?>"><?php echo $item['TrunkGroup']['group_name'];?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
                <p class="group-p">Group</p>
            </div>
        </td>
        <td>
            <?php echo $form->input('ingress_client_id', array('label' => false, 'class' => 'input-small', 'empty' => 'All Carriers', 'div' => false, 'options' => $appBlocklists->_get_select_options($IngressClient, 'Client', 'client_id', 'name'))); ?>
        </td>
        <td>
            <?php echo $form->input('ingress_res_id', array('label' => false, 'div' => false, 'class' => 'input-small', 'type' => 'select', 'v' => $this->data['ResourceBlock']['ingress_res_id'])); ?>
            <div id="ingress_groups" class="hidden">
                <select name="data[ResourceBlock][ingress_group_id]" id="" class="input-small select in-select">
                    <option></option>
                    <?php
                    foreach ($ingress_group as $item):
                        ?>
                        <option value="<?php echo $item['TrunkGroup']['group_id'];?>"><?php echo $item['TrunkGroup']['group_name'];?></option>
                        <?php
                    endforeach;
                    ?>
                </select>
                <p class="group-p">Group</p>
            </div>
        </td>
        <td>
            <?php
            echo $form->input('ani_empty', array('label' => false, 'div' => false, 'class' => 'prefix_chk', 'type' => 'checkbox', 'class' => 'input-small'));
            ?>
        </td>
        <td>
            <?php
            $disabled = false;
            if ($this->data['ResourceBlock']['ani_empty'] == 't')
                $disabled = true;
            echo $form->input('ani_prefix', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[custom[onlyLetterNumber]  input-small', 'disabled' => $disabled));
            ?>
            <!--        <input type="checkbox" name="data[ResourceBlock][is_disabled]" class="prefix_chk" <?php if (empty($this->data['ResourceBlock']['ani_prefix'])) echo 'checked="checked"'; ?> />-->
        </td>
        <td>
            <?php echo $form->input('ani_length', array('id' => 'ani_length', 'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[custom[onlyLetterNumber] input-small')); ?>
        </td>
        <td>
            <?php echo $form->input('ani_max_length', array('id' => 'ani_max_length', 'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[custom[onlyLetterNumber]  input-small')); ?>
        </td>
        <td>
            <?php echo $form->input('digit', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[custom[onlyLetterNumber]  input-small')); ?>
        </td>
        <td data-hide="phone,tablet"  style="display: table-cell;">
            <?php echo $form->input('dnis_length', array('id' => 'dnis_length', 'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[custom[onlyLetterNumber]  input-small')); ?>
        </td>
        <td data-hide="phone,tablet"  style="display: table-cell;">
            <?php echo $form->input('dnis_max_length', array('id' => 'dnis_max_length', 'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[custom[onlyLetterNumber]  input-small')); ?>
        </td>
        <td data-hide="phone,tablet"  style="display: table-cell;">

        </td>
        <td data-hide="phone,tablet"  style="display: table-cell;">

        </td>
        <td data-hide="phone,tablet"  style="display: table-cell;">

        </td>
<!--        <td data-hide="phone,tablet"  style="display: table-cell;">
        </td>-->
        <td  data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
            <a title="Save" id="save" href="javascript:void(0)">
                <i class="icon-save"></i>
            </a>
            <a title="Exit" id="delete"  href="javascript:void(0)">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>
<script type="text/javascript">
$(function(){
    $('input').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });

    $("#ResourceBlockAniEmpty").click(function(){
        var is_ani_empty = $(this).attr('checked');
        $("#ResourceBlockAniPrefix").removeAttr("disabled");
        if(is_ani_empty)
        {
            $("#ResourceBlockAniPrefix").attr("disabled","disabled");
        }
    });

    $("#type").change(function () {
       if($(this).val() == 1) {
           $("#ResourceBlockEngressResId").show().css('visibility', 'visible');
           $("#ResourceBlockIngressResId").show().css('visibility', 'visible');
           $("#ResourceBlockEgressClientId").show().css('visibility', 'visible');
           $("#ResourceBlockIngressClientId").show().css('visibility', 'visible');
           $("#egress_groups").hide().css('visibility', 'hidden');
           $("#ingress_groups").hide().css('visibility', 'hidden');
       } else {

           $("#ResourceBlockEngressResId").hide().css('visibility', 'hidden');
           $("#ResourceBlockIngressResId").hide().css('visibility', 'hidden');
           $("#ResourceBlockEgressClientId").show().css('visibility', 'hidden');
           $("#ResourceBlockIngressClientId").show().css('visibility', 'hidden');
           $("#egress_groups").show().css('visibility', 'visible');
           $("#ingress_groups").show().css('visibility', 'visible');
       }
    });


})    
    
   
</script>



