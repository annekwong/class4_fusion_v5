<style>
    .transition {
        -webkit-transition: all 1s;
        -moz-transition: all 1s;
        -o-transition: all 1s;
        transition: all 1s;
        transition-timing-function: ease-in-out;
        -moz-transition-timing-function: ease-in-out;
        -webkit-transition-timing-function: ease-in-out;
        -o-transition-timing-function: ease-in-out;
    }
</style>
<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Origination</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Order New Number</li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Order New Number</h4>
</div>

<div class="innerLR">
    <!--NEW HTML-->
    <div class="widget widget-body-white">
        <div class="widget-body" id="app">
<!--            <div class="field-group">-->
<!--                <label>Country</label>-->
<!--                <select name="country" id="country" v-model="country" v-on:change="formChange">-->
<!--                    <option value="">All</option>-->
<!--                    --><?php //foreach ($countries as $country): ?>
<!--                        <option value="--><?php //echo $country['Jurisdiction']['name']; ?><!--">--><?php //echo $country['Jurisdiction']['name'] ?><!--</option>-->
<!--                    --><?php //endforeach ?>
<!--                </select>-->
<!--            </div>-->
            <div class="field-group">
                <label>Search By</label>
                <select name="searchBy" id="searchBy" v-model="searchBy" v-on:change="formChange">
                    <option value="0" selected>Local</option>
                    <option value="1">Toll Free</option>
                </select>
            </div>
            <div class="field-group" v-if="searchBy == 1">
                <label>Toll Free Prefix</label>
                <select name="prefix" id="prefix" v-model="prefix" v-on:change="formChange">
                    <option v-for="item in prefixes" v-bind:value="item">{{item}}</option>
                </select>
            </div>
            <div class="field-group" v-if="numbers.length > 0">
                <label>Select number</label>
                <table class="table table-striped table-bordered table-primary list">
                    <thead>
                        <tr>
                            <th></th>
                            <th>DID</th>
<!--                            <th>Country</th>-->
<!--                            <th>Type</th>-->
<!--                            <th>State</th>-->
                            <th>One Time Fee</th>
                            <th>Monthly Fee</th>
                            <th>Per Min Fee</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in numbers">
                            <td><input type="checkbox" name="codes[]" v-model="number" :value="item[0].id"></td>
                            <td>{{item[0].did}}</td>
<!--                            <td>{{item[0].country}}</td>-->
<!--                            <td>{{item[0].jur_type}}</td>-->
<!--                            <td>{{item[0].state}}</td>-->
                            <td>{{item[0].one_time_fee}}</td>
                            <td>{{item[0].monthly_fee}}</td>
                            <td>{{item[0].per_min_fee}}</td>
                            <td>
                                <a v-if="item[0].client_rule_rate_type == 2 || item[0].client_rule_rate_type == 3" title="View Rates" v-bind:href="'<?php echo $this->webroot?>did_client/downloadRates/' + item[0].id" target="_blank">
                                    <i class="icon-download"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
<!--                <select name="number" id="number" v-model="number">-->
<!--                    <option v-for="item in numbers" v-bind:value="item[0].code">{{item[0].code}}</option>-->
<!--                </select>-->
            </div>
            <div class="field-group" v-if="number.length > 0">
                <button class="btn btn-primary" v-on:click="order">Order number</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue"></script>
<script>

    var Vue = new Vue({
        el: "#app",
        data: {
            searchBy: 0,

            prefix: '1800',
            numbers: [],
            number: [],
            prefixes: ['1800', '1888', '1877', '1866', '1855', '1844'],
            country: ''
        },
        methods: {
            formChange: function () {
                $.ajax({
                    url: "<?php echo $this->webroot; ?>did_client/ajaxGetOrderDids",
                    data: {
                        searchBy: Vue.searchBy,
                        prefix: Vue.prefix,
                        country: Vue.country
                    },
                    method: 'POST',
                    success: function (response) {
                        let data = $.parseJSON(response);
                        Vue.numbers = data;
                    }
                });
            },
            order: function () {
                $.ajax({
                    url: "<?php echo $this->webroot; ?>did_client/order_new_number",
                    data: {
                        number: Vue.number,
                    },
                    method: 'POST',
                    success: function (response) {
                        if (response == 1) {
                            showMessages_new("[{'code':201,'msg':'DID number has been purchased successfully.'}]");
                            Vue.formChange();
                        } else {
                            showMessages_new("[{'code':101,'msg':'Error! Please try again..'}]");
                        }
                    }
                });
            }
        }
    });

    $(document).ready(function () {
        Vue.formChange();
    });

</script>