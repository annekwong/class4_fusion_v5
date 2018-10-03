//数字
function in_array(v, arr) {
    for (var i in arr) {
        if (v == arr[i]) {
            return true;
        }
    }
    return false;
}
jQuery.fn.xkeyvalidatesfuns = {};
jQuery.xkeyvalidatesfuns = {};
jQuery.xkeyvalidatesfuns.noEmpty = function(that, obj)
{
    var value = jQuery(that).val();
    if (value == '')
    {
        jQuery(that).addClass('invalid');
        //jGrowl_to_notyfy('This is not null',{theme:'jmsg-error'});
        jQuery(that).focus();
        return false;
    }
    return true;
}
jQuery.fn.xkeyvalidatesfuns.noEmpty = function(that, obj) {
    jQuery(that).blur(
            function() {
                jQuery.xkeyvalidatesfuns.noEmpty(this, obj);
            }
    );
}
jQuery.xkeyvalidatesfuns.Int = function(that, obj) {
    var value = jQuery(that).val();
    var re = true;
    while (/[\D]/.test(value)) {
        value = value.replace(/[\D]/, '');
        re = false;
    }
    jQuery(that).val(value);
    return re;
}
jQuery.fn.xkeyvalidatesfuns.Int = function(that, obj) {
    jQuery(that).each(function() {
        jQuery(this).each(function() {
            jQuery(this).keyup(function(e) {
                if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
                    return;
                }
                jQuery.xkeyvalidatesfuns.Int(this, obj);
            });
        });
    });
    return jQuery(that);
}
jQuery.xkeyvalidatesfuns.code = function(that, obj) {
    var value = jQuery(that).val();
    var re = true;
    while (/[^\dx]/.test(value)) {
        value = value.replace(/[^\dx]/, '');
        re = false;
    }
    jQuery(that).val(value);
    return re;
}
jQuery.fn.xkeyvalidatesfuns.code = function(that, obj) {
    jQuery(that).each(function() {
        jQuery(this).keyup(function(e) {
            if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
                return;
            }
            jQuery.xkeyvalidatesfuns.code(this, obj);
        });
    });
    return jQuery(that);
}
jQuery.xkeyvalidatesfuns.Num = function(that, obj) {
    var value = jQuery(that).val();
    var re = true;
    while (/[^-\.\d#\*_]/g.test(value))
    {
        value = value.replace(/[^-\.\d#\*_]/g, '');
        re = false;
    }
    jQuery(that).val(value);
    return re;
}
jQuery.fn.xkeyvalidatesfuns.Num = function(that, obj) {
    jQuery(that).each(function() {
        jQuery(this).keyup(function(e) {
            if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
                return;
            }
            jQuery.xkeyvalidatesfuns.Num(jQuery(this), obj);
        });
    });
}
//校验字母和数字＿－.@
jQuery.fn.xkeyvalidatesfuns.strNum = function(that, obj) {
    var strNum = /[^0-9A-Za-z-\_\.\@\s]+/;//    /^[\w|-]+[.|@]*[\w|-]+$/
    jQuery(that).keyup(function(e) {
        if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
            return;
        }
        value = jQuery(this).val();
        while (strNum.test(value)) {
            value = value.replace(strNum, '');
        }
        jQuery(this).val(value);
    });

}
//校验字母和数字＿－.
jQuery.fn.xkeyvalidatesfuns.strName = function(that, obj) {
    jQuery(that).keyup(function(e) {
        if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
            return;
        }
        value = jQuery(this).val();
        while (/[^0-9A-Za-z-\_\.\s#]+/.test(value)) {
            value = value.replace(/[^0-9A-Za-z-\_\.]+/, '');
        }
        jQuery(this).val(value);
    });
}
//E-mail可输入多个，用“，”号隔开
function check_email(str)
{
    var bo = true
    mail_arr = str.split(';');
    str_size = mail_arr.length;
    for (i = 0; i < str_size; i++) {
        //var temp=/^[\w\-\.\_]+@[\w\-\.]+(\.\w+)+$/;
        if (!/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.-]+$/.test(mail_arr[i]))
        {
            bo = false;
        }
    }
    return bo;
}

//校给整数
jQuery.xkeyvalidatesfuns.checkNum = function(that, obj) {
    var value = jQuery(that).val();
    var te = true;
    while (/\D/.test(value)) {
        value = value.replace(/\D/, '');
        te = false;
    }
    jQuery(that).val(value);
    return te;
}
jQuery.fn.xkeyvalidatesfuns.checkNum = function(that, obj) {
    jQuery(that).each(function(e) {
        jQuery(this).keyup(function(e) {
            if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
                return;
            }
            jQuery.xkeyvalidatesfuns.checkNum(this, obj);
        });
    });
}

