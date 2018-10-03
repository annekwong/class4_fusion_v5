<style type="text/css">
#addproject {
    width:50%;
}    
#addproject p {
    margin:5px;
}   

</style>
<div id="title">
    <h1><?php __('Tools')?>&gt;&gt;<?php echo __('Vendor Test',true);?></h1>
    <ul id="title-menu">
        <li>
            <a class="link_back" href="javascript:void(0)" onclick="javascript:history.go(-1)" >
                <img width="16" height="16" src="/Class4/images/icon_back_white.png" alt="<?php echo __('goback',true);?>">
                &nbsp;<?php echo __('goback',true);?> 
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <form action="###" method="post" name="myform" id="myform">
        <div id="addproject">
            <h1><?php echo __('New Project',true);?></h1>
            <p>
                <label><?php echo __('Project Name',true);?>:</label>
                <input type="text" name="project_name" />
            </p>
            <p>
                <label><?php echo __('Codecs',true);?>:</label>
                <select name="codec" id="codec">
                    <option value="PCMA|8000|8" selected="selected"><?php __('PCMA')?></option>
                    <option value="PCMU|8000|0"><?php __('PCMU')?></option>
                    <option value="G729|8000|18"><?php __('G729')?></option>
                </select>
           </p>
            <p>
                <label><?php echo __('Trunks',true);?>:</label>
                <select name="trunk" id="trunk">
                        <option></option>
                    <?php foreach($trunks as $trunk): ?>
                        <option value="<?php echo $trunk[0]['resource_id'] ?>" />
                            <?php echo $trunk[0]['alias'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
           </p>
            <p>
                <label><?php echo __('code_name',true);?>:</label>
                <select name="codename" id="codename"  style="width:300px;">
                    <option></option>
                </select>
                <input type="button" value="search" id="search" />
            </p>
            <p>
                <label>
                    <?php __('Search with select box above or insert your own numbers in textarea below')?>
                </label>
                <textarea style="width:500px;height:100px;" id="numbers" name="numbers"></textarea>
            </p>
            <p>
                <label><?php echo __('ani',true);?>:</label>
                <input type="text" name="call_number" />
            </p>
            <p>
                <label><?php echo __('Call Time',true);?>:</label>
                <input type="text" name="call_time" value="60" />s
            </p>
            <p>
                <input type="hidden" name="code_names" id="code_names" />
                <input type="submit" value="<?php echo __('create',true);?>" />
            </p>
        </div>
    </form>
</div>

<script type="text/javascript">
$(function() {
    $('#trunk').change(function() {
        $.ajax({
            url:'<?php echo $this->webroot ?>vendortests/get_codename/' + $(this).val(),
            type:'GET',
            dataType:'json',
            success:function(data) {
                var $codename = $('#codename');
                $codename.empty();
                $.each(data, function(index, item) {
                    $codename.append('<option value="' + item['code_name'] + '">'+ item['code_name'] +'</option>');
                });
            }
        });
    });

    $('#search').click(function() {
        $.ajax({
            url:'<?php echo $this->webroot ?>vendortests/get_cdrnum/' + $('#codename').val(),
            type:'GET',
            dataType:'json',
            success:function(data) {
                var $numbers = $('#numbers');
                $numbers .val('');
                $.each(data, function(index, item) {
                    var $number = item[0]['origination_destination_number'] + "\n";
                    $numbers.val($numbers.val() + $number);
                });
            }
        });
    });
    
});
</script>