'use strict';

class Report {

    constructor(webroot, url, form, optionalHeaders = null) {
        this.humanHeaders = this._getHumanHeadersArray();
        this.form = form;
        this.url = url;
        this.webroot = webroot;
        this.optionalHeaders = optionalHeaders;
        this.limit = 100;
        this.server = {
            config: {
                currentIndex: 0,
                limit: 1000
            }
        };

        if (this.optionalHeaders) {
            for (var item in this.optionalHeaders) {
                if (this.optionalHeaders[item].length > 0) {
                    this.humanHeaders[item] = this.optionalHeaders[item];
                }
            }
        }
    }

    initData(callback) {
        let _this = this;
        let data = this.form.serialize();
        let formObject = this.form.serializeObject();
        this._clearIndex();
        this.formObject = formObject;
        this._convertForm();
        this.showFields = formObject["query[fields][]"];

        // window.history.pushState('page2', 'Title', this.webroot + "cdrapi/summary_reports?" + data);

        $.ajax({
            url: this.url,
            method: 'POST',
            data: this.formObject,
            success: function (response) {
                if (response) {
                    let decodedResponse = JSON.parse(response);
                    if (decodedResponse.code == 200) {
                        decodedResponse.data = _this._convertDataValues(decodedResponse.data);
                        if (decodedResponse.data.length > 0) {
                            _this._increaseIndex();
                        }
                        if (decodedResponse.data.length > 0 && Object.keys(decodedResponse.data[decodedResponse.data.length - 1]).length === 0) {
                            decodedResponse.data.pop();
                        }
                        _this.data = decodedResponse.data;
                    } else {
                        _this.notify(101, 'Can\'t establish connection with API');
                        _this.data = decodedResponse.data;
                    }
                    callback();
                } else {
                    _this.notify(101, 'Can\'t establish connection with API');
                }
            },
            xhr: this._onLoading
        });
    }

    loadData(callback = null) {
        let _this = this;
        // this.formObject.from = this.server.config.currentIndex;
        // this.formObject.count = this.server.config.limit;

        $.ajax({
            url: this.url,
            method: 'POST',
            data: this.formObject,
            success: function (response) {
                let decodedResponse = JSON.parse(response);

                if (decodedResponse.code == 200) {
                    decodedResponse.data = _this._convertDataValues(decodedResponse.data);
                    if (decodedResponse.data.length > 0) {
                        _this._increaseIndex();
                    }
                    if (decodedResponse.data.length > 0 && Object.keys(decodedResponse.data[decodedResponse.data.length - 1]).length === 0) {
                        decodedResponse.data.pop();
                    }
                    _this.data = _this.data.concat(decodedResponse.data);
                } else {
                    _this.notify(101, 'Can\'t establish connection with API');
                    _this.data = _this.data.concat(decodedResponse.data);
                }
                callback();
            },
            xhr: this._onLoading
        });
    }

    getData(offset) {
        let data = this._getRecords(offset);
        let normalArray = this._parseData(data);
        return normalArray;
    }

    getMaxOffset() {
        return Math.ceil(this.data.length / this.limit);
    }

    getHeader() {
        return this.header;
    }

    initHeader(callback = null) {
        var _this = this;
        $.ajax({
            type: 'POST',
            data: {
                start_time: '-1',
                print_only_headers: 1
            },
            url: this.url,
            complete: function (jqXHR) {
                if (jqXHR.readyState === 4) {
                    let arrayHeaders = jqXHR.responseText.indexOf('?') !== -1 ? jqXHR.responseText.split('?') : jqXHR.responseText.split(',');

                    if (arrayHeaders.length > 0) {
                        arrayHeaders.pop();
                    }

                    _this.header = _this._getHumanHeaders(arrayHeaders);
                    callback();
                }
            }
        });
    }

    getCsv(callback = function () {
    }) {
        let _this = this;
        let formObject = this.form.serializeObject();
        this.formObject = formObject;
        this._convertForm();
        this.showFields = formObject["query[fields][]"];
        let formData = this.formObject;
        formData.format = 'csv';
        formData.output = 2;
        formData.field = this._implode(',', this.showFields);
        delete formData.count;
        delete formData.from;

        $.ajax({
            type: 'POST',
            url: this.url,
            data: formData,
            complete: function (jqXHR) {
                if (jqXHR.readyState === 4) {
                    let responseText = jqXHR.responseText;
                    let decodedResponse = JSON.parse(responseText);
                    _this.notify(decodedResponse.code, "Exported Successfully");
                    callback();
                }
            }
        });
    }

    _param(obj) {
        var str = "";
        for (var key in obj) {
            if (str != "") {
                str += "&";
            }
            str += key + "=" + obj[key];
        }
        return str;
    }

