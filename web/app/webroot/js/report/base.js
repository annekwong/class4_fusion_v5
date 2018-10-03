'use strict';

class ReportBase {

    /**
     * Constructor
     * @param url
     * @param form
     * @param fields - associative array of fields
     */
    constructor(url, form, fields = {}, sortFields = null) {
        this.form = form;
        this.url = url;
        this.fieldsTemplate = this._getFieldsTemplate();
        this.replaceFields = fields;
        this.sortFields = sortFields;
    }

    /**
     * Ajax request
     * @param data
     * @param method
     * @param callback
     */
    request(data, method = 0, callback = null) {
        let _this = this;

        $.ajax({
            url: this.url,
            method: method == 0 ? 'POST' : 'GET',
            data: data,
            success: function (response) {
                response = JSON.parse(response);

                if (response.code == 200) {
                    response = response.data;
                    _this.data = _this._convertFields(response);
                    if (typeof callback === 'function') {
                        callback();
                    }
                } else {
                    _this.data = [];
                    _this.notify(101, response.msg);
                }
            },
            xhr: this._onLoading
        });
    }

    /**
     * Initialize data
     * @param callback
     */
    initData(callback) {
        this._convertForm();
        this.request(this.formObject, 0, callback);
    }

    /**
     * Downloading file in csv format
     */
    getCsv() {
        this._getFile(1);
    }

    /**
     * Downloading file in xls format
     */
    getXls() {
        this._getFile(2);
    }

    /**
     * Shows notify message
     * @param code
     * @param message
     */
    notify(code, message) {
        var theme = "jmsg-error";

        if (code == 200) {
            theme = "jmsg-success";
        }

        $.jGrowl(message, {theme: theme});
    }

    /**
     * Returns fields template
     * @returns {*}
     */
    get fields() {
        return this.fieldsTemplate;
    }

    /**
     * Returns completed data
     * @returns {*}
     */
    getData() {
        return this.data;
    }

    _convertFields(data) {
        let _this = this;

        data.forEach(function (item, key) {
            for (var field in _this.replaceFields) {
                if (data[key][field] !== undefined) {
                    let value = data[key][field];
                    delete data[key][field];
                    data[key][_this.replaceFields[field]] = value;
                }
            }
        });

        if (this.sortFields) {
            let sortedArray = [];

            data.forEach(function (item, key) {
                let tempArray = {};

                for (var sortField in _this.sortFields) {
                    if (item[_this.sortFields[sortField]] !== undefined) {
                        tempArray[_this.sortFields[sortField]] = item[_this.sortFields[sortField]];
                    }
                }
                sortedArray.push(tempArray);
            });
            data = sortedArray;
        }
        return data;
    }

    /**
     * Convert form data to API format
     * @param callback
     * @private
     */
    _convertForm(callback = false) {
        var result = {};

        this.formObject = this.form.serializeObject();
        let start = this.formObject.start_date + "T" + this.formObject.start_time + ".804Z";
        let end = this.formObject.stop_date + "T" + this.formObject.stop_time + ".804Z";
        let timezone = this.formObject["query[tz]"];

        result.start_time = Math.floor(new Date(start).getTime() / 1000);
        result.end_time = Math.floor(new Date(end).getTime() / 1000);
        result.format = 'json';
        result.output = 5;
        result.fields = this.formObject["query[fields][]"];
        result.tz = parseInt(timezone) / 100;

        if (this.formObject['group_by_date'] !== undefined && this.formObject['group_by_date'].length > 0) {
            result.time = this.formObject['group_by_date'];
        }
        if (this.formObject['orig_src_number'] !== undefined && this.formObject['orig_src_number'].length > 0) {
            result.dest_number = this.formObject['orig_src_number'];
        }
        if (this.formObject['query[src_number]'] !== undefined && this.formObject['query[src_number]'].length > 0) {
            result.source_number = this.formObject['query[src_number]'];
        }
        if (this.formObject['query[country]'] !== undefined && this.formObject['query[country]'].length > 0) {
            result.orig_code = this.formObject['query[country]'];
        }
        let groupBy = [];

        if (typeof this.formObject['group_select[]'] === "object") {
            this.formObject['group_select[]'].forEach(function (item, key) {
                if (item.length > 0) {
                    groupBy.push(item);
                }
            });
        } else {
            groupBy.push(this.formObject['group_select[]']);
        }

        if (groupBy.length > 0) {
            result.group = groupBy.join(',');
        }

        if (this.formObject['ingress_alias[]'] !== undefined) {
            let ingresses = [];

            if (typeof this.formObject['ingress_alias[]'] === 'Array') {
                this.formObject['ingress_alias[]'].forEach(function (item, key) {
                    if (item.length > 0) {
                        ingresses.push(item);
                    }
                });
            } else {
                ingresses.push(this.formObject['ingress_alias[]']);
            }

            if (ingresses.length > 0) {
                result.ingress_id = ingresses.join(',');
            }
        }
        if (this.formObject['egress_alias[]'] !== undefined) {
            let egresses = [];

            if (typeof this.formObject['egress_alias[]'] === 'Array') {
                this.formObject['egress_alias[]'].forEach(function (item, key) {
                    if (item.length > 0) {
                        egresses.push(item);
                    }
                });
            } else {
                egresses.push(this.formObject['egress_alias[]']);
            }
            if (egresses.length > 0) {
                result.egress_id = egresses.join(',');
            }
        }
        this.formObject = result;
    }

