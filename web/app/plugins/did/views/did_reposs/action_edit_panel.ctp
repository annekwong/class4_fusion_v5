
<?php echo $form->create('DidRepos') ?>
<?php echo $xform->input('lata', array('id' => 'DIDLata','type' => "hidden")); ?>
<table>
    <tr>
        <td></td>
        <td><?php
            if (isset($edit))
            {
                echo $this->data['DidRepos']['number'];
            }
            else
            {
                echo $xform->input('number', array('maxlength' => 20, 'type' => "text",'class' => 'did_number validate[required,custom[onlyNumber]]'));
            }
            ?>
        </td>
        <td><?php echo $xform->input('ingress_id', array('options' => $ingresses,'class'=>'validate[required]')) ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td id="DIDCountry">
            <?php echo $xform->input('country', array('maxlength' => 100, 'type' => "text",'class' => 'did_country validate[custom[onlyLetterNumberLineSpace]]')); ?>
        </td>
        <td id="DIDState">
            <?php echo $xform->input('state', array('maxlength' => 100, 'type' => "text",'class' => 'did_state validate[custom[onlyLetterNumberLineSpace]]')); ?>
            </td>
        <td id="DIDCity">
            <?php echo $xform->input('city', array('maxlength' => 100, 'type' => "text",'class' => 'width120 did_city validate[custom[onlyLetterNumberLineSpace]]')); ?>
        </td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="javascript:void(0)" title="Edit">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Exit">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>

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
                    $("#DIDCity").find('input').val(city);
                    $("#DIDState").find('input').val(state);
                    $("#DIDLata").val(lata);
                }
            });
        }
    }

    $(document).ready(function(){
        $('.did_number').live('input',ajax_get_country_state_by_did).live('keyup',ajax_get_country_state_by_did);
    });


</script>