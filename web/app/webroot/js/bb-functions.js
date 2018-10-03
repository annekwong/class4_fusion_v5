/**
 * Runs drop-down menu
 */
var jsddm_timeout = 300;
var jsddm_closetimer = 0;
var jsddm_item = 0;
var currentTime = "<?php echo time(); ?>";
/**
 * Opens popup window
 */
function winOpen(href, width, height, name)
{
    if (!width) {
        width = 10;
    }
    if (!height) {
        height = 10;
    }
    if (!name) {
        name = 'blank_' + Math.floor(Math.random() * 100000);
    }
    // var params = 'width='+width+'px'+',height='+height+'px';
    var params = 'scrollbars=1,resizable=yes,status=1,width=' + width + ',height=' + height;
    var win = window.open(href, name, params);
    win.isPopup = 1;
}


/**
 * 根据长度截取先使用字符串，超长部分追加...
 * @param str 对象字符串
 * @param len 目标字节长度
 * @return 处理结果字符串
 */
function cutString(str, len) {
    //length属性读出来的汉字长度为1
    if (str.length * 2 <= len) {
        return str;
    }
    var strlen = 0;
    var s = "";
    for (var i = 0; i < str.length; i++) {
        s = s + str.charAt(i);
        if (str.charCodeAt(i) > 128) {
            strlen = strlen + 2;
            if (strlen >= len) {
                return s.substring(0, s.length - 1) + "...";
            }
        } else {
            strlen = strlen + 1;
            if (strlen >= len) {
                return s.substring(0, s.length - 2) + "...";
            }
        }
    }
    return s;
}

/**
 * Closes popup window
 * 关闭弹出的子窗口
 */
function winClose()
{
    window.open('', '_parent', '');
    window.close();
}

function closewindow() {
    window.open("", "_self");
    top.opener = null;
    top.close();
}

/**
 * Resizes window if it is allowed
 */
function winResize(w, h, force)
{
    if (window.isPopup || force) {
        window.resizeTo(w, h);
    }
}

/**
 * Try to submit search forms for opener
 */
function winReload() {
    var search_imp = $('#search-_q');
    if ($('#advsearch:visible').length > 0) {
        $('#advsearch form').submit();
    } else if (search_imp.val() != '' && search_imp.val() != search_imp.attr('title')) {
        $('#title-search form').submit();
    } else {
        window.location.reload();
    }
}


/**
 * Switches loading panel
 */
function loading(action, msg)
{
    var c = msg || L['loadingPanel'];
    if (!$('#loadingPanel').length) {
        $('<div id="loadingPanel"><div></div><p><b>' + c + '</b><span><em></em></span></p></div>').appendTo(document.body);
        setInterval(function() {
            var e = $('#loadingPanel em');
            if (e.attr('xpos') == undefined) {
                e.attr('xpos', -52);
            }
            var pos = 1 * e.attr('xpos') + 3;
            if (pos > 200) {
                pos = -52;
            }
            e.attr('xpos', pos);
            e.css('left', pos + 'px');
        }, 50);
    }
    if (action == undefined) {
        action = $('#loadingPanel:visible').length ? false : true;
    }
    if (action) {
        $('#loadingPanel').fadeIn();
    } else {
        $('#loadingPanel').fadeOut();
    }
}

/**
 * Replaces dynamic part of the page
 */
var dynSearchTimer = null;
var dynSearchUrl = null;
var dynSearchQ = null;
function dynSearchLoad(data)
{
    data = data.substring(data.indexOf('<!-- DYNAMIC -->'));
    data = data.substring(0, data.lastIndexOf('<!-- DYNAMIC -->'));
    var c = $('#container');
    c.get(0).innerHTML = data;
    initTooltips(c);
    initPopups(c);
    initList(c);
    initForms(c);
}
function dynSearchRun()
{

}


/**
 * Runs advanced search
 */
function advSearchToggle(action)
{
    if (action == undefined) {
        // alert(action);
        action = $('#advsearch:visible').length ? false : true;
        //alert(action);
    }
    if (action) {

        $('#advsearch').show();
        //$('#id_time_profiles_eq,#search-state_eq').show();  
        $('#title-search-adv').addClass('opened');
    } else {
        $('#advsearch').hide();
        //$('#id_time_profiles_eq,#search-state_eq').hiden();  
        $('#title-search-adv').removeClass('opened');
    }
}

/**
 * Submits form and sets flag of apply
 */
function applyForm(frm)
{
    $(frm).append($('<input type="hidden" name="apply" value="1" />'));
    frm.submit();
}

/**
 * 
 * @param {type} msgs
 * @returns {undefined}
 * 
 */
