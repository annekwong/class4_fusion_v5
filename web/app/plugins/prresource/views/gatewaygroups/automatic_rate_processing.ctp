<style type="text/css">
    input.subline {
        border:none;background:transparent none;border-bottom:1px solid #000;
    }
    #info_panel {
        overflow:hidde;
    }
    #info_panel p {
        padding:10px;
    }
    #formats {
        float:left;
    }
    #buttongroup {
        float:left;
        margin-left: 20px;

    }
    #buttongroup input {
        margin-top:20px;
        display:block;
    }
    .orders select {
        width:80px;
    }
</style>

<div id="title">
    <h1>
        <?php __('Carrier')?> [<?php echo $client_name ?>] &gt;&gt;<?php __('Edit Egress')?> <font title="Name" class="editname">[<?php echo $name ?>]</font>
    </h1>
</div>

<div id="container">
    <?php echo  $this->element('egress_tab',array('active_tab'=>'autorate'));?>
    <div id="info_panel">
        <form name="myfrm" method="post" id="myfrm">
            <p>
                <span><?php __('From Email')?></span>
                <input type="text" name="from_email" class="subline" value="<?php echo isset($data['from_email']) ? $data['from_email'] : '' ?>" />
            </p>
            <p>
                <?php __('Auto End Date')?> <input type="text" name="day" class="subline" style="width:30px;" value="<?php echo isset($data['day']) ? $data['day'] : '' ?>" /> <?php __('days after rate submission')?>.
            </p>
            <p>
                <?php __('Valid Date Starts From')?> <input type="text" name="start_line" class="subline" style="width:30px;" value="<?php echo isset($data['start_line']) ? $data['start_line'] : '' ?>" />
            </p>
            <p>
                <?php __('Number of Column')?> <input type="text" id="number_column" name="number_column" class="subline" style="width:30px;" value="<?php echo isset($data['number_column']) ? $data['number_column'] : '8' ?>" />
            </p>
            <table class="orders list">
                <tr>
                    <td><?php __('Country')?></td>
                    <td>
                        <select name="orders[]" control="<?php echo isset($data['format']) ? array_search('country', $data['format']) + 1 : '' ?>">
                        </select>
                    </td>
                    <td><?php __('Code Name')?></td>
                    <td>
                        <select name="orders[]" control="<?php echo isset($data['format']) ? array_search('code_name', $data['format']) + 1 : '' ?>">
                        </select>
                    </td>
                    <td><?php __('Code')?></td>
                    <td>
                        <select name="orders[]" control="<?php echo isset($data['format']) ? array_search('code', $data['format']) + 1 : '' ?>">
                        </select>
                    </td>
                    <td><?php __('Rate')?></td>
                    <td>
                        <select name="orders[]" control="<?php echo isset($data['format']) ? array_search('rate', $data['format']) + 1 : '' ?>">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php __('Interstate Rate')?></td>
                    <td>
                        <select name="orders[]" control="<?php echo isset($data['format']) ? array_search('inter_rate', $data['format']) + 1 : '' ?>">
                        </select>
                    </td>
                    <td><?php __('Intrastate Rate')?></td>
                    <td>
                        <select name="orders[]" control="<?php echo isset($data['format']) ? array_search('intra_rate', $data['format']) + 1 : '' ?>">
                        </select>
                    </td>
                    <td><?php __('Local Rate')?></td>
                    <td>
                        <select name="orders[]" control="<?php echo isset($data['format']) ? array_search('local_rate', $data['format']) + 1 : '' ?>">
                        </select>
                    </td>
                    <td><?php __('Effective Date')?></td>
                    <td>
                        <select name="orders[]" control="<?php echo isset($data['format']) ? array_search('effective_date', $data['format']) + 1 : '' ?>">
                        </select>
                    </td>
                </tr>
            </table>
            <!--
            <p>
                <select id="formats" name="formats[]" multiple="multiple" style="width:160px;height:150px;">
                    <?php if(isset($data['format'])): ?>
                    <?php foreach($data['format'] as $item): ?>
                    <option value="<?php echo $item ?>"><?php echo $cols[$item]; ?></option>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <option value="country">Country</option>
                    <option value="code_name">Code Name</option>
                    <option value="code">Code</option>
                    <option value="rate">Rate</option>
                    <option value="inter_rate">Interstate Rate</option>
                    <option value="intra_rate">Intrastate Rate</option>
                    <option value="local_rate">Local Rate</option>
                    <option value="effective_date">Effective Date</option>
                    <?php endif; ?>
                </select>
                <div id="buttongroup">
                    <input type="button" id="arrow_up" value="Up">
                    <input type="button" id="arrow_down" value="Down">
                </div>
                <br style="clear: both; height:1%;" />
            </p>
            -->
            <p>
                <input type="radio" class="end_date_type" name="end_date_type" value="0" <?php echo isset($data['end_date_type']) && $data['end_date_type'] == 0 ? "checked" : '' ?> />
                <?php __('End Date All Codes')?>
                <input type="radio" class="end_date_type" name="end_date_type" value="1" <?php echo isset($data['end_date_type']) && $data['end_date_type'] == 1 ? "checked" : '' ?> />
                <?php __('End Date Uploaded Codes')?>
            </p>
            <p style="<?php echo isset($data['end_date_type']) && $data['end_date_type'] == 1 ? "display:none;" : 'display:block' ?>">
                <input type="radio" name="end_date_when" value="0" <?php echo isset($data['end_date_when']) && $data['end_date_when'] == 0 ? "checked" : '' ?> />
                <?php __('Set End Date to 7 days after rate is received')?>
                <input type="radio" name="end_date_when" value="1" <?php echo isset($data['end_date_when']) && $data['end_date_when'] == 1 ? "checked" : '' ?> />
                <?php __('Set End Date to right before the earliest effective date')?>
            </p>
            <p style="text-align:center;">
                <input type="submit" value="<?php __('Submit')?>" />
            </p> 
        </form> 
    </div>
       
