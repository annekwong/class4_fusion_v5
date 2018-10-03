<style>
    .error-div {
        display: none;
    }

    div.bootbox.modal {
        width: 80% !important;
        left: 10% !important;
        margin-left: 0px !important;
    }

</style>

<form id="form3" class="form-inline" action="<?php echo $this->webroot ?>uploads/egress_action" method="POST" enctype="multipart/form-data">
    <div id="static_div" style="text-align: left; width: 530px;">
        <table class="cols" style="width: 252px; margin: 0px auto;"></table>
    </div>
    <table class="cols" style="width:700px;margin:0px auto;">
        <tbody>
            <tr>
                <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Import File', true); ?>:</td>
                <td style="text-align:left;" class="last"><input type="file" name="file" id="myfile3"></td>
            </tr>
            <tr>
                <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Duplicate', true); ?>:</td>
                <td style="text-align:left;" class="last">
                    <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore">
                    <label for="duplicate_type_ignore"><?php echo __('Ignore', true); ?></label>			  
<!--					<input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite">
                    <label for="duplicate_type_overwrite"><?php echo __('Overwrite', true); ?></label>			  -->
                    <input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete"  checked="checked">
                    <label for="duplicate_type_delete"><?php echo __('delete', true); ?></label>
                </td>
            </tr>
            <tr><td colspan="2"  align="center"><span id="analysis_myfile3" class="analysis" style="display:block;"></span></td></tr>
            <tr><td align="right"><?php __('Example')?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/resource_action.csv" target="_blank" title="Show example file"><?php __('show')?></a></td></tr>
            <tr>
                <td style="text-align:right;padding-right:4px;" class="first last"></td>
            </tr>
            <tr>
                <td colspan="2" class="first last center"><div class="submit"><input type="submit" value="<?php echo __('upload', true); ?>" class="input in-submit btn btn-primary"></div></td>
            </tr>	
        </tbody>
    </table>
</form>

<div class="error-div">
    <table id="error_table" class="list list-form footable table table-striped tableTools table-bordered  table-white table-primary default">
        <thead>
        <tr>
            <th>Line</th>
            <th>Data</th>
            <th>Errors</th>
            <th>Ignore</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script type="text/javascript">

    var ignoredLines = [];
    var flag = false;

    function addIgnoreLine(line, element) {
        ignoredLines.push(line);
        $(element).parent().parent().hide();
    }

    function checkFile() {

        let filename = $("input[name='myfile3_guid']").val();
        filename = filename.trim();
        if(filename.length > 0) {
            ignoredLines = [];
            $.ajax({
                type: 'POST',
                url: "<?php echo $this->webroot;?>uploads/checkEgressFile",
                data: {
                    filename: filename
                },
                success: function (data) {
//                    console.log(data);
//                    return false;
                    let decodedData = $.parseJSON(data);
                    if(decodedData.state == 0) {
                        $.each(decodedData.lines, function (item, value) {
                            $("#error_table > tbody").append("<tr>" +
                                "<td>" + value.number + "</td>" +
                                "<td>" + value.data + "</td>" +
                                "<td>" + value.errors + "</td>" +
                                "<td>" +
                                "<a class='delete' onclick='addIgnoreLine(" + value.number + ", this)'>"+
                                "<i class='icon-remove'></i>" +
                                "</a></td>" +
                                "</tr>");
                        });

                        let errorTableUI = $(".error-div").html();
                        $(".error-div").remove();
                        let confirmResult = bootbox.confirm(errorTableUI, "Cancel", "Save and submit", function(result) {
                            if(result == true) {
                                $("#form3").prepend("<input type='hidden' name='ignore_lines' value='" + ignoredLines.join(',') + "'/>");
                                flag = true;
                                $("#form3").submit();
                                return true;
                            } else {
                                location.href = "<?php echo $this->webroot; ?>prresource/gatewaygroups/view_egress";
                            }
                        });

                        return false;
                    } else {
                        flag = true;
                        $("#form3").submit();
                        return true;
                    }
                },
                async:false
            });
        }
        return false;
    }

    $(function() {
        $("#form3").submit(function () {
            var file = $("#myfile3_completedMessage").html();

            if (!file) {
                jQuery.jGrowlError('You should select a file!');
                return false;
            } else if (flag == false && checkFile() == false) {
                return false;
            } else {
                return true;
            }
        });
    });

</script>