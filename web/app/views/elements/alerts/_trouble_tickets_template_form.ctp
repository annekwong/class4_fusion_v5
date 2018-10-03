<form method="post">
    <table class="list table table-condensed">
        <tr>
            <td><?php __('Name')?></td>
            <td>
                <input type="text" class="validate[required]" style="width:320px;"  name="name" value="<?php echo isset($template) ? $template['TroubleTicketsTemplate']['name'] : '' ?>" />
            </td>
        </tr>
        <tr>
            <td><?php __('Title')?></td>
            <td>
                <input type="text" class="validate[required]" style="width:320px;"  name="title" value="<?php echo isset($template) ? $template['TroubleTicketsTemplate']['title'] : '' ?>" />
            </td>
        </tr>
        <tr>
            <td><?php __('Content')?></td>
            <td>
                <textarea name="content" class="validate[required]" style="width:500px;height:200px;min-width: 500px;"><?php echo isset($template) ? $template['TroubleTicketsTemplate']['content'] : '' ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="buttons-group center">
                <input type="submit" value="Submit" class="btn btn-primary" />
                <input type="reset" value="Revert" class="btn btn-default" />
            </td>
        </tr>
    </table>
</form>