    /**
     * Shows progress of loading data
     * @param object
     * @returns {*}
     * @private
     */
    _onLoading(object) {
        var xhr = $.ajaxSettings.xhr();

        xhr.onprogress = function (event) {
            var percent = Math.ceil(event.loaded / event.total * 100);
            $("#progress").css('width', percent + "%").parent().show();
        }

        xhr.onload = xhr.onerror = function () {
            $("#progress").parent().hide();
        };

        return xhr;
    }

    /**
     * This function contains human readable fields array.
     * @returns {string[]}
     * @private
     */
    _getFieldsTemplate() {
        let fields = {
            "asr_global": "ASR",
            "asr": "ASR",
            "asr_term": "ASR",
            "acd": "ACD",
            "acd_term": "ACD",
            "pdd_global": "PDD",
            "pdd": "PDD",
            "pdd_term": "PDD",
            "npr_count": "NPR Count",
            "npr_global": "NPR",
            "npr": "NPR",
            "npr_term": "NPR",
            "nrf_count": "NRF Count",
            "nrf": "NRF",
            "sd_count": "SD Count",
            "sdp": "SDP",
            "revenue": "Revenue",
            "revenue_term": "Revenue",
            "profit": "Profit",
            "margin": "Margin",
            "pp_min": "PP Min",
            "pp_k_calls": "PP K Calls",
            "ppka": "PPKA",
            "limited": "Limited",
            "total_duration": "Total Duration",
            "total_duration_term": "Total Duration",
            "total_billable_time": "Total Ingress Billable Time",
            "total_billable_time_term": "Total Egress Billable Time",
            "total_cost": "Total Ingress Cost",
            "total_cost_term": "Total Egress Cost",
            "inter_cost": "Inter Cost",
            "inter_cost_term": "Inter Cost",
            "intra_cost": "Intra Cost",
            "intra_cost_term": "Intra Cost",
            "local_cost": "Local Cost",
            "local_cost_term": "Local Cost",
            "ij_cost": "IJ Cost",
            "ij_cost_term": "IJ Cost",
            "average_rate": "Ingress Average Rate",
            "average_rate_term": "Egress Average Rate",
            "calls": "Total Calls",
            "total_calls": "Total Calls",
            "total_calls_term": "Total Calls",
            "not_zero_calls": "Not Zero Calls",
            "not_zero_calls_6": "Calls <= 6s",
            "not_zero_calls_6_percent": "Percentage of calls <= 6s",
            "not_zero_calls_30": "Calls <= 30s",
            "not_zero_calls_30_percent": "Percentage of calls <= 30s",
            "success_calls": "Success Calls",
            "busy_calls": "Busy Calls",
            "busy_calls_term": "Busy Calls",
            "total_calls_percent": "Percentage of calls (%)",
            "total_calls_percent_term": "Percentage of calls (%)",
            "total_duration_percent": "Percentage of duration (%)",
            "total_duration_percent_term": "Percentage of duration (%)",
            "margin_percent": "Percentage of margin (%)",
            'cancel_calls': 'Failed Calls',
            'failed_calls': 'Failed Calls',
            'egress_limit': 'Peak',
            'did_price': 'DID Price',
            'min_price': 'Min Price',
            'monthly_charge': 'Monthly Charge'
        };

        return fields;
    }

    /**
     * Downloading file
     * @param type
     * @private
     */
    _getFile(type) {
        let _this = this;
        var content = type == 1 ? "data:text/csv;charset=utf-8," : "data:application/vnd.ms-excel;charset=utf-8,";
        var delimiter = type == 1 ? "," : "\t";
        var filename = type == 1 ? "summary.csv" : "summary.xls";

        this._convertForm();
        this.request(this.formObject, 0, function () {
            let total_row_index = 'Total';
            if (_this.data.length == 0) {
                _this.notify(401, "No data found");
                return;
            }
            let objectKeys = Object.keys(_this.data[0]);

            let objectHeader = $.map(objectKeys, function(e){
                if (_this.fields[e] !== undefined) {
                    e = _this.fields[e];
                }
                return e;
            }).join(delimiter);
            content += objectHeader + "\n";
            if((typeof _this.data[_this.data.length -1]['egress_id'] !== 'undefined' && _this.data[_this.data.length -1]['egress_id'] == total_row_index) ||
                (typeof _this.data[_this.data.length -1]['time'] !== 'undefined' && _this.data[_this.data.length -1]['time'] == total_row_index)){
                delete _this.data[_this.data.length -1];
            }

            _this.data.forEach(function(infoArray, index) {
                let dataString = $.map(infoArray, function(e, i){
                    if ( objectKeys.indexOf(i)  > -1) {
                        return e;
                    }
                }).join(delimiter);
                content += index < _this.data.length ? dataString + "\n" : dataString;
            });

            var encodedUri = encodeURI(content);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", filename);
            document.body.appendChild(link);

            link.click();
            link.remove();
        });
    }
}

function saveFields(url, report) {
    $.ajax({
        url: url + "cdrapi/ajaxSaveFields",
        type: 'POST',
        format: 'JSON',
        data: {
            report: report,
            fields: $("#query-fields").val()
        },
        success: function (response) {
            console.log(response);
        }
    });
}