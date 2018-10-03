<div style="height: 200px;"></div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white center">
        <input type="hidden" style="width: 220px;"  class="js-data-example-ajax" />
        <!--        <select class="js-data-example-ajax">-->
        <!--            <option value="3620194" selected="selected">select2/select2</option>-->
        <!--        </select>-->
    </div>
</div>

<script type="text/javascript">
    function formatRepo (repo) {
        if (repo.loading) return repo.text;

        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";

        if (repo.description) {
            markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
        }

        markup += "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
            "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
            "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
            "</div>" +
            "</div></div>";

        return markup;
    }

    function formatRepoSelection (repo) {
        return repo.full_name || repo.text;
    }
    $(function(){
//        $(".js-data-example-ajax").select2({
//            ajax: {
//                url: "<?php //echo $this->webroot; ?>//routestrategys/ajax_get_static_route",
//                dataType: 'json',
//                delay: 250,
//                data: function (params) {
//                    return {
//                        search: params,
//                    };
//                },
//                cache: true
//            },
//            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
//            minimumInputLength: 1,
//            templateResult: formatRepo, // omitted for brevity, see the source of this page
//            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
//        });



        $(".js-data-example-ajax").select2({
            minimumInputLength: 1,
            initSelection: function (element, callback) {   // 初始化时设置默认值
                var $st_route_name = $("#myModal_AddRoute").find(".st_route_name").val();
                var data = [{id: element.val(), text: $st_route_name}];
                callback({id: element.val(), text: $st_route_name});
            },
            id : function(item) {
                return item.id;
            },
            formatSelection: function (item) { return item.text || item.id; },
            formatResult: function (item) { return item.text; },
            ajax : {
                url      : "<?php echo $this->webroot; ?>routestrategys/ajax_get_static_route",
                dataType : "json",
                quietMillis:500,
                data     : function (term, page) {
                    return { search: term };
                },
                results: function (data,page) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.Product.product_id,
                            'text': item.Product.name
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                escapeMarkup : function (m) { return m; }
            }
        });
    })



</script>