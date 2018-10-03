<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name></x:Name><x:WorksheetOptions><x:Selected/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->
 
</head>
<body>
<table border="1">
        <tr>
            <td><?php __('DID')?></td>
            <td><?php __('DID Vendor')?></td>
            <td><?php __('DID Client')?></td>
            <td><?php __('Created Time')?></td>
            <td><?php __('Assigned Time')?></td>
            <td><?php __('Country')?></td>
            <td><?php __('State')?></td>
            <td><?php __('City')?></td>
        </tr>
        <?php foreach($this->data as $item): ?>
        <tr>
            <td><?php echo $item['DidRepos']['number']; ?></td>
            <td><?php echo @$ingresses[$item['DidRepos']['ingress_id']]; ?></td>
            <td><?php echo @$item['DidRepos']['egress_id'] ? $egresses[$item['DidRepos']['egress_id']] : ''; ?></td>
            <td><?php echo $item['DidRepos']['created_time']; ?></td>
            <td><?php echo $item['DidRepos']['updated_time']; ?></td>
            <td><?php echo $item['DidRepos']['country']; ?></td>
            <td><?php echo $item['DidRepos']['state']; ?></td>
            <td><?php echo $item['DidRepos']['city']; ?></td>
        </tr>
        <?php endforeach; ?>
</table>
</body>
</html>