<?php if (empty($logs)): ?>
    <div class="msg viewss">No data found</div>
<?php else: ?>
    <table class="list viewss">

        <tbody>
        <?php foreach($logs as $item): ?>
            <tr>
                <td>
                    <a title="Download" class="views cdr_download_link" target="_blank" href="<?php echo $this->webroot ?>cdrreports_db/export_log_item_down?key=<?php echo base64_encode($id);?>&file=<?php echo urlencode($item); ?>">
                        <i class="icon-download-alt"></i> <?php echo $item; ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