    _getRecords(offset) {
        // Check if data is null
        if (this.data == null) {
            this.data = [];
        }

        let totalRecord = this.data.length;
        let startRecord = this.limit * offset;
        let endRecord = startRecord + this.limit > totalRecord ? totalRecord : startRecord + this.limit;

        return this.data.slice(startRecord, endRecord);
    }

    _parseData(data) {
        let result = [];
        let _self = this;

        data.forEach(function (item, key) {
            if (item) {
                let normalArray = _self._getTemplateByArray(item);
                result.push(normalArray);
            }
        });

        return result;
    }

    _increaseIndex() {
        this.server.config.currentIndex += this.server.config.limit;
    }

    _clearIndex() {
        this.server.config.currentIndex = 0;
    }

    _convertForm(callback = false) {
        var result = {
            // from: this.server.config.currentIndex,
            // count: this.server.config.limit
        };

        let start = this.formObject.start_date + "T" + this.formObject.start_time + ".804Z";
        let end = this.formObject.stop_date + "T" + this.formObject.stop_time + ".804Z";

        result.start_time = Math.floor(new Date(start).getTime() / 1000);
        result.end_time = Math.floor(new Date(end).getTime() / 1000);

        result.format = 'json';

        if (this.formObject['query[country]'] !== undefined && this.formObject['query[country]'].length > 0) {
            result.c = this.formObject['query[country]'];
        }
        if (this.formObject['query[fields][]'] !== undefined && this.formObject['query[fields][]'].length > 0) {
            result.field = this.formObject['query[fields][]'].join(',');
        }
        if (this.formObject['ingress_alias'] !== undefined && this.formObject['ingress_alias'].length > 0) {
            result.ingress_id = this.formObject['ingress_alias'];
        }
        if (this.formObject['egress_alias[]'] !== undefined && this.formObject['egress_alias[]'].length > 0) {
            result.egress_id = this.formObject['egress_alias[]'].join(',');
        }
        if (this.formObject['egress_alias'] !== undefined && this.formObject['egress_alias'].length > 0) {
            result.egress_id = this.formObject['egress_alias'];
        }
        if (this.formObject['routing_digits'] !== undefined && this.formObject['routing_digits'].length > 0) {
            result.routing_digits = this.formObject['routing_digits'];
        }
        if (this.formObject['orig_src_number'] !== undefined && this.formObject['orig_src_number'].length > 0) {
            result.dest_number = this.formObject['orig_src_number'];
        } else if(this.formObject['orig_src_number_default'] !== undefined && this.formObject['orig_src_number_default'].length > 0){
            result.dest_number = this.formObject['orig_src_number_default'];
        }
        if (this.formObject['query[src_number]'] !== undefined && this.formObject['query[src_number]'].length > 0) {
            result.source_number = this.formObject['query[src_number]'];
        }
        if (this.formObject['is_final_call'] !== undefined && this.formObject['is_final_call'] == 1) {
            result.is_final_call = this.formObject['is_final_call'];
        }
        if (this.formObject['is_zero_call'] !== undefined && this.formObject['is_zero_call'] >= 1) {
            result.non_zero = this.formObject['is_zero_call'] == 2 ? 0 : 1;
        }
        if (this.formObject['res_status_ingress'] !== undefined && this.formObject['res_status_ingress'].length > 0) {
            result.ingress_rcause = this.formObject['res_status_ingress'];
        }
        if (this.formObject['res_status'] !== undefined && this.formObject['res_status'].length > 0) {
            result.egress_rcause = this.formObject['res_status'];
        }
        if (this.formObject['disconnect_cause'] !== undefined && this.formObject['disconnect_cause'].length > 0) {
            result.egress_rcode = this.formObject['disconnect_cause'];
        }
        if (this.formObject['human_readable'] !== undefined && this.formObject['human_readable'].length > 0) {
            result.human_readable = 1;
        }

        this.formObject = result;
    }

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

    notify(code, message) {
        var theme = "jmsg-error";

        if (code == 200) {
            theme = "jmsg-success";
        }

        $.jGrowl(message, {theme: theme});
    }

    _implode(glue, pieces) {
        return ( ( pieces instanceof Array ) ? pieces.join(glue) : pieces );
    }

