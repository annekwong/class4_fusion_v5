<style type="text/css">
    #myform {
        width:800px;
        margin:0 auto;
    }    
    #myform label {
        float:left;
        width:200px;
        text-align:right;
        padding-right:50px;
    }
    #myform input.input {
        width:300px;
    }
    #myform p.submit {
        text-align:center;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Edit sending template') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Edit sending template') ?></h4>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rates/rate_templates"><i></i><?php __('Back')?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>rates/rate_sending" class="glyphicons list">
                        <i></i><?php __('Rate sending')?>   		
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>rates/rate_templates" class="glyphicons cogwheel">
                        <i></i><?php __('Template')?>  		
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>rates/rate_sending_logging" class="glyphicons book_open">
                        <i></i><?php __('Log')?>  		
                    </a>
                </li>
            </ul>
        </div>
        <div id="container" class="widget-body">

            <div id="myform">
                <form method="post">
                    <p>
                        <label><?php __('Name')?>:</label>
                        <input type="text" name="name" value="<?php echo $data[0][0]['name']; ?>" />
                    </p>
                    <p>
                        <label><?php __('Suject')?>:</label>
                        <input type="text" name="subject" value="<?php echo $data[0][0]['subject']; ?>" />
                    </p>
                    <p>
                        <label><?php __('Content')?>:</label>
                        <textarea name="content" style="width:500px;height:100px;"><?php echo $data[0][0]['content']; ?></textarea>
                    </p>
                    <p style="text-align:center;">
                        <input type="submit" style="width:auto;" class="btn btn-primary" value="<?php __('Submit')?>" />
                    </p>
                </form>
            </div>

        </div>