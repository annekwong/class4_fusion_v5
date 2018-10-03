<div class="row-fluid">
    <form action="<?php echo $this->webroot; ?>logging/add_notes" method="post">
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <tr>
                <th><?php __('NOTE(You can for you just operation note)')?>:</th>
            <input type="hidden" name="log_id" value="<?php echo $log_id; ?>"  />
            <input type="hidden" name="path" value="<?php echo $path; ?>"  />
            </tr>
            <tr>
                <td>
                    <textarea name="content" cols="100" rows="5" > </textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary">
                    <input class="input in-button btn btn-default" type="reset" style="margin-left: 20px;" value="<?php __('Revert')?>">
                </td>
            </tr>

        </table>
    </form>
</div>