function showMessages_new(msgs)
{
    // if we got just one message to show
    if (!(msgs instanceof Array)) {
        msgs = eval(msgs);
    }
    //循环遍历错误信息
    for (var i = 0; i < msgs.length; i++) {
        if (!msgs[i].msg) {
            continue;
        }
//        //给出错的表单 生成红色的样式
        if (msgs[i].field) {
            $(msgs[i].field).addClass('invalid');
            $(msgs[i].field).attr('title', msgs[i].msg);
            //返回用户输入的数据
            if (msgs[i].value) {
                $(msgs[i].field).attr('value', msgs[i].value);
            }
            $(msgs[i].field).css('', 'none')
        }
        var alert_type = "";
        switch (msgs[i].code.toString().substring(0, 1)) {
            case '1':
                alert_type = 'error';
                break;
            case '2':
                alert_type = 'success';
                break;
            case '3':
            case '4':
                alert_type = 'alert';
                break;
            default:
                alert_type = 'information';
                break;
        }
        //jquery的弹出信息显示 
        //  alert(params.sticky);
        var this_notyfy = notyfy({
            text: msgs[i].msg,
            type: alert_type, // alert|error|success|information|warning|primary|confirm
        });

        setTimeout(function() {
            this_notyfy.close();
        }, 5000);
    }
}




/**
 * 
 * 利用jquery显示弹出想信息(错误信息,msgs错误信息数组,msgs[i]['code'],)
 * Shows messages with appropriate styles
 */
function showMessages(msgs)
{
    // if we got just one message to show
    if (!(msgs instanceof Array)) {
        msgs = eval(msgs);
    }
    // if no messages and we need to close the window
    //关闭错误信息
    /*    if (!msgs.length && close) {
     winClose();
     }*/

    // init defaults
    $.jGrowl.defaults.position = 'top-center';
    $.jGrowl.defaults.closeTemplate = 'x';
    $.jGrowl.defaults.closerTemplate = "<div style='background: #333333 '>[ hide-all ]</div>";
    // init variables
    var params = {
        sticky: false, //不需要用户手动关闭
        theme: 'default'
    };
    // if we need to close popup after messages
    /*    if (close) {
     params['close'] = winClose;
     }*/

    //循环遍历错误信息
    for (var i = 0; i < msgs.length; i++) {
        if (!msgs[i].msg) {
            continue;
        }
        //给出错的表单 生成红色的样式
        if (msgs[i].field) {
            $(msgs[i].field).addClass('invalid');
            $(msgs[i].field).attr('title', msgs[i].msg);
            //返回用户输入的数据
            if (msgs[i].value) {
                $(msgs[i].field).attr('value', msgs[i].value);
            }
            $(msgs[i].field).css('', 'none')
        }
        params['sticky'] = false;
        switch (msgs[i].code.toString().substring(0, 1)) {
            case '1':
                params['theme'] = 'jmsg-alert';
                break;
            case '2':
                params['sticky'] = false;
                params['theme'] = 'jmsg-success';
                break;
            case '3':
            case '4':
                params['theme'] = 'jmsg-error';
                break;
            default:
                params['theme'] = 'jmsg-default';
                break;
        }
        //jquery的弹出信息显示 
        //  alert(params.sticky);
        $.jGrowl(msgs[i].msg, params);
    }
}
jQuery.error = function(msg) {
    jQuery.jGrowl(msg, {theme: 'jmsg-error'});
}
jQuery.fn.error = function(msg) {
    jQuery.error(msg);
    jQuery(this).addClass('invalid');
}
/**
 * Parse of URLs querystring
 */
jQuery.query = function(q) {

    var r = {};
    q = unescape(q);
    q = q.replace(/^\?/, ''); // remove the leading ?
    q = q.replace(/\&$/, ''); // remove the trailing &
    jQuery.each(q.split('&'), function() {

        var key = this.split('=')[0];
        var val = this.split('=')[1];
        // convert floats
        if (/^[0-9.]+$/.test(val))
            val = parseFloat(val);
        // ingnore empty values

        if (val) {
            r[key] = val;
        }
    });
    return r;
};
/**
 * Runs time in text
 */
function bbTime(elName)
{
    el = document.getElementById(elName);
    if (el == undefined) {
        alert('Specified element ' + elName + ' not found.');
        return;
    }

    var tz = el.getAttribute('mb:tz'); //获取时区

    if (tz == undefined) {
        tz = '+0800';
    }

    serverTz = (tz % 100 + Math.floor(tz / 100) * 60) * 60;
    stamp = el.getAttribute('mb:stamp'); //时间戳
    stamp = stamp * 1 + 1;
    el.setAttribute('mb:stamp', stamp);
    d = new Date();
    d = new Date(stamp * 1000 + d.getTimezoneOffset() * 60 * 1000 + serverTz * 1000);
    els = new Array();
    els['%Y'] = d.getFullYear();
    els['%y'] = d.getYear();
    els['%m'] = printf("%02d", d.getMonth() + 1);
    els['%n'] = d.getMonth();
    els['%d'] = printf("%02d", d.getDate());
    els['%j'] = d.getDate();
    els['%h'] = d.getUTCHours();
    els['%H'] = printf("%02d", d.getHours());
    els['%M'] = printf("%02d", d.getMinutes());
    els['%S'] = printf("%02d", d.getSeconds());
    els['%N'] = d.getDay();
    els['%Z'] = tz;
    els['%z'] = tz;
    format = el.getAttribute('mb:format');
    if (format == undefined) {
        format = 'Y-m-d H:i:s';
    }

    for (key in els) {
        format = format.replace(key, els[key])
    }

    el.innerHTML = format;
}

