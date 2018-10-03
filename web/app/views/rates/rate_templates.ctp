
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate sending template') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate sending template') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>rates/rate_sending" class="glyphicons list">
                        <i></i><?php __('Rate sending') ?>   		
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>rates/rate_templates" class="glyphicons cogwheel">
                        <i></i><?php __('Template') ?>   		
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>rates/rate_sending_logging" class="glyphicons book_open">
                        <i></i><?php __('Log') ?>  		
                    </a>
                </li>
            </ul>
        </div>
        <div id="container" class="widget-body">

            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php __('Name') ?></th>
                        <th><?php __('Subject') ?></th>
                        <th><?php __('Content') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $item): ?>
                        <tr>
                            <td><?php echo $item[0]['name']; ?></td>
                            <td><?php echo $item[0]['subject']; ?></td>
                            <td><?php echo substr($item[0]['content'], 0, 20);
                    ; ?></td>
                            <td>
                                <a href="<?php echo $this->webroot ?>rates/edit_template/<?php echo $item[0]['id']; ?>">
                                    <i class="icon-edit"></i>
                                </a> 
                                <a href="<?php echo $this->webroot ?>rates/delete_template/<?php echo $item[0]['id']; ?>">
                                    <i class='icon-remove'></i>
                                </a>
                            </td>
                        </tr>
<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>