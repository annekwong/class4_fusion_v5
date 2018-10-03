<?php
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>' ."\n";
echo $content_for_layout;
?>