/**
 * Formats string for output
 */


function printf(fstring)
{
    var pad = function(str, ch, len)
    {
        var ps = '';
        for (var i = 0; i < Math.abs(len); i++)
            ps += ch;
        return len > 0 ? str + ps : ps + str;
    }
    var processFlags = function(flags, width, rs, arg)
    {
        var pn = function(flags, arg, rs)
        {
            if (arg >= 0)
            {
                if (flags.indexOf(' ') >= 0)
                    rs = ' ' + rs;
                else if (flags.indexOf('+') >= 0)
                    rs = '+' + rs;
            }
            else
                rs = '-' + rs;
            return rs;
        }
        var iWidth = parseInt(width, 10);
        if (width.charAt(0) == '0')
        {
            var ec = 0;
            if (flags.indexOf(' ') >= 0 || flags.indexOf('+') >= 0)
                ec++;
            if (rs.length < (iWidth - ec))
                rs = pad(rs, '0', rs.length - (iWidth - ec));
            return pn(flags, arg, rs);
        }
        rs = pn(flags, arg, rs);
        if (rs.length < iWidth)
        {
            if (flags.indexOf('-') < 0)
                rs = pad(rs, ' ', rs.length - iWidth);
            else
                rs = pad(rs, ' ', iWidth - rs.length);
        }
        return rs;
    }
    var converters = new Array();
    converters['c'] = function(flags, width, precision, arg)
    {
        if (typeof (arg) == 'number')
            return String.fromCharCode(arg);
        if (typeof (arg) == 'string')
            return arg.charAt(0);
        return '';
    }
    converters['d'] = function(flags, width, precision, arg)
    {
        return converters['i'](flags, width, precision, arg);
    }
    converters['u'] = function(flags, width, precision, arg)
    {
        return converters['i'](flags, width, precision, Math.abs(arg));
    }
    converters['i'] = function(flags, width, precision, arg)
    {
        var iPrecision = parseInt(precision);
        var rs = ((Math.abs(arg)).toString().split('.'))[0];
        if (rs.length < iPrecision)
            rs = pad(rs, ' ', iPrecision - rs.length);
        return processFlags(flags, width, rs, arg);
    }
    converters['E'] = function(flags, width, precision, arg)
    {
        return (converters['e'](flags, width, precision, arg)).toUpperCase();
    }
    converters['e'] = function(flags, width, precision, arg)
    {
        iPrecision = parseInt(precision);
        if (isNaN(iPrecision))
            iPrecision = 6;
        rs = (Math.abs(arg)).toExponential(iPrecision);
        if (rs.indexOf('.') < 0 && flags.indexOf('#') >= 0)
            rs = rs.replace(/^(.*)(e.*)$/, '$1.$2');
        return processFlags(flags, width, rs, arg);
    }
    converters['f'] = function(flags, width, precision, arg)
    {
        iPrecision = parseInt(precision);
        if (isNaN(iPrecision))
            iPrecision = 6;
        rs = (Math.abs(arg)).toFixed(iPrecision);
        if (rs.indexOf('.') < 0 && flags.indexOf('#') >= 0)
            rs = rs + '.';
        return processFlags(flags, width, rs, arg);
    }
    converters['G'] = function(flags, width, precision, arg)
    {
        return (converters['g'](flags, width, precision, arg)).toUpperCase();
    }
    converters['g'] = function(flags, width, precision, arg)
    {
        iPrecision = parseInt(precision);
        absArg = Math.abs(arg);
        rse = absArg.toExponential();
        rsf = absArg.toFixed(6);
        if (!isNaN(iPrecision))
        {
            rsep = absArg.toExponential(iPrecision);
            rse = rsep.length < rse.length ? rsep : rse;
            rsfp = absArg.toFixed(iPrecision);
            rsf = rsfp.length < rsf.length ? rsfp : rsf;
        }
        if (rse.indexOf('.') < 0 && flags.indexOf('#') >= 0)
            rse = rse.replace(/^(.*)(e.*)$/, '$1.$2');
        if (rsf.indexOf('.') < 0 && flags.indexOf('#') >= 0)
            rsf = rsf + '.';
        rs = rse.length < rsf.length ? rse : rsf;
        return processFlags(flags, width, rs, arg);
    }
    converters['o'] = function(flags, width, precision, arg)
    {
        var iPrecision = parseInt(precision);
        var rs = Math.round(Math.abs(arg)).toString(8);
        if (rs.length < iPrecision)
            rs = pad(rs, ' ', iPrecision - rs.length);
        if (flags.indexOf('#') >= 0)
            rs = '0' + rs;
        return processFlags(flags, width, rs, arg);
    }
    converters['X'] = function(flags, width, precision, arg)
    {
        return (converters['x'](flags, width, precision, arg)).toUpperCase();
    }
    converters['x'] = function(flags, width, precision, arg)
    {
        var iPrecision = parseInt(precision);
        arg = Math.abs(arg);
        var rs = Math.round(arg).toString(16);
        if (rs.length < iPrecision)
            rs = pad(rs, ' ', iPrecision - rs.length);
        if (flags.indexOf('#') >= 0)
            rs = '0x' + rs;
        return processFlags(flags, width, rs, arg);
    }
    converters['s'] = function(flags, width, precision, arg)
    {
        var iPrecision = parseInt(precision);
        var rs = arg;
        if (rs.length > iPrecision)
            rs = rs.substring(0, iPrecision);
        return processFlags(flags, width, rs, 0);
    }
    farr = fstring.split('%');
    retstr = farr[0];
    fpRE = /^([-+ #]*)(\d*)\.?(\d*)([cdieEfFgGosuxX])(.*)$/;
    for (var i = 1; i < farr.length; i++)
    {
        fps = fpRE.exec(farr[i]);
        if (!fps)
            continue;
        if (arguments[i] != null)
            retstr += converters[fps[4]](fps[1], fps[2], fps[3], arguments[i]);
        retstr += fps[5];
    }
    return retstr;
}


/* SHARED SELECT: client / cc / company / code  查询所所需要的字段 这里都封装在一个对象里------------------------------ */
var ss_ids = {
    //client的量位
    'client': {
        'id_clients': 'id_clients',
        'id_clients_name': 'id_clients_name',
        'id_accounts': 'id_accounts',
        'account': 'account',
        'tz': 'tz',
        'id_currencies': 'id_currencies',
        'id_code_decks': 'id_code_decks',
        'autoinvoice_cdr_output': 'cdr_output',
        'autoinvoice_cdr_file': 'cdr_generate',
        'autoinvoice_output': 'invoice_output'
    },
    'country': {
        'id_countrys': 'id_countrys'

    },
    'package': {
        'id_packages': 'id_packages',
        'id_packages_name': 'id_packages_name',
        'id_accounts': 'id_accounts',
        'account': 'account',
        'tz': 'tz',
        'id_currencies': 'id_currencies',
        'id_code_decks': 'id_code_decks',
        'autoinvoice_cdr_output': 'cdr_output',
        'autoinvoice_cdr_file': 'cdr_generate',
        'autoinvoice_output': 'invoice_output'
    },
    //client的量位
    'reseller': {
        'id_resellers': 'id_resellers',
        'id_resellers_name': 'id_resellers_name',
        'id_accounts': 'id_accounts',
        'account': 'account',
        'tz': 'tz',
        'id_currencies': 'id_currencies',
        'id_code_decks': 'id_code_decks',
        'autoinvoice_cdr_output': 'cdr_output',
        'autoinvoice_cdr_file': 'cdr_generate',
        'autoinvoice_output': 'invoice_output'
    },
    //client的量位
    'client_term': {
        'id_clients': 'id_clients_term',
        'id_clients_name': 'id_clients_name_term',
        'id_accounts': 'id_accounts',
        'account': 'account',
        'tz': 'tz',
        'id_currencies': 'id_currencies',
        'id_code_decks': 'id_code_decks',
        'autoinvoice_cdr_output': 'cdr_output',
        'autoinvoice_cdr_file': 'cdr_generate',
        'autoinvoice_output': 'invoice_output'
    },
    //client的量位
    'reseller_term': {
        'id_resellers': 'id_resellers_term',
        'id_resellers_name': 'id_resellers_name_term',
        'id_accounts': 'id_accounts',
        'account': 'account',
        'tz': 'tz',
        'id_currencies': 'id_currencies',
        'id_code_decks': 'id_code_decks',
        'autoinvoice_cdr_output': 'cdr_output',
        'autoinvoice_cdr_file': 'cdr_generate',
        'autoinvoice_output': 'invoice_output'
    },
    //费率前缀的量位
    'code': {
        'id_code_decks': 'id_code_decks',
        'code_deck': 'code_deck',
        'code': 'code',
        'code_name': 'code_name',
        'code_country': 'code_country'
    },
    //费率前缀的量位
    'code_term': {
        'id_code_decks': 'id_code_decks',
        'code_deck': 'code_deck',
        'code': 'code_term',
        'code_name': 'code_name_term',
        'code_country': 'code_country'
    },
    //费率模板量位
    'rate': {
        'id_rates': 'id_rates',
        'id_rates_name': 'id_rates_name'

    },
    'rate_term': {
        'id_rates': 'id_rates',
        'id_rates_name': 'id_rates_name'

    },
    'country_term': {
        'id_countrys': 'query-country_term'

    },
    //帐号卡量位
    'card': {
        'id_cards': 'id_cards',
        'id_cards_name': 'id_cards_name',
        'id_accounts': 'id_accounts',
        'account': 'account',
        'tz': 'tz',
        'id_currencies': 'id_currencies',
        'id_code_decks': 'id_code_decks',
        'autoinvoice_cdr_output': 'cdr_output',
        'autoinvoice_cdr_file': 'cdr_generate',
        'autoinvoice_output': 'invoice_output'
    },
    //帐号池量位
    'serie': {
        'id_series': 'id_series',
        'id_series_name': 'id_series_name',
        'id_accounts': 'id_accounts',
        'account': 'account',
        'tz': 'tz',
        'id_currencies': 'id_currencies',
        'id_code_decks': 'id_code_decks',
        'autoinvoice_cdr_output': 'cdr_output',
        'autoinvoice_cdr_file': 'cdr_generate',
        'autoinvoice_output': 'invoice_output'
    },
    //帐号池批次
    'batch': {
        'id_batchs': 'id_batchs',
        'id_batchs_name': 'id_batchs_name',
        'id_accounts': 'id_accounts',
        'account': 'account',
        'tz': 'tz',
        'id_currencies': 'id_currencies',
        'id_code_decks': 'id_code_decks',
        'autoinvoice_cdr_output': 'cdr_output',
        'autoinvoice_cdr_file': 'cdr_generate',
        'autoinvoice_output': 'invoice_output'
    }
};
//客户需要的字段
var ss_ids_custom = {};
/**
 * 获取client所需要的字段
 * client
 * @param type=client
 * @return
 */
function ss_getIds(type)
{
    var _ss_ids = ss_ids[type]; //提取cient得字段type=client



    if (ss_ids_custom[type] !== undefined) {
        if (ss_ids_custom instanceof Array) {
            _ss_ids = {};
            //自定义的是数组
            for (var i = 0; i < ss_ids_custom.length; i++) {
                _ss_ids[ss_ids_custom[i]] = ss_ids[type][ss_ids_custom[i]];
            }
        } else {
            _ss_ids = {}; //置空他
            //将客户定义的字段
            for (k in ss_ids_custom[type]) {
                _ss_ids[k] = ss_ids_custom[type][k];
            }
        }

    }
    return _ss_ids;
}
/*
 * 
 * 查找代理商和路由伙伴
 Types are represented as bitmask:
 0001 - clients
 0010 - clients & accounts
 0100 - calling cards
 1000 - resellers
 Examples:
 1 - show only clients, 
 5 - show clients and calling cards, 
 7 - show clients+accounts and calling cards
 9 - show resellers and clients
 */

var tz = '';
/**
 * 查找client 点击文本框 types=10ss_client(10);--10 可以查到下面的卡
 * 9--查找单个
 * @param types=10
 * @param _ss_ids
 * @return
 */
function ss_client(types, _ss_ids)
{


    ss_ids_custom['client'] = _ss_ids;
    if (!types) {
        types = 1;
    }
    tz = $('#query-tz').val(); //时区值
    //打开查找client的页面
    winOpen(webroot + 'clients/ss_client?types=' + types, 500, 530);
}

/**
 * 
 * 查找号码
 * @param id_code_decks
 * @param _ss_ids
 * @param _q
 * @return
 */
function ss_code(id_code_decks, _ss_ids, _q, id)
{
    ss_ids_custom['code'] = _ss_ids;
    if (!id_code_decks) {
        id_code_decks = '';
        // try to get current code deck
        if (_ss_ids['id_code_decks'] !== undefined && $(_ss_ids['id_code_decks'])) {
            id_code_decks = $('#' + _ss_ids['id_code_decks']).val();
        }
    }
    if (!id) {
        id = '';
    }
    winOpen(webroot + 'codedecks/ss_codename?type=2&types=8&id=' + id, 500, 530);
}
function ss_codename_all(id_code_decks, _ss_ids, _q, id) {
    winOpen(webroot + 'codedecks/ss_codename_all?type=2&types=8&id=' + id, 500, 530);
}
function ss_country_all(id_code_decks, _ss_ids, _q, id) {
    winOpen(webroot + 'codedecks/ss_country_all?type=2&types=8&id=' + id, 500, 530);
}
function ss_code_term(id_code_decks, _ss_ids, _q)
{
    ss_ids_custom['code_term'] = _ss_ids;
    if (!id_code_decks) {
        id_code_decks = '';
        // try to get current code deck
        if (_ss_ids['id_code_decks'] !== undefined && $(_ss_ids['id_code_decks'])) {
            id_code_decks = $('#' + _ss_ids['id_code_decks']).val();
        }
    }



    winOpen(webroot + 'codedecks/ss_codename_term?type=2&types=8', 500, 530);
}

function ss_country(_ss_ids)
{

    ss_ids_custom['country'] = _ss_ids;
    winOpen(webroot + 'codedecks/ss_country?type=2&types=8', 500, 530);
}

function ss_country_term(_ss_ids)
{


    ss_ids_custom['country_term'] = _ss_ids;
    winOpen(webroot + 'codedecks/ss_country_term?type=2&types=8', 500, 530);
}

/**
 * 
 * 打开查找费率的子窗口
 * @return
 */
function ss_rate(_ss_ids) {
    ss_ids_custom['rate'] = _ss_ids; //初始化自定义的表单元素

    winOpen(webroot + 'clients/ss_rate?type=2&types=8', 500, 530);
}



function ss_rate_term(_ss_ids) {

    ss_ids_custom['rate_term'] = _ss_ids; //初始化自定义的表单元素

    winOpen(webroot + 'clients/ss_rate_term?type=2&types=8', 500, 530);
}
//type=client


function ss_clear(type, _ss_ids)
{
    ss_ids_custom[type] = _ss_ids; //构造自定义的表单和字段
    var _ss_ids = ss_getIds(type); //将自定义的字段转换对象
    //遍历对象里面的每一个属性

    for (k in _ss_ids) {
        if (k == 'id_dr_plans')
            continue;
        //<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
        //el=query-id_clients;
        var el = $('#' + _ss_ids[k]);
        if (!el.length) {
            continue;
        }
        el.val(''); //清空字段对应表单的值()
        if (k == 'tz') {
            $('#query-tz').val(tz);
        }
    }
}
function ss_clear_input_select(obj) {
    var $this = $(obj);
    if ($(obj).prev().prev().hasClass('chosen-select')) {
        $(obj).prev().prev().val('').trigger('chosen:updated');
    } else if ($(obj).prev().prop('tagName') == 'SELECT') {
        $(obj).prev().val(0).trigger('change');
    } else {
        $(obj).prev().val('');
        if ($(obj).prev().prev().prop('tagName') == 'SELECT') {
            $(obj).prev().prev().val(0);
        }else{
            $(obj).prev().prev().val('');
        }
    }
    // $(obj).prev().val('');
    // $(obj).prev().prev().val('');
}
/*
 function ss_clear_input_select(obj,obj_type){
 var $this = $(obj);
 if(obj_type=='select'){
 $(obj).prev().val('');
 }else if(obj_type=='input'){
 $(obj).prev().val('');
 }
 }
 */
/**
 * 
 * data=>子窗口的数据
 * 向父窗口传值  将自窗口的数据(data)写入父窗口的文本框
 * @param type=client
 * @param data
 *  onclick='opener.ss_process("client", {"id_clients":"55","id_clients_name":"CW CORan \/ all accounts","id_accounts":"","account":"",
 *  "tz":"+0300","id_currencies":"","id_code_decks":null,"autoinvoice_cdr_output":"xls","autoinvoice_cdr_file":"",
 *  "autoinvoice_output":"pdf","id_dr_plans":""});winClose();'
 * data选中的一行数据
 * @return
 */
function ss_process(type, data)
{
    var _ss_ids = ss_getIds(type); //获取client的字段


    //循环client的每一个字段 k=id_clients,id_clients_name
    for (k in _ss_ids) {
        //如果这个字段没有值就设为''  
        if (data[k] == undefined) {
            data[k] = '';
        }
        var el = $('#' + _ss_ids[k]); //在父窗口的界面上寻找字段所对应的文本域

        if (!el.length) {
            continue;
        }
        if (el.is('input[type=text],input[type=hidden],select')) {
            el.val(data[k]); //数据写入对应的文本域
        } else if (el.is('input[type=checkbox]')) {
            el.attr('checked', Boolean(data[k]));
        } else {
            el.text(data[k]);
        }
    }
}


/**
 * Calculates period from current time
 * 根据这个下拉框计算时间
 * type=<select id="query-smartPeriod" onchange="setPeriod(this.value)" name="query[smartPeriod]" class="input in-select">
 * <option value="custom" selected="selected">custom</option>
 * <option value="curDay">today</option>
 * <option value="prevDay">yesterday</option>
 * <option value="curWeek">current week</option>
 * <option value="prevWeek">previous week</option>
 * <option value="curMonth">current month</option>
 * <option value="prevMonth">previous month</option>
 * <option value="curYear">current year</option>
 * <option value="prevYear">previous year</option>
 * </select>
 * 
 * 
 */
/**
 * Calculates period from current time
 */


function calcPeriod(type)
{
    period = new Array();
    period['startDate'] = '';
    period['startTime'] = '00:00:00';
    period['stopDate'] = '';
    period['stopTime'] = '23:59:59';
    if (currentTime == undefined) {
        cur_dt = new Date();
    } else {
        cur_dt = new Date(currentTime * 1000);
    }

    stop_dt = new Date(cur_dt);
    start_dt = new Date(cur_dt);
    switch (type) {
        case 'curYear':
            start_dt.setDate(1);
            start_dt.setMonth(0);
            stop_dt = new Date(start_dt);
            stop_dt.setYear(stop_dt.getFullYear() + 1);
            stop_dt.setDate(stop_dt.getDate() - 1);
            break;
        case 'prevYear':
            start_dt.setYear(start_dt.getFullYear() - 1);
            start_dt.setDate(1);
            start_dt.setMonth(0);
            stop_dt = new Date(start_dt);
            stop_dt.setYear(stop_dt.getFullYear() + 1);
            stop_dt.setDate(stop_dt.getDate() - 1);
            break;
        case 'curMonth':
            start_dt.setDate(1);
            break;
        case 'prevMonth':
            start_dt.setDate(1);
            start_dt.setMonth(start_dt.getMonth() - 1);
            stop_dt = new Date(start_dt);
            stop_dt.setMonth(stop_dt.getMonth() + 1);
            stop_dt.setDate(stop_dt.getDate() - 1);
            break;
        case 'curWeek':
            dow = cur_dt.getDay();
            if (dow == 0) {
                dow = 7;
            }
            start_dt = new Date(cur_dt.getTime() - (dow - 1) * 24 * 3600 * 1000);
            break;
        case 'prevWeek':
            dow = cur_dt.getDay();
            if (dow == 0) {
                dow = 7;
            }
            start_dt = new Date(cur_dt.getTime() - (dow - 1) * 24 * 3600 * 1000);
            start_dt.setDate(start_dt.getDate() - 7);
            stop_dt = new Date(start_dt);
            stop_dt.setDate(stop_dt.getDate() + 6);
            break;
        case 'curDay':
            break;
        case 'prevDay':
            start_dt.setDate(start_dt.getDate() - 1);
            stop_dt = new Date(start_dt);
            break;
    }

    period['startDate'] = printf('%04d-%02d-%02d', start_dt.getFullYear(), start_dt.getMonth() + 1, start_dt.getDate());
    period['stopDate'] = printf('%04d-%02d-%02d', stop_dt.getFullYear(), stop_dt.getMonth() + 1, stop_dt.getDate());
    return period;
}


/**
 * Sets period into text fields
 */
//function setPeriod(type, current)
//{
//    $("#query-start_date-wDt").click(function() {
//        WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});
//    });
//    $("#query-stop_date-wDt").click(function() {
//        WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});
//    });
//    if (type != 'custom') {
//        period = calcPeriod(type, current);
//        $('#query-start_date-wDt').val(period['startDate']);
//        $('#query-start_time-wDt').val(period['startTime']);
//        $('#query-stop_date-wDt').val(period['stopDate']);
//        $('#query-stop_time-wDt').val(period['stopTime']);
//    }
//    $('#query-smartPeriod').val(type);
//}



/**
 * Sets period into text fields 统计报表时间设置 
 *通过下拉框设置时间
 *
 *
 *
 * 
 */
function setPeriod(type, current)
{
    $("#query-start_date-wDt").click(function() {
        WdatePicker({dateFmt: 'yyyy-MM-dd'});
    });
    $("#query-start_time-wDt").click(function() {
        WdatePicker({dateFmt: 'HH:mm:ss'});
    });
    $("#query-stop_date-wDt").click(function() {
        WdatePicker({dateFmt: 'yyyy-MM-dd'});
    });
    $("#query-stop_time-wDt").click(function() {
        WdatePicker({dateFmt: 'HH:mm:ss'});
    });
    if (type != 'custom') {
        //如果不是自定义时间
        document.getElementById("query-start_date-wDt").onfocus = ""; //开始日期
        document.getElementById("query-start_time-wDt").onfocus = ""; //开始时间
        document.getElementById("query-stop_date-wDt").onfocus = ""; //结果日期
        document.getElementById("query-stop_time-wDt").onfocus = ""; //结束时间
        period = calcPeriod(type, current); //通过选择的值计算时间
        $('#query-start_date-wDt').val(period['startDate']);
        $('#query-start_time-wDt').val(period['startTime']);
        $('#query-stop_date-wDt').val(period['stopDate']);
        $('#query-stop_time-wDt').val(period['stopTime']);
    } else {

        //自定义时间
        //document.getElementById("query-start_date-wDt").onfocus=function(){WdatePicker({dateFmt:'yyyy-MM-dd'});};
        //document.getElementById("query-start_time-wDt").onfocus=function(){WdatePicker({dateFmt:'HH:mm:ss'});};
        //document.getElementById("query-stop_date-wDt").onfocus=function(){WdatePicker({dateFmt:'yyyy-MM-dd'});};
        //document.getElementById("query-stop_time-wDt").onfocus=function(){WdatePicker({dateFmt:'HH:mm:ss'});};


    }
    $('#query-smartPeriod').val(type);
}


/**
 * Watch that stop time is rounded / for reports
 */
function watchStopTime()
{
    el = $('#stop_time');
    hours = el.val().substring(0, 2);
    if (hours.length == 1) {
        hours = '0' + hours;
    }
    el.val(hours + ':59:59');
}
function startWatchStopTime()
{
    $('#stop_time').bind('blur', watchStopTime);
}


function closeAlert(a, type) {
    $(a).parent().hide();
    $.get('/admin/_view/ajax?act=off&type=' + type);
    return false;
}


/**
 * Cookies management
 */
function setCookie(c_name, value, expiredays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
}

function getCookie(c_name)
{
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }

            return unescape(document.cookie.substring(c_start, c_end));
        }
    }

    return "";
}


