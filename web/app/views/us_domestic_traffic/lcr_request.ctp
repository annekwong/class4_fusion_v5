<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.0.8/semantic.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.0.8/semantic.js"></script>

<style>
    .country-select {
        display: none;
    }

    div.field {
        width: 220px;
    }

    .req-form {
        width: 300px;
        padding: 40px;
        margin: 0 auto !important;
        background: rgba(204, 204, 204, 0.29);
    }

    input[type=submit] {
        height: 40px;
        width: 220px;
        max-width: 220px;
        background: #05bc05;
        color: #fff;
        border: 0px;
        font-weight: 600;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LCR Generation') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Request') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Request') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head ">
            <?php echo $this->element('us_reports/tab', array('active' => $active)) ?>
        </div>
        <div class="separator bottom"></div>
        <div class="widget-body">
            <form action="" class="req-form" method="POST" enctype="multipart/form-data">
                <div class="twelve wide field">
                    <label style="width: 250px">Select Code Deck</label>
                    <select id="code_deck_select" name="code_deck_id" class="ui search fluid dropdown new-drop">
                    <?php
                    if(!empty($codeDecks)) {
                        foreach ($codeDecks as $codeDeck) {
                        ?>
                            <option value="<?php echo $codeDeck[0]['code_deck_id'];?>"><?php echo $codeDeck[0]['name'];?></option>
                        <?php
                        }
                    }
                    ?>
                    </select>
                </div>

                <div class="twelve wide field">
                    <label style="width: 250px">Generate for all Codes</label>
                    <select id="for_all" name="for_all" class="ui fluid dropdown new-drop">
                        <option value="0" selected>Yes</option>
                        <option value="1">No</option>
                    </select>
                </div>

                <?php
                if(!empty($codeDecks)) {
                    foreach ($codeDecks as $codeDeck) {
                        ?>
                        <div class="country-select twelve wide field" data-name="country_<?php echo $codeDeck[0]['code_deck_id'];?>">
                            <label style="width: 250px">Select Country</label>
                            <select onchange="countryChange(this)" name="country_<?php echo $codeDeck[0]['code_deck_id'];?>" multiple="multiple" class="ui search fluid dropdown new-drop">

                            <?php
                            foreach ($codeDeck['countries'] as $country) {
                                ?>
                                <option value="<?php echo $country[0]['country']; ?>"><?php echo $country[0]['country']; ?></option>
                                <?php
                            }
                            ?>
                            </select>
                        </div>
                        <?php
                    }
                }
                ?>

                <div class="country-select twelve wide field">
                    <label style="width: 250px">Select Codes</label>
                    <select id="codes" name="codes[]" multiple="multiple" class="ui fluid search dropdown">
                    </select>
                </div>

                <?php
                if(!empty($egressTrunks)) {
                    ?>
                    <div class="twelve wide field">
                        <label style="width: 250px">Egress Trunks</label>
                        <select name="egressTrunks[]" multiple="multiple" class="ui search fluid dropdown new-drop">
                <?php
                    foreach ($egressTrunks as $egressTrunk) {
                        ?>
                            <option value="<?php echo $egressTrunk[0]['resource_id'] ?>"><?php echo $egressTrunk[0]['alias'] ?></option>
                        <?php
                    }
                    ?>
                        </select>
                    </div>
                    <?php
                }
                ?>
                <div class="">
                    <label style="width: 250px">Effective Date</label>
                    <input type="text" style="height: 39px; width: 250px !important; max-width: 220px;" value="" name="effective_date" id="effective_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});">
                </div>
                <div class="form-footer">
                    <input type="submit" name="Submit" value="Create Request">
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    $(".new-drop").dropdown({
        allowLabels:true
    });

    $("#codes").dropdown({
        allowLabels:true
    });

    function toggleCountry() {
        $(".country-select").hide();
        if($("#for_all").val() == 1) {
            $("div[data-name='country_" + $("#code_deck_select").val() + "']").show();
        }
    }

    $(document).ready(function () {
        $("#code_deck_select").change(function () {
            toggleCountry();
        });

        $("#for_all").change(function () {
           toggleCountry();
        });
    });

    function countryChange(el) {
        var selectedCountries = $(el).val();
        var elementId = $(el).attr('name').split('_')[1];
        $("#codes").parent().parent().hide();
        $('#codes').find('option').remove();
        $.post("<?php echo $this->webroot;?>us_domestic_traffic/getCodesByCountry",
            {
                'selectedCountries': selectedCountries,
                'elementId': elementId
            },
            function (data) {
                var result = $.parseJSON(data);
                $.each(result, function(item, data) {
                        $("#codes").append("<option value='" + data[0]['code_id'] + "'>" + data[0]['name'] + "</option>");
                });
                $("#codes").parent().parent().show();
            });
    }

</script>