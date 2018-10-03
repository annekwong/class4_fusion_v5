<style>
    #container input {
        width:100px;
    }
</style>

<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Agents Egress Trunk') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">Agents Egress List</h4>
</div>
<div class="separator bottom"></div>
<?php
$status = array(
    0 => 'Inactive',
    1 => 'Active'
);
?>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get" id="myform1">
                    <!-- Filter -->
                    <div>
                        <label>Trunk Name:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn search_submit input in-submit">Query</button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
            <div id="container">
                <?php
                $data = $p->getDataArray();
                ?>
                <div id="toppage"></div>
                <?php
                if (count($data) == 0) {
                    ?>
                    <div class="msg"><?php echo __('no_data_found') ?></div>
                    <?php
                } else {
                    ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>

                            <tr>
                                <th>Id</th>
                                <th>Trunk Name</th>
                                <th>Client Name</th>
                                <th>Agent</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $item[0]['resource_id']; ?></td>
                                    <td><?php echo $item[0]['alias']; ?></td>
                                    <td><?php echo $item[0]['client_name']; ?></td>
                                    <td><?php echo empty($item[0]['agent_name']) ? $default_agent_name : $item[0]['agent_name']; ?></td>
                                    <td><?php echo $item[0]['active'] == 't' ? "Active" : "Inactive"; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
    <script>


    </script>