//校验IP
jQuery.xkeyvalidatesfuns.checkIp = function(that, obj) {
    var value = jQuery(that).val();
    if (value == "") {
        return true;
    }
    if (/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){3}$/.test(value)) {
        return true;
    } else {
        jQuery(that).addClass('invalid');
        jGrowl_to_notyfy(value + ' IPs must be a valid format. The following IPs are not valid: <<192.168.500.600、a.b.c.d>>', {theme: 'jmsg-error'});
        return false;
    }
}
jQuery.fn.xkeyvalidatesfuns.checkIp = function(that, obj) {
    jQuery.fn.xkeyvalidatesfuns.Ip(that, obj);
    jQuery(that).each(function() {
        jQuery(this).blur(function() {
            jQuery.xkeyvalidatesfuns.checkIp(jQuery(this), obj);
        });
    });
}
//校验 钱,小数点后只能有五位小数 位
jQuery.fn.xkeyvalidatesfuns.Money = function(that, obj) {
    var value = undefined;
    jQuery(that).keyup(function(e) {
        if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
            return;
        }
        value = jQuery(this).val();
        while (/[^-\.\d]/g.test(value)) {
            if (/\s+/.test(value)) {
                value = value.replace(/\s+/, '');
            }
            value = value.replace(/[^-\.\d]/g, '');
        }
        values = value.split('.');
        if (values[1] == undefined)
        {
            values[1] = '';
        }
        values[1] = values[1].substring(0, 5);
        if (values[0] != '' && values[1] != '') {
            value = values[0] + '.' + values[1];
        }
        if (values[0] == '')
        {
            value = '';
        }
        jQuery(this).val(value);
    }).blur(function() {
        if (/\.$/.test(value)) {
            value = value + "0";
            jQuery(this).val(value);
        }
    });
}

/////////////////////////////////////////////////////////
//小数
jQuery.xkeyvalidatesfuns.Ip = function(that, obj) {
    var value = jQuery(that).val();
    var re = true;
    while (/[^-\.\d]+/.test(value))
    {
        re = false;
        value = value.replace(/[^\d\.]+/, '');
    }
    jQuery(that).val(value);
    return re;
}
jQuery.fn.xkeyvalidatesfuns.Ip = function(that, obj) {
    jQuery(that).each(function() {
        jQuery(this).keyup(function(e) {
            if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
                return;
            }
            jQuery.xkeyvalidatesfuns.Ip(this, obj);
        });
    });

}
////////////////////////////////////////////////////////////////////
jQuery.xkeyvalidatesfuns.Email = function(that, obj) {
    value = jQuery(that).removeClass('invalid').val();
    if (value == '') {
        return false;
    }
    if (!/^[a-zA-Z0-9\.\-]+@[a-zA-Z0-9.-]+$/.test(value))
    {
        if (!obj.noMessage) {
            jQuery(that).focus();
            showMessages("[{'field':'#" + jQuery(that).attr('id') + "','code':'101','msg':'E-mail,format is not correct '}]");
        }
        return false;
    }
    return true;
}
jQuery.fn.xkeyvalidatesfuns.Email = function(that, obj) {
    jQuery(that).blur(function() {
        jQuery.xkeyvalidatesfuns.Email(jQuery(this), obj);
    });
}
jQuery.xkeyvalidatesfuns.all = function(that, obj) {
    obj = jQuery.extend({}, jQuery.xkeyvalidatesfuns.all.defObj, obj);
    obj.type = 'split';
    return jQuery.xkeyvalidate(jQuery(that).find('input[' + obj.check + '],select[' + obj.check + '],textarea[' + obj.check + ']'), obj);
}
jQuery.fn.xkeyvalidatesfuns.all = function(that, obj) {
    obj = jQuery.extend({}, jQuery.xkeyvalidatesfuns.all.defObj, obj);
    obj.type = 'split';
    jQuery(that).find('input[' + obj.check + '],select[' + obj.check + '],textarea[' + obj.check + ']').xkeyvalidate(obj);
}
jQuery.xkeyvalidatesfuns.all.defObj = {
    check: 'check'
}
jQuery.xkeyvalidatesfuns.split = function(that, obj) {
    var re = true;
    obj = jQuery.extend({}, jQuery.xkeyvalidatesfuns.split.defObj, obj);
    jQuery(that).each(
            function() {
                var checks = jQuery(this).attr(obj.check).split('/');
                for (var i in checks)
                {
                    var type = checks[i];
                    if (!jQuery.xkeyvalidate(jQuery(this)[0], {type: type}))
                    {
                        re = false;
                    }
                }
            }
    );
    return re;
}
jQuery.fn.xkeyvalidatesfuns.split = function(that, obj) {
    obj = jQuery.extend({}, jQuery.xkeyvalidatesfuns.split.defObj, obj);
    jQuery(that).each(
            function() {
                var checks = jQuery(this).attr(obj.check).split('/');
                for (var i in checks)
                {
                    var type = checks[i];
                    jQuery(this).xkeyvalidate({type: type});
                }
            }
    );
}
jQuery.xkeyvalidatesfuns.split.defObj = {
    check: 'check'
}
////////////////////////////////////////////////////////////////////
jQuery.xkeyvalidate = function(that, obj) {
    jQuery(that).removeClass('invalid');
    if (obj.type)
    {
        var jsstr = "fun=jQuery.xkeyvalidatesfuns." + obj.type;
        eval(jsstr);
    }
    if (obj.fun)
    {
        fun = obj.fun;
    }
    var re = fun(that, obj);
    return re;
}
jQuery.fn.xkeyvalidate = function(obj) {
    var that = this;
    if (obj.type)
    {
        var jsstr = "fun=jQuery.fn.xkeyvalidatesfuns." + obj.type;
        eval(jsstr);
    }
    if (obj.fun)
    {
        fun = obj.fun;
        fun(that, obj);
    }
    return this;
}
jQuery.xshow = function(options) {
    options = jQuery.extend({}, jQuery.xshow.defOptions, options);
    options.mongban = jQuery('body').append('<div id="' + options.id + '"></div>');
    var my = jQuery('<div/>').css('width', options.width).css('top', options.top).css('height', options.height).css('left', options.left).css('zIndex', '99').css('position', 'absolute').attr('id', options.id).addClass('tooltip-styled').appendTo('body');
    options.div = my;
    if (options.close) {
        //my.append(jQuery('<div style="height:20px"/>').append(jQuery('<a href="#" style="float:right;font-size:16px">Close</a>').click(function(){jQuery.closexshow(options);return false;})));
        my.append(jQuery('<div style="height:20px"/>').append(jQuery('<span class="float_right"><a href="#" class="pop-close">&nbsp</a></span>').click(function() {
            jQuery.closexshow(options);
            return false;
        })));
    }
    my.append(jQuery(options.html));
    options.callBack(options);
    jQuery('div[id=' + options.id + ']').center();
    return options;
}

