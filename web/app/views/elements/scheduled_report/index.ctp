<td id="scheduled_report_td">
                                <input type="hidden" value="" name="scheduled_report[report_name]" id="scheduled_report_name" />
                                <input type="hidden" value="" name="scheduled_report[subject]" id="scheduled_subject" />
                                <input type="hidden" value="" name="scheduled_report[frequency_type]" id="scheduled_frequency_type" />
                                <input type="hidden" value="" name="scheduled_report[month]" id="scheduled_month" />
                                <input type="hidden" value="" name="scheduled_report[week]" id="scheduled_week" />
                                <input type="hidden" value="" name="scheduled_report[time]" id="scheduled_time" />
                                <input type="hidden" value="" name="is_scheduled_report" id="is_scheduled_report" />
                                <input type="hidden" value="" name="scheduled_report[interval]" id="scheduled_interval" />
                                <input type="button" id="scheduled_report" value="<?php echo __('Scheduled Report', true); ?>" class="btn btn-primary margin-bottom10">
                            </td>
                    <script type="text/javascript">
                        $(function() {
                            $("#scheduled_report").click(function() {
                                if (!$('#dd').length) {
                                    $(document.body).append("<div id='dd'></div>");
                                }
                                var $dd = $('#dd');
                                var $form = null;
                                var report_name = "<?php echo $report_name; ?>";
                                $dd.load('<?php echo $this->webroot; ?>scheduled_report/ajax_option', {'report_name': report_name},
                                function(responseText, textStatus, XMLHttpRequest) {
                                    $dd.dialog({
                                        'width': '450px',
                                        'create': function(event, ui) {
                                            $form = $('form', $dd);
                                            $form.validationEngine();
                                        },
                                        'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
                                                    $form = $('form', $dd);
                                                    
                                                    if ($form.validationEngine('validate'))
                                                    {
                                                        var scheduled_report_report_name =  $("input[name='scheduled_report_report_name']").val();
                                                        $("#scheduled_report_name").val(scheduled_report_report_name);
                                                        var scheduled_report_subject =  $("input[name='scheduled_report_subject']").val();
                                                        $("#scheduled_subject").val(scheduled_report_subject);
                                                        var scheduled_report_frequency_type =  $("#scheduled_report_frequency_type").val();
                                                        $("#scheduled_frequency_type").val(scheduled_report_frequency_type);
                                                        var scheduled_report_month =  $("#scheduled_report_month").val();
                                                        $("#scheduled_month").val(scheduled_report_month);
                                                        var scheduled_report_week =  $("#scheduled_report_week").val();
                                                        $("#scheduled_week").val(scheduled_report_week);
                                                        var scheduled_report_time =  $("#scheduled_report_time").val();
                                                        $("#scheduled_time").val(scheduled_report_time);
                                                        var scheduled_report_interval =  $("#scheduled_report_interval").val();
                                                        $("#scheduled_interval").val(scheduled_report_interval);
                                                        $(".scheduled_report_email_to").each(function(){
                                                            $email_item = $(this).val();
                                                            if($email_item)
                                                            {
                                                                $("#scheduled_report_td").append("<input type='hidden' value='"+$email_item+"' name='scheduled_report[email_to][]' />");
                                                            }
                                                        });
                                                        $("#is_scheduled_report").val(1);
                                                        $(this).dialog("close");
                                                        $(".scheduled_report_form").submit();
                                                    }
                                                }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                                                    $(this).dialog("close");
                                                }}]

                                    });
                                }
                                );


                            });
                        });
                    </script>