    _convertDataValues(array) {
        if (this.formObject['human_readable'] !== 1) {
            let timeKeys = ['start_time_of_date', 'answer_time_of_date', 'release_tod', 'end_epoch', 'ring_epoch'];

            array.forEach(function (item, key) {
                for (let object in item) {
                    if (item.hasOwnProperty(object) && timeKeys.includes(object) && item[object] != 0) {
                        let dateObject = new Date(item[object] / 1000);
                        item[object] = + dateObject.getFullYear()
                            + "-" + (dateObject.getUTCMonth() < 9 ? '0' + (dateObject.getUTCMonth()+1) : (dateObject.getUTCMonth()+1))
                            + "-" +  (dateObject.getUTCDate() < 10 ? '0' + (dateObject.getUTCDate()) : (dateObject.getUTCDate()))
                            + " " +  (dateObject.getUTCHours() < 10 ? '0' + dateObject.getUTCHours() : dateObject.getUTCHours())
                            + ":" +  (dateObject.getUTCMinutes() < 10 ? '0' + dateObject.getUTCMinutes() : dateObject.getUTCMinutes())
                            + ":" +  (dateObject.getUTCSeconds() < 10 ? '0' + dateObject.getUTCSeconds() : dateObject.getUTCSeconds());
                    }
                }
                array[key] = item;
            });
        }
        return array;
    }

    _getHumanHeaders(array) {
        let _this = this;

        if (this.optionalHeaders) {
            let headers = [];
            let resultArray = [];

            for (var item in this.optionalHeaders) {
                headers.push(item);
            }

            array.forEach(function (item, key) {
                if (headers.includes(item) && _this.optionalHeaders[item] != undefined) {
                    resultArray[key] = _this.optionalHeaders[item];
                }
            });
            array = resultArray;
        } else {
            array.forEach(function (item, key) {
                if (_this.humanHeaders[item] != undefined) {
                    array[key] = _this.humanHeaders[item];
                }
            });
        }


        return array;
    }

    _getTemplateByArray(array) {
        var _this = this;

        let result = [];
        let iterator = 0;
        for (var index in array) {
            if (array.hasOwnProperty(index)) {
                // Not needed, because we use 'fields' param in request to API
                // let active = _this.showFields.includes(iterator.toString()) ? true : false;
                result.push({
                    // 'key': template[iterator],
                    'key': _this.humanHeaders[index] != undefined ? _this.humanHeaders[index] : index,
                    'value': array[index],
                    'active': true
                });
            }
            iterator++;
        }

        return result;
    }

    _getHumanHeadersArray() {
        let template = {
            connection_type: 'Connection Type',
            session_id: 'Session ID',
            release_cause: 'Release Cause',
            start_time_of_date: 'Start Time',
            answer_time_of_date: 'Answer Time',
            release_tod: 'End Time',
            release_cause_from_protocol_stack: 'Response From Egress',
            binary_value_of_release_cause_from_protocol_stack: 'Response To Ingress',
            first_release_dialogue: 'Orig/Term Release',
            trunk_id_origination: 'Ingress Alias',
            origination_source_number: 'Orig SRC Number',
            origination_source_host_name: 'Orig IP',
            origination_destination_number: 'Orig DST Number',
            origination_destination_host_name: 'Orig Switch IP',
            origination_call_id: 'Orig Call ID',
            origination_remote_payload_ip_address: 'Orig Media IP',
            origination_remote_payload_udp_address: 'Orig Media Port',
            origination_local_payload_ip_address: 'Orig Local Media IP',
            origination_local_payload_udp_address: 'Orig Local Media Port',
            origination_codec_list: 'Orig Codecs',
            trunk_id_termination: 'Egress Alias',
            termination_source_number: 'Term SRC Number',
            termination_source_host_name: 'Term IP',
            termination_destination_number: 'Term DST Number',
            termination_destination_host_name: 'Term Switch IP',
            termination_call_id: 'Term Call ID',
            termination_remote_payload_ip_address: 'Term Media IP',
            termination_remote_payload_udp_address: 'Term Media Port',
            termination_local_payload_ip_address: 'Term Local Media IP',
            termination_local_payload_udp_address: 'Term Local Media Port',
            termination_codec_list: 'Term Codecs',
            final_route_indication: 'Final Route',
            routing_digits: 'Term ANI',
            call_duration: 'Call Duration',
            pdd: 'PDD',
            ring_time: 'Ring Time',
            callduration_in_ms: 'Call Duration In ms',
            conf_id: 'Ingress Register User',
            route_plan: 'Routing Plan Name',
            dynamic_route: 'Dyamic Route',
            term_country: 'Term Country',
            term_code_name: 'Term Code Name',
            term_code: 'Term Code',
            orig_country: 'Orig Country',
            orig_code_name: 'Orig Code Name',
            orig_code: 'Orig Code',
            translation_ani: 'Translational ANI',
            egress_rate: 'Egress Rate',
            egress_cost: 'Egress Cost'
        };

        return template;
    }
}