jQuery.fn.center = function(f) {
    return this.each(function() {
        var p = f === false ? document.body : this.parentNode;
        if (p.nodeName.toLowerCase() != "body" && jQuery.css(p, "position") == 'static')
            p.style.position = 'relative';
        var s = this.style;
        s.position = 'absolute';
        if (p.nodeName.toLowerCase() == "body")
            var w = $(window);
        if (!f || f == "horizontal") {
            s.left = "0px";
            if (p.nodeName.toLowerCase() == "body") {
                var clientLeft = w.scrollLeft() - 10 + (w.width() - parseInt(jQuery.css(this, "width"))) / 2;
                s.left = Math.max(clientLeft, 0) + "px";
            } else if (((parseInt(jQuery.css(p, "width")) - parseInt(jQuery.css(this, "width"))) / 2) > 0)
                s.left = ((parseInt(jQuery.css(p, "width")) - parseInt(jQuery.css(this, "width"))) / 2) + "px";
        }
        if (!f || f == "vertical") {
            s.top = "0px";
            if (p.nodeName.toLowerCase() == "body") {
                var clientHeight = w.scrollTop() - 10 + (w.height() - parseInt(jQuery.css(this, "height"))) / 2;
                s.top = Math.max(clientHeight, 0) + "px";
            } else if (((parseInt(jQuery.css(p, "height")) - parseInt(jQuery.css(this, "height"))) / 2) > 0)
                s.top = ((parseInt(jQuery.css(p, "height")) - parseInt(jQuery.css(this, "height"))) / 2) + "px";
        }
    });
};

jQuery.fn.xshow = function(options) {
    return jQuery.xshow(options);
}
jQuery.xshow.defOptions = {
    width: 'auto',
    top: '30%',
    height: 'auto',
    left: '40%',
    id: 'xshow',
    html: '<div>this is xshow</div>',
    close: true,
    callBack: function(options) {
    }
}
jQuery.closexshow = function(options) {
    jQuery('div[id=' + options.id + ']').remove();
}
jQuery.fn.closexshow = function() {
    jQuery.closexshow();
}

