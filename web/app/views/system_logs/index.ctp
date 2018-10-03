<style type="text/css">
    #log_window {overflow: auto;}
</style>
<div id="title">
    <h1><?php echo __('Log', true); ?>&gt;&gt;<?php echo __($sub_title, true); ?></h1>
</div>

<div id="container">
    <?php echo $this->element('system_logs/sub_menu',array('active' => $current_log)); ?>
    <pre><div id="log_window"><?php echo $error; ?></div></pre>
</div>

<script>
    $(function() {
        var $log_window = $('#log_window');
        var $window = $(window);
        var winH = $window.height(); //页面可视区域高度
        var tell = 0;
        var log_file = "<?php echo $log_file ?>";
        
        function fetch()
        {
            $window.unbind('scroll');
            $.ajax({
                'url': "<?php echo $this->webroot ?>system_logs/fetch_log",
                'type' :'POST',
                'async' : false,
                'dataType' : 'json',
                'data' : {tell:tell, log_file:log_file},
                'success' : function(json){
                    if(json){
                        if (json.tell == 0)
                        {
                            return false;
                        }
                        tell = json.tell;
                        $log_window.append(json.content);
                    }else{
                        return false;
                    }
                    $window.bind('scroll', scroll_event);
                }
            });
        }
        
        function scroll_event()
        {
            var pageH = $(document.body).height();
            var scrollT = $window.scrollTop(); //滚动条top
            var aa = (pageH-winH-scrollT)/winH;
            if(aa<0.02){
                fetch();
            }
        }
        $window.bind('scroll', scroll_event);
        fetch();
    });
</script>