</div>

<script>

$(function() {
     $('#arrow_up, #arrow_down').click(function() {
        var $opt = $("#formats option:selected:first");
        if (!$opt.length) return;
        if (this.id == "arrow_up") $opt.prev().before($opt);
        else $opt.next().after($opt);
    });
    
    
    $('#number_column').keyup(function() {
        var num = parseInt($(this).val());
        var elements = new Array();
        for (var i = 1; i <= num; i++)
        {
            elements.push('<option>' + i + '</option>');
        }
        var element = elements.join('');
        $('.orders select').wrapInner(element);
        $('.orders select').each(function() {
            var $this = $(this);
            var control = $this.attr('control');
            if(control)
            {
                $this.find('option[value=' + control +']').attr('selected', true);
            }
                
        });
    }).trigger('keyup');
    
    $('.end_date_type').change(function() {
        var $this = $(this);
        if($this.val() == 0)
        {
           $this.parent().next().show();
        }
        else
        {
           $this.parent().next().hide();
        }
    });
    
    $('#myfrm').submit(function() {
        var flag = true;
        var email = $('input[name=from_email]').val();
        var re=/^[\w-]+(\.[\w]+)*@([\w-]+\.)+[a-zA-z]{2,7}$/;
        if (email == '' || !email.match(re))
        {
            showMessages("[{'code':'101','msg':'Invalid Email Address'}]");
            flag = false;
        }
        re=/^[-]?\d*\.?\d*$/;
        var day = $('input[name=day]').val();
        var start_line = $('input[name=start_line]').val();
        var number_column = $('input[name=number_column]').val();
        if (!day.match(re))
        {
            showMessages("[{'code':'101','msg':'The Field Auto End Date day must be numeric only'}]");
            flag = false;
        }
        if (!start_line.match(re))
        {
            showMessages("[{'code':'101','msg':'The Field Valid Date Starts From  must be numeric only'}]");
            flag = false;
        }
        if (!number_column.match(re))
        {
            showMessages("[{'code':'101','msg':'The Field Number of Column must be numeric only'}]");
            flag = false;
        }
        
        if (!flag)
            return false;
        $('#formats option').attr('selected', true);
    });
});
                       
</script>