//jQuery.xshowadd
jQuery.fn.xshowadd = function(options) {
    jQuery.fn.xshowadd.def_input = {'label': '', 'name': 'name', 'id': 'id', 'fin': 'input', 'type': 'text', 'value': '', 'display': 'block', 'validate': [], 'options': []};
    jQuery.fn.xshowadd.def_options = {
        id: 'showadd',
        left: '40%',
        top: '30%',
        width: '320px',
        height: 'auto',
        title: 'Add or Update',
        url: ' ',
        posts: [jQuery.fn.xshowadd.def_input],
        validateFun: jQuery.fn.xshowadd.def_validateFun,
        callBack: function(data) {
        }
    }
    options = jQuery.extend({}, jQuery.fn.xshowadd.def_options, options);
    jQuery(this).attr('href', 'javascript:void(0)').click(
            function() {
                jQuery.xcloseadd();
                jQuery('body').append('<div id="showadd_con" style="z-index: 77; width: 100%; height:100%"></div>');
                var my = jQuery('<div/>').css('width', options.width).css('top', options.top).css('height', options.height).css('left', options.left).css('zIndex', '99').css('position', 'absolute').attr('id', options.id).addClass('tooltip-styled');
                my.appendTo('body').append(
                        '<div style="text-align:center;width:100%;height:25px;font-size:16px;">' + options.title + '</div>'
                        );
                for ($i = 0; $i < options.posts.length; $i++)
                {
                    post = jQuery.extend({}, jQuery.fn.xshowadd.def_input, options.posts[$i]);
                    if (post.fin == 'input')
                    {
                        my.append(
                                jQuery('<div style="padding-left:5%;margin:10px;"/>').css('display', post.display).append(
                                jQuery('<label/>').css('display', 'inline-block').css('min-width', '50px').html(post.label)
                                ).append(
                                jQuery('<' + post.fin + '/>').css('display', 'inline-block').attr('type', post.type).attr('name', post.name).attr('id', post.id).val(post.value)
                                ).append(
                                jQuery('<span class="error" style="color:red"/>')
                                )
                                );
                    }
                    if (post.fin == 'select')
                    {
                        var valuediv = jQuery('<div style="padding-left:5%;margin:10px;"/>').css('display', post.display);
                        valuediv.append(jQuery('<label/>').css('display', 'inline-block').css('min-width', '50px').html(post.label));
                        var select = jQuery('<' + post.fin + '/>').css('display', 'inline-block').attr('name', post.name).attr('id', post.id).val(post.value);
                        for (i in post.options)
                        {
                            var postOption = post.options[i];
                            select.append(jQuery('<option/>').attr('value', postOption.value).html(postOption.key));
                        }
                        valuediv.append(select);
                        valuediv.append(jQuery('<span class="error" style="color:red"/>'));
                        my.append(valuediv);
                    }
                }
                my.append(
                        jQuery('<div style="text-align:center;"/>').append(
                        jQuery('<input type="button" class="input in-button" value="Submit">').click(
                        function() {
                            jQuery('#showadd .error').html('');
                            if (!options.validateFun(options.posts))
                            {
                                for (var i in jQuery.fn.xshowadd.message.error)
                                {
                                    arr = jQuery.fn.xshowadd.message.error[i];
                                    jQuery('#' + arr[0]).parent().find('span').html(arr[1]);
                                }
                                return false;
                            }
                            var showadd = jQuery(this).parent().parent();
                            var posts = {};
                            for (i = 0; i < options.posts.length; i++)
                            {
                                post = options.posts[i];
                                jsstr = 'posts.' + post.name + '="' + jQuery(this).parent().parent().find('#' + post.id).val() + '"';
                                eval(jsstr);
                            }
                            jQuery.post(options.url, posts, function(data) {
                                options.callBack(data), jQuery.xcloseadd()
                            });
                        }
                )
                        ).append(
                        '&nbsp;&nbsp;&nbsp;&nbsp;'
                        ).append(
                        jQuery('<input type="button" class="input in-button" value="Cancel">').click(jQuery.xcloseadd)
                        )
                        );
            }
    );
    return jQuery(this);
}
jQuery.fn.xshowadd.message = {};
jQuery.fn.xshowadd.message.putError = function(key)
{
    for (var i in jQuery.fn.xshowadd.message.error)
    {
        arr = jQuery.fn.xshowadd.message.error[i];
        if (arr[0] == key)
        {
            return arr[1];
        }
    }
    return null;
}
jQuery.fn.xshowadd.def_validateFun = function(posts)
{
    jQuery.fn.xshowadd.clearError();
    for (i = 0; i < posts.length; i++)
    {
        if (!posts[i].validate)
        {
            continue;
        }
        for (j = 0; j < posts[i].validate.length; j++)
        {
            var obj = posts[i].validate[j];
            var value = jQuery('#' + posts[i].id).val();
            if (!obj.fun(value))
            {
                jQuery.fn.xshowadd.addError(posts[i].id, obj.message);
                break;
            }
        }
    }
    return !jQuery.fn.xshowadd.hasError();
}
jQuery.fn.xshowadd.def_validateFunctions = {};
jQuery.fn.xshowadd.def_validateFunctions.noEmpty = function(value) {
    if (value == '' || value == null || value == undefined)
    {
        return false;
    }
    return true;
}
jQuery.fn.xshowadd.hasError = function()
{
    if (jQuery.fn.xshowadd.message && jQuery.fn.xshowadd.message.error)
    {
        return true;
    }
    return false;
}
jQuery.fn.xshowadd.getError = function()
{
    if (jQuery.fn.xshowadd.message && jQuery.fn.xshowadd.message.error)
    {
        return jQuery.fn.xshowadd.message.error;
    }
}
jQuery.fn.xshowadd.clearError = function()
{
    if (jQuery.fn.xshowadd.message && jQuery.fn.xshowadd.message.error)
    {
        return jQuery.fn.xshowadd.message.error = undefined;
    }
}
jQuery.fn.xshowadd.addError = function(key, value)
{
    if (!jQuery.fn.xshowadd.message.error)
    {
        jQuery.fn.xshowadd.message.error = Array();
    }
    jQuery.fn.xshowadd.message.error.push([key, value]);
}
jQuery.fn.xcloseadd = function() {
    jQuery(this).click(
            function() {
                jQuery('#showadd,#showadd_con').remove();
            }
    );
}
jQuery.extend(
        {
            xcloseadd: function() {
                jQuery('#showadd,#showadd_con').remove();
            }
        }
);
jQuery.trAdd = function(that, options) {
    options = jQuery.extend({}, jQuery.trAdd.defOptions, options);
    if (!jQuery(that).trAdd.beforeAdd(options)) {
    } else {
        jQuery.trAdd.render(that, options);
        options.defCallback(options);
        options.callback(options);
    }
    options.allCallBack();
}
jQuery.trAdd.isAdd = true;
jQuery.fn.trAdd = function(options) {
    jQuery.trAdd(this, options);
}
jQuery.trAdd.defOptions = {
    log: 'trAdd',
    ajax: undefined,
    del: 'delete',
    save: 'save',
    defCallback: function(options) {
        jQuery('#' + options.log).find('#' + options.del).removeTrAdd(options);
        jQuery('#' + options.log).find('#' + options.save).click(function() {
            jQuery('.' + options.log).xForm(options);
        });
        //jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
        //jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
        //jQuery('select').addClass('select in-select');
        //jQuery('textarea').addClass('textarea in-textarea');
    },
    callback: function(options) {

    },
    allCallBack: function() {
        jQuery.trAdd.isAdd = true;
    }
}
jQuery.trAdd.beforeAdd = function(that, options) {
    options = jQuery.extend({}, jQuery.trAdd.beforeAdd.defOptions, options);
    if (jQuery.trAdd.isAdd == false) {
        return false;
    }
    jQuery.trAdd.isAdd = false;
    if (options.valiType == 'stop' && jQuery('#' + options.log).size() > 0) {
        jQuery.jGrowlError('Please save and then create new !');
        return false;
    }
    if (options.valiType == 'clear' && jQuery('#' + options.log).size() > 0) {
        jQuery.removeTrAdd(options);
        return true;
    }
    return true;
}
jQuery.fn.trAdd.beforeAdd = function(options)
{
    return jQuery.trAdd.beforeAdd(this, options);
}
jQuery.trAdd.beforeAdd.defOptions = {
    valiType: 'stop'
}
jQuery.trAdd.ajaxData = function(options) {
    re = '';
    options = jQuery.extend({}, jQuery.trAdd.ajaxData.defOptions, options);
    jQuery.ajax(options);
    return re;
}
jQuery.fn.trAdd.ajaxData = function(options) {
    return jQuery.trAdd.ajaxData(options);
}
jQuery.trAdd.ajaxData.defOptions = {
    async: false,
    url: undefined,
    type: 'get',
    data: {},
    success: function(data) {
        re = data;
    }
}
jQuery.trAdd.render = function(that, options) {
    options = jQuery.extend({}, jQuery.trAdd.render.defOptions, options);
    var data = jQuery.trAdd.ajaxData({url: options.ajax, data: options.ajaxData});
    var jsDiv = jQuery('<div/>').hide().append(data).appendTo('body');
    if (options.line == 1)
        var jsTr = jsDiv.find(options.tag + ':nth-child(1)').clone(true).attr('id', options.log).addClass(options.log);
    else
        var jsTr = jsDiv.find(options.tag).clone(true).attr('id', options.log).addClass(options.log);

    if (options.saveType == 'add') {
        if (options.insertNumber == 'last') {
            jsTr.appendTo(that);
        }
        else if (options.insertNumber == 'first') {
            jsTr.prependTo(that);

        }
        else {
            jsTr.appendTo(that);
            jsTr.insertBefore(that.find('tbody').find(options.tag + ':nth-child(' + options.insertNumber + ')'));
        }
    }
    if (options.saveType == 'edit') {
        jsTr.insertBefore(that);
        jQuery(that).attr('trAdd', 'hide').hide();
        if (options.line == 2)
        {
            jQuery(that).next().attr('trAdd', 'hide').hide();
        }
    }
    jsDiv.remove();
}
jQuery.fn.trAdd.render = function(options) {
    return jQuery.trAdd.render(this, options);
}
jQuery.trAdd.render.defOptions = {
    saveType: 'add',
    tag: 'tr',
    insertNumber: 'last',
    line: 1
}
jQuery.xForm = function(that, options) {
    options = jQuery.extend({}, jQuery.fn.xForm.defOptions, options);

    if (!options.onsubmit(options)) {
        return false;
    }

    options.log = options.log + "Form";
    if (options.data) {
        options.data.action = options.log;
        options.data = jQuery(options.data).formatUrl();
    } else {
        options.data = "";
    }


    jQuery('<div>').attr('id', options.log).hide().appendTo('body');
    jQuery('<form/>').attr('action', options.action + options.data).attr('method', options.method).appendTo('#' + options.log);
	var have_floatThead = 0;
	if (jQuery(that).closest('table').hasClass('floatThead-table')){
		have_floatThead = 1;
	}
    jQuery(that).find('input,select,textarea').each(
            function() {
				if (have_floatThead == 1){
					if (jQuery(this).closest('table').hasClass('floatThead-table') == false){
						return true;
					}
				}
				if (jQuery(this).attr('type') != 'checkbox' || jQuery(this).is(':checked')) {
						jQuery('<input/>').attr('name', jQuery(this).attr('name')).val(jQuery(this).val()).appendTo(jQuery('#' + options.log).find('form'));
				}
				
            }
    );
   jQuery('#' + options.log).find('form').submit();

}
jQuery.fn.xForm = function(options) {
    jQuery.xForm(this, options);
}
jQuery.fn.xForm.defOptions = {
    log: 'xForm',
    action: '',
    method: 'post',
    data: undefined,
    onsubmit: function() {
        return true;
    }
}
jQuery.removeTrAdd = function(options) {
    options = jQuery.extend({}, jQuery.fn.removeTrAdd.defOptions, options);
    while (jQuery('#' + options.log).size() > 0) {

        if (jQuery('#' + options.log).next().attr('trAdd') == 'hide')
        {
            jQuery('#' + options.log).next().show();
            if (options.line == 2)
                jQuery('#' + options.log).next().next().show();
        }
        ;
        jQuery('#' + options.log).remove();

    }
    options.removeCallback(options);
}
jQuery.fn.removeTrAdd = function(options) {
    jQuery(this).click(function() {
        jQuery.removeTrAdd(options);
        return false
    });
}
jQuery.fn.removeTrAdd.defOptions = {
    'saveType': 'add',
    'removeCallback': function() {
    }
};