function showAccounts(o, e, block) {
    e.stopPropagation();
    if ($('#' + block).css('display') == 'none') {
        $('#' + block).show();
        $(o).attr('src', $(o).attr('src').replace('plus', 'minus'));
    } else {
        $('#' + block).hide();
        $(o).attr('src', $(o).attr('src').replace('minus', 'plus'));
    }
    return false;
}




/*jQuery公共弹出层*/
function showDiv(pop_id, pop_width, pop_height, pop_url,pop_title) {
    /*
     var pop_obj = $("#"+pop_id);
     var margin_left=pop_width/2;
     var margin_top=pop_height/2;
     
     pop_obj.css("width",pop_width+"px");
     pop_obj.css("height",pop_height+"px");
     pop_obj.css("position","fixed!important");
     pop_obj.css({"position": "absolute","display":"","left":"50%","top":"50%","z-index":"9999"});
     pop_obj.css("margin-left","-"+margin_left+"px!important");//FF IE7 该值为本身高的一半
     pop_obj.css("margin-top","-"+margin_top+"px!important");//FF IE7 该值为本身高的一半
     pop_obj.css("margin-top","0px");
     
     if(pop_url!=''){
     $.get(pop_url,function(data){
     //3.接受从服务器端返回的数据
     //alert(data);
     //4.将服务器端的返回的数据显示到页面上
     //取到用来显示结果信息的节点
     var resultObj = $("#pop-content");
     resultObj.html(data);
     });
     }
     
     pop_obj.show();
     $("#pop-clarity").show();
     */
    if (!$('#dd').length) {
        $(document.body).append("<div id='dd'></div>");
    }
    var $dd = $('#dd');
    var $form = null;
    $dd.load(pop_url,
            {},
            function(responseText, textStatus, XMLHttpRequest) {
                $dd.dialog({
                    'title': pop_title,
                    'width': pop_width,
                    'height': pop_height,
                    'modal':true,
                    'create': function(event, ui) {
                        $form = $('form', $dd);
                        $form.validationEngine();
                    }
                });
            }
    );
    $dd.append("<input type='hidden' class='foreign_id' value='"+pop_id+"' />");
    //return pop_obj;
}

function closeDiv(pop_id) {
    $("#" + pop_id).hide();
    $("#pop-clarity").hide();
}
/*//jQuery公共弹出层*/

/**
 *报表report页面上的Group By
 */
/*
 $(document).ready(function(){
 var group_by = $('#group_by');
 var group_by_list = $('.group_by_list');
 group_by_list.hide();
 group_by.css('cursor','pointer').toggle(function(){
 $('img', $(this)).attr('src', webroot+'images/bullet_toggle_minus.png');
 group_by_list.show();
 }, function() {
 $('img', $(this)).attr('src', webroot+'images/bullet_toggle_plus.png');
 group_by_list.hide();
 });
 
 });
 */

function jGrowl_to_notyfy(msg, notyfy_type_json)
{
    var notyfy_type_jGrowl = notyfy_type_json.theme;
    if (notyfy_type_jGrowl == 'jmsg-success')
    {
        var notyfy_type = "success";
    }
    else if (notyfy_type_jGrowl == 'jmsg-error')
    {
        var notyfy_type = "error";
    }
    else
    {
        var notyfy_type = "alert";
    }
    var this_notyfy = notyfy({
        text: msg,
        type: notyfy_type // alert|error|success|information|warning|primary|confirm
    });
    setTimeout(function() {
        this_notyfy.close();
    }, 5000);


}

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

