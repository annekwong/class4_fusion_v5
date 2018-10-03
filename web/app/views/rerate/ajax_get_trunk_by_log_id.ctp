<table class="table table-bordered">
    <?php foreach($data as $data_item): ?>
        <tr>
            <td>
                <?php echo $data_item[0]['alias']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>