jQuery.formatUrl = function(that) {
    if (!that) {
        return "";
    }
    var re = "?1=1";
    for (i in that) {
        re += "&" + i + "=" + that[i];
    }
    return re;
}
jQuery.fn.formatUrl = function() {
    return jQuery.formatUrl(jQuery(this)[0]);
}
jQuery.jGrowlSuccess = function(msg, options) {
    options = jQuery.extend({}, jQuery.jGrowlSuccess.defOptions, options);
    jGrowl_to_notyfy(msg, options);
}
jQuery.fn.jGrowlSuccess = function(msg, options) {
    jQuery.jGrowlSuccess(msg, options);
}
jQuery.jGrowlSuccess.defOptions = {theme: 'jmsg-success'};
jQuery.jGrowlError = function(msg, options) {
    options = jQuery.extend({}, jQuery.jGrowlError.defOptions, options);
    jGrowl_to_notyfy(msg, options);
}
jQuery.fn.jGrowlError = function(msg, options) {
    jQuery(this).addClass('invalid');
    jQuery.jGrowlError(msg, options);
}
jQuery.jGrowlError.defOptions = {theme: 'jmsg-error'};
jQuery.ajaxData = function(that, options) {
    if (options == undefined) {
        options = that;
    }
    re = '';
    if (typeof (options) == "string") {
        options = {url: options};
    }
    options = jQuery.extend({}, jQuery.ajaxData.defOptions, options);
    jQuery.ajax(options);
    return re;
}
jQuery.ajaxData.defOptions = {
    async: false,
    success: function(data) {
        re = data;
    }
}
jQuery.disabled = function(options) {
    options = jQuery.extend({}, jQuery.disabled.defOptions, options);
    if (options.disabled) {
        jQuery(options.id).each(function() {
            jQuery(this).attr('disabled', true)
        }).val('');
    } else {
        jQuery(options.id).each(function() {
            jQuery(this).removeAttr('disabled')
        });
    }
}
jQuery.fn.disabled = function(options) {
    options = jQuery.extend({}, jQuery.disabled.defOptions, options);
    jQuery(this).click(function() {
        if (jQuery(this).is(':checked')) {
            options.disabled = false;
        } else {
            options.disabled = true;
        }
        jQuery.disabled(options);
    });
    if (jQuery(this).attr('checked') == true) {
        options.disabled = false;
    } else {
        options.disabled = true;
    }
    jQuery.disabled(options);
    return jQuery(this);
}
jQuery.disabled.defOptions = {
    id: 'input,select',
    disabled: true
}
jQuery.join = function(id, f) {
    var temp = array();
    jQuery(id).each(function() {
        temp.push(jQuery(this).val());
    });
    return temp.join(f);
}
jQuery.fn.join = function(f) {
    return jQuery.join(this, f);
}
function checkCB() {
    if (!jQuery('#ClientAutoInvoicing').attr('checked')) {
        jQuery('#ClientPaymentTermId,#ClientInvoiceFormat,#ClientCdrListFormat,#ClientLastInvoiced,#ClientInvoiceJurisdictionalDetail,#ClientAttachCdrsList,#ClientAutoInvoiceType,#ClientInvoiceZone,#ClientInvoiceZero').attr('disabled', true).val('');
        jQuery('#ClientLastInvoiced,#ClientAttachCdrsList,#ClientIsLinkCdr,#ClientInvoiceShowDetails').attr('disabled', true).val('');
    } else {
        //jQuery('#ClientInvoiceShowDetails, #ClientInvoiceJurisdictionalDetail').attr('checked', true);
        jQuery('#ClientPaymentTermId,#ClientInvoiceFormat,#ClientCdrListFormat,#ClientLastInvoiced,#ClientInvoiceJurisdictionalDetail,#ClientAttachCdrsList,#ClientAutoInvoiceType,#ClientInvoiceZone,#ClientInvoiceZero').attr('disabled', false);
        jQuery('#ClientLastInvoiced,#ClientAttachCdrsList,#ClientIsLinkCdr,#ClientInvoiceShowDetails').removeAttr('disabled');
    }
    if (!jQuery('#ClientIsPanelaccess').attr('checked')) {
        jQuery('#ClientLogin,#ClientPassword').attr('disabled', true).val('');
    } else {
        jQuery('#ClientLogin,#ClientPassword').attr('disabled', false);
    }
    if (!jQuery('#ClientIsPanelaccess').attr('checked')) {
        jQuery('#ClientIsPanelAccountsummary').attr({'disabled': true});
        jQuery('#ClientIsPanelRatetable').attr({'disabled': true});
        jQuery('#ClientIsPanelTrunks').attr({'disabled': true});
        jQuery('#ClientIsPanelProducts').attr({'disabled': true});
        jQuery('#ClientIsPanelBalance').attr({'disabled': true});
        jQuery('#ClientIsPanelPaymenthistory').attr({'disabled': true});
        jQuery('#ClientIsPanelOnlinepayment').attr({'disabled': true});
        jQuery('#ClientIsPanelInvoices').attr({'disabled': true});
        jQuery('#ClientIsPanelCdrslist').attr({'disabled': true});
        jQuery('#ClientIsPanelSummaryreport').attr({'disabled': true});
        jQuery('#ClientIsPanelSippacket').attr({'disabled': true});
    } else {
        jQuery('#ClientIsPanelAccountsummary').attr({'disabled': false});
        jQuery('#ClientIsPanelRatetable').attr({'disabled': false});
        jQuery('#ClientIsPanelTrunks').attr({'disabled': false});
        jQuery('#ClientIsPanelProducts').attr({'disabled': false});
        jQuery('#ClientIsPanelBalance').attr({'disabled': false});
        jQuery('#ClientIsPanelPaymenthistory').attr({'disabled': false});
        jQuery('#ClientIsPanelOnlinepayment').attr({'disabled': false});
        jQuery('#ClientIsPanelInvoices').attr({'disabled': false});
        jQuery('#ClientIsPanelCdrslist').attr({'disabled': false});
        jQuery('#ClientIsPanelSummaryreport').attr({'disabled': false});
        jQuery('#ClientIsPanelSippacket').attr({'disabled': false});

    }
}

