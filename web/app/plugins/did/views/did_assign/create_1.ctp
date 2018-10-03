<style type="text/css">
    #add_panel section {
        text-align: center;
    }

    #add_panel section p {
        display: inline;
    }
    #number_section {
        display:table;
        text-align:center;
        margin:0 auto;
        margin-top:10px;
    }
   
    #number_other {
        display:none; 
        text-align:center;
        padding:10px;
    }
   
    #number_section ul {
       list-style: none;
    }
    #number_section ul li {
        float:left;
    }
    #number_section ul li label {
        width:120px;
        height:20px;
        line-height:20px;
        cursor:pointer;
    }
    #number_section ul li input{
        margin-left:10px;
        line-height:20px;
    }
</style>

<div id="title">
    <h1>
        <?php echo __('Origination',true);?>
        &gt;&gt;
        <?php echo __('Egress DID Assignment',true);?>
    </h1>
	<ul id="title-menu">
		<li><a class="link_back_new"
			href="<?php echo $this->webroot ?>did/did_assign"> <img width="16"
				height="16" alt="Back"
				src="<?php echo $this->webroot ?>images/icon_back_white.png">&nbsp;Back
		</a>
		</li>
	</ul>
</div>

<div id="container">
    <div id="add_panel">
        <fieldset>
            <legend><?php __('Search')?>:</legend>
            <section>
                <p>
                    <label for="country"><?php __('Country')?>:</label> 
                    <input type="text" id="country" name="country" />
                </p>
                <p>
                    <label for="state"><?php __('State')?>:</label> 
                    <input type="text" id="state" name="state" />
                </p>
                <p>
                    <label for="city"><?php __('City')?>:</label> 
                    <input type="text" id="city" name="city" />
                </p>
                <p>
                    <label for="rate"><?php __('Rate Center')?>:</label> 
                    <input type="text" id="rate_center" name="rate_center" />
                </p>
                <p>
                    <label for="number"><?php __('Number')?>:</label> 
                    <input type="text" id="number" name="number" />
                </p>
                <p>
                    <input type="button" id="search" value="Query" />
                </p>
            </section> 
            <div id="number_other">
            <?php __('Assign to')?>
            <select name="egress_id" id="egress_id">
                <?php foreach($egresses as $key => $egress): ?>
                <option value="<?php echo $key; ?>">
                    <?php echo $egress ?>
                </option>
                <?php endforeach; ?>
            </select>
            &nbsp;&nbsp;
            <input type="submit" id="sub" value="<?php __('Submit')?>" />
            </div>
        </fieldset>
        <section id="number_section">
            <ul>
            </ul>
        </section>
    </div>
</div>
<div id="loading"></div>

<script>
    $(function() {
        var $number_list = $('#number_section ul');
        var $loading = $('#loading');
	
        function get_data()
        {
            $.ajax({
                'url' : "<?php echo $this->webroot ?>did/did_assign/search_number",
                'type' : 'POST',
                'dataType' : 'json',
                'data' : {"country":$('#country').val(),
                    "state":$('#state').val(), "city":$('#city').val(), "rate_center":$('#rate_center').val(), "number" : $("#number").val()},
                'success' : function(data) {
                    $loading.hide();
                    if(data)
                    {
                        $.each(data, function(index, value){
                            $number_list.append("<li><label><input type='checkbox' number='"+ value[0]['number'] +"' />" + value[0]['number'] + "</label></li>")
                        });
                        $('#number_other').show();
                        //$('fieldset').hide();
                    }
                },
                'beforeSend' : function() {
                    $loading.show();
                }
            });
        }
	
        $('#search').click(function() {
            $number_list.empty();
            get_data();
        });



        $('#sub').click(function() {
            var numbers = new Array();
            $('input:checked', $number_list).each(function() {
                numbers.push($(this).attr('number'));
            });
            var egress_id = $('#egress_id').val();
            $.ajax({
                'url' : "<?php echo $this->webroot ?>did/did_assign/assign",
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {"numbers[]":numbers, "egress_id":egress_id},
                'success' : function(data) {
                    $loading.hide();
                    if(data.indexOf("true") != -1)
                    {
                        jGrowl_to_notyfy("The numbers [" + numbers + "] are assigned successfully",{theme:'jmsg-success'});
                        return false;
                    }
                },
                'beforeSend' : function() {
                    $loading.show();
                }
            });
        });
	
 
    });
</script>