jQuery.loadNextTr = function(that, options) {
    options = jQuery.extend({}, jQuery.loadNextTr.defOptions, options);
    var data = '';
    data = jQuery.ajaxData(options);
    var colspan = jQuery(that).find('td').size();
    var div = jQuery('<div/>').css('padding', '5px').append(data);
    var td = jQuery('<td/>').attr('colspan', colspan).append(div);
    var newTr = jQuery('<tr/>').append(td);
    jQuery(that).after(newTr);
}
jQuery.fn.loadNextTr = function(options) {
    jQuery.loadNextTr(this, options);
}
jQuery.loadNextTr.defOptions = {
}
jQuery.loadNextTr.cache = {
    last: 1
}
jQuery.closeLoadNextTr = function(that, options) {
    options = jQuery.extend({}, jQuery.closeLoadNextTr.defOptions, options);
    jQuery(that).next().remove();
}
jQuery.fn.closeLoadNextTr = function(that, options) {
    jQuery.closeLoadNextTr(this, options);
}
jQuery.closeLoadNextTr.defOptions = {
}

/*
 * 删除一行
 */
function ex_delConfirm(obj, url, f_name) {

    bootbox.confirm("Are you sure to delete " + f_name + " ?", function(result) {
        if (result) {
            window.location.href = url;
        }
    });
}

function dele_tr(delete_url, delete_a, dele_name) {
    if (confirm("Are you sure to delete , " + dele_name)) {
        jQuery.ajax({
            url: delete_url,
            success: function() {
                jGrowl_to_notyfy("delete success", {'sticky': false, theme: 'jmsg-success'});
                jQuery(delete_a).parent().parent().remove()

            }
        });
    }
}


//判断输入的两次密码是否一致
function password_same(obj1, obj2) {
    if (jQuery(obj1).val() != jQuery(obj2).val()) {
        jQuery(obj1).addClass('invalid');
        jQuery(obj2).addClass('invalid');
        jQuery(obj1).val('');
        jQuery(obj2).val('');
        jGrowl_to_notyfy('The Confirm Password does not match!', {theme: 'jmsg-error'});
        return false;
    } else {
        jQuery(obj1).attr('calss', 'input in-input in-text');
        jQuery(obj2).attr('calss', 'input in-input in-text');
        return true;
    }
}
//删除选中的deleteSelected('tabid','/exchange/jurisdictionprefixs/del_selected_jur');
function ex_deleteSelected(tabid, url, str) {
    if (!$('#' + tabid + ' tr').find('td:first input[type="checkbox"]:checked').length) {
        jGrowl_to_notyfy("No selected row!", {theme: 'jmsg-error'});
        return;
    }
    bootbox.confirm("Please confirm the " + str + " to remove!", function(result) {
        if (result) {
            var ids = '';
            var chx = document.getElementById(tabid).getElementsByTagName("input");
            var loop = chx.length;
            for (var i = 0; i < loop; i++) {
                var c = chx[i];
                if (c.type == "checkbox") {
                    if (c.checked == true && c.value != '') {
                        ids += c.value + ",";
                    }
                }
            }
            if (ids == '' || ids.length < 1)
            {
                jGrowl_to_notyfy("Please select the " + str + " that you would like to remove!", {theme: 'jmsg-error'});

            }
            else
            {
                ids = ids.substring(0, ids.length - 1);// 去掉最后逗号
                if (url.indexOf("?") != -1) {
                    url = url + "&ids=" + ids;
                } else {
                    url = url + "?ids=" + ids;
                }
                location = url;
            }
        }
    });
}


function show_users(url, role_id) {
    var show_users = '';
    alert(url);
    alert(role_id);
    jQuery.getJSON(url, {act: 'getHost', role_id: role_id}, function(d) {

    });
    return show_users;
}

function check_repeat(url, check_name) {
    var data = jQuery.ajaxData(url + '/' + check_name);
    if (data.indexOf('false')) {
        jGrowl_to_notyfy(check_name + " is already in use! ", {theme: 'jmsg-error'});
        return false;
    } else {
        return true;
    }
}
