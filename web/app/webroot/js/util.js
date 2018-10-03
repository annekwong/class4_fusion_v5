//提示消息居中显示
jQuery.jGrowl.defaults.position = 'top-center';

//去空格
String.prototype.trim = function() {
    return this.replace(/(^\s*)|(\s*$)/g, "");
};

/*
 * 创建XMLDocument对象
 */
function createXMLDoc() {
    var xmlDoc;
    var arrSignatures = ["MSXML2.DOMDocument.5.0", "MSXML2.DOMDocument.4.0",
        "MSXML2.DOMDocument.3.0", "MSXML2.DOMDocument",
        "Microsoft.XmlDom"
    ];
    try {
        for (var i = 0; i < arrSignatures.length; i++) {
            xmlDoc = new ActiveXObject(arrSignatures[i]);
            return xmlDoc;
        }
    } catch (e) { //For Firefox
        try {
            xmlDoc = document.implementation.createDocument("", "", null);
        } catch (e) {
            alert("XMLDocument object can not be created");
        }
    }
    return xmlDoc;
}

/*
 * 将字符串转换为XMLDocument对象
 *var oSerializer=new XMLSerializer();//可以输出转换后的xml Only Firefox IE下直接 xmlDoc.xml即可
 *var sXml=oSerializer.serializeToString(xmlDoc,"text/xml");
 */
function strToXML(xmlstr) {
    var xmlDoc = createXMLDoc();//Create the XML document object;
    try {//For IE
        xmlDoc = xmlDoc.loadXML(xmlstr);
    } catch (e) {//For Firefox or others
        var oParser = new DOMParser();
        xmlDoc = oParser.parseFromString(xmlstr, "text/xml");
    }
    return xmlDoc;
}



/*
 * 替换特殊字符
 */
function convertSpecical(data) {
    data = data.replace(/&lt;/g, "<");
    data = data.replace(/&gt;/g, ">");
    data = data.replace(/&quot;/g, "\"");
    return data;
}


/*
 *载入XML文件将其转换为XML Document 对象
 */
function loadXMLFile(fileName) {
    var xmlDoc = createXMLDoc();//Create the XML document object;
    xmlDoc = xmlDoc.load(fileName);
    return xmlDoc;
}

/*
 * 删除一行
 */
function delConfirm(obj, url) {
    if (confirm("Are you sure to delete this item?")) {
        obj.href = url;
    }
}

//获取鼠标位置
function mousePosition(ev) {
    var e = ev || window.event;
    if (e.pageX || e.pageY) {
        return {x: e.pageX, y: e.pageY};
    }
    else {
        return {
            x: e.clientX + document.body.scrollLeft - document.body.clientLeft,
            y: e.clientY + document.body.scrollTop - document.body.clientTop
        };
    }
}

/*
 * 鼠标右击时显示右击菜单
 */
function right_click(e) {
    var even = e || window.event;
    document.body.oncontextmenu = function(even) {
        var mousep = mousePosition(even);
        var menu = document.getElementById("showMenu");
        menu.style.position = "absolute";

        var width = document.body.clientWidth;//页面宽度
        var height = document.body.clientHeight;//页面高度

        var x = parseInt(mousep.x) - 30;
        var y = parseInt(mousep.y) - 5;

        if ((parseInt(mousep.x) + parseInt(menu.style.width)) >= width) {
            x = x - parseInt(menu.style.width) - 25;
        }

        if ((parseInt(mousep.y) + parseInt(menu.style.height)) >= height) {
            y = y - parseInt(menu.style.height) - 50;
        }

        menu.style.display = "block";
        menu.style.left = x + "px";
        menu.style.top = y + "px";

        if (document.all)
            window.event.returnValue = false;// for IE
        else
            even.preventDefault();
    };
}

//点击body时隐藏右击菜单
function hidden() {
    document.documentElement.oncontextmenu = function() {
        return false;
    };
    document.body.onclick = function() {
        document.getElementById("showMenu").style.display = "none";
    };
}

/*
 * 加背景图片
 */
function bgimage(obj) {
    obj.style.background = "url('/exchange/img/title-menu.png')";
}

function noimage(obj) {
    obj.style.backgroundImage = "";
}

/*
 *  选中下拉框
 */
function selected(id, value) {
    var s = document.getElementById(id);
    var loop = s.options.length;
    for (var i = 0; i < loop; i++) {
        var c = s[i];
        if (c.value) {
            if (c.value == value) {
                c.selected = true;
                break;
            }
        }
    }
}

/*
 * 切换显示表格
 */
function bypercentage(obj) {
    var is_disabled = true;
    if (parseInt(obj.value) == 0) {
        is_disabled = false;
    }

    for (var i = 1; i <= 8; i++) {
        if (document.getElementById("percentage_" + i))
            document.getElementById("percentage_" + i).disabled = is_disabled;
    }
}

/*
 * 表单提交时 清除不要提交的值
 */
function whensubmit() {
    //清空没有选择resource 的百分比的值
    for (var i = 1; i <= 8; i++) {
        var se = document.getElementById("percentage_" + i);
        if (se) {
            if (se.disabled == true) {
                se.disabled = false;
                document.getElementById("percentage_" + i).value = "";
            }
        }
    }

    if (document.getElementById("totalpercentages")) {
        document.getElementById("totalpercentages").value = document.getElementById("resourcetab").rows.length;
    }

}

/*
 * 显示详细时间段情况
 */
function showTimeProfile(e, st, et) {
    var div = document.getElementById("infodiv");
    //var xias = mousePosition(e);
    var obj = e.target || e.srcElement;
    div.style.zIndex = 99;
    div.style.display = "block";
    div.style.left = "38%";
    document.getElementById("st").innerHTML = st;
    document.getElementById("et").innerHTML = et;
}

/*
 * 隐藏详细时间段情况
 */
function hideTimeProfile() {
    document.getElementById("infodiv").style.display = "none";
}

/*
 * 显示透明的层  覆盖页面 使得页面不能点击
 * css文件中需要有个 "#cover" 的样式
 * #cover{
 background: #fff;
 position: absolute;
 left: 0px;
 top: 0px;
 filter:alpha(opacity=50); // IE //
 -moz-opacity:0.5; // Moz + FF 
 opacity: 0.5;
 }
 * 需要使用的页面需要加一个div 如：
 * <div id="cover"></div> 即可
 * id:
 */


function cover(id) {
    var cover = document.getElementById("cover");
    with (cover) {
        //style.zIndex = 1;
        style.width = parent.document.body.offsetWidth + "px";
        style.height = parent.document.body.scrollHeight + "px";
        style.display = "";
    }
    var showDiv = document.getElementById(id);
    showDiv.style.display = "";
    //showDiv.style.zIndex = 200000;
    return showDiv;
}

/*
 * 关闭透明的层
 */
function closeCover(id) {
    //if(confirm('Note:Do you want to delete this object ?')){
    var showDiv = document.getElementById(id);
    showDiv.style.display = "none";
    var cover = document.getElementById("cover");
    cover.style.display = "none";
//	}
}

//全选或不选
function checkAllOrNot(obj, tabid) {
    // var tab = document.getElementById(tabid);
    // var loop = tab.rows.length;
    // var s = obj.checked;
    // for (var i = 0; i < loop; i++) {
    //     tab.rows[i].cells[0].childNodes[1].checked = s;
    // }

    if($(obj).attr('checked') == 'checked'){
        $('#producttab input[type="checkbox"]').prop('checked',true);
    }else{
        $('#producttab input[type="checkbox"]').prop('checked',false);
    }
}

function checkAll(obj, containerId) {
    jQuery("#" + containerId + " input[type=checkbox]").attr('checked', obj.checked);
}

/*
 *添加 Digit Mapping 或 Product
 */
function add(nameId, url) {
    if (typeof nameId == "object") {
        if (nameId.length) {
            var loop = nameId.length;
            var tmpUrl = '?a=1';
            for (var i = 0; i < loop; i++) {
                var name = document.getElementById(nameId[i]).value;
                tmpUrl += "&name" + i + "=" + name;
            }
            location = url + tmpUrl;
        }
    } else {
        var name = document.getElementById(nameId).value;//名字
        location = url + "?name=" + name;
    }
}


/**
 * 跟进投诉建议
 * @param nameId  跟进内容
 * @param url //提交地址
 * @return
 */

function addfeedback(nameId, url, status) {
    var name = document.getElementById(nameId).value;//名字
    location = url + "/" + name + "/" + jQuery('.ss:checked').val() + "/" + status;
}


/*
 * 添加系统终端类型
 */
function addsystem_update_types() {
    var id = document.getElementById('update_id').value;//名字
    var name = document.getElementById('update_name').value;//名字
    location = "/exchange/sysupdates/addsystem_update/" + id + "/" + name + "/";
}


function deluploadbytime(url) {
    var start = document.getElementById("start_date").value;
    var end = document.getElementById("end_date").value;
    location = url + "/" + start + "/" + end;
    window.location.reload();

}


/**
 * 按时间段删除短信记录
 * @param url
 * @return
 */
function delsmsbytime(url) {
    var start = document.getElementById("start_date").value;
    var end = document.getElementById("end_date").value;
    location = url + "/" + start + "/" + end;
    window.location.reload();

}




/*
 *删除所有
 */
function deleteAll(url) {
    bootbox.confirm("Are you sure to remove all?", function(result) {
        if (result) {

            window.location.href = url;
        }
    });
}

// 删除选中的deleteSelected('tabid','/exchange/jurisdictionprefixs/del_selected_jur');
function deleteSelected(tabid, url, msg) {
    bootbox.confirm("Please confirm the " + msg + " to delete!", function(result) {
        if (result)
        {
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

            if (ids == '' || ids.length < 1) {
                jGrowl_to_notyfy("Please select the " + msg + " to delete!", {theme: 'jmsg-error'});
            } else {
                ids = ids.substring(0, ids.length - 1); // 去掉最后逗号
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


function approvedSelected(tabid, url) {
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

    if (ids == '' || ids.length < 1) {
        jQuery.jGrowl('No items selected');
    } else {
        ids = ids.substring(0, ids.length - 1); // 去掉最后逗号
        if (url.indexOf("?") != -1) {
            url = url + "&ids=" + ids;
        } else {
            url = url + "?ids=" + ids;
        }
        location = url;
    }
}



/*
 * 修改Product或者Digit Mapping的名字
 */
function modifyName(webroot, obj, controllerName, successMsg, updateColumn) {
    var cr = obj.parentNode.parentNode; // 当前行
    var c = cr.cells[updateColumn - 1]; // 第二列

    var inp = document.createElement("input"); // 创建文本框
    inp.value = c.innerHTML.trim();
    inp.size = 22;
    c.innerHTML = "";
    c.appendChild(inp);
    // 改变图片
    //obj.getElementsByTagName("img")[0].src = "/exchange/images/menuIcon_004.gif";
    jQuery(obj).find('img:nth-child(1)').attr('src', webroot + "img/menuIcon_004.gif")
    var cancel = obj.cloneNode(true);
    cancel.title = "Cancel";
    cancel.style.marginLeft = "20px";
    cancel.getElementsByTagName("img")[0].src = webroot + "img/rerating_queue.png";
    jQuery(cancel).click(function() {
        location.reload();
    }).attr('onclick', '').attr('href', '#');
    obj.parentNode.appendChild(cancel);
    // 保存
    obj.onclick = function() {
        var cel = cr.cells[updateColumn - 2];
        var id = cr.cells[updateColumn - 2].innerHTML.trim();
        if (cel.getElementsByTagName("a")[0]) {
            id = cel.getElementsByTagName("a")[0].innerHTML.trim();
        }
        if (controllerName == 'digits') {
            var id = cr.cells[updateColumn - 3].innerHTML.trim();
        }
        var name = inp.value.trim(); // New Name
        // if (name == '' || name.length < 1) {
        // jQuery.jGrowl("Please enter the product name");
        // return;
        // }

        jQuery.get(webroot + controllerName + "/modifyname?id=" + id + "&name="
                + name, function(data) {
                    var dreg = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})/; // 验证日期格式
                    // 返回日期格式 则代表修改成功 否则 失败
                    if (dreg.test(data)) {
                        jQuery.jGrowl(successMsg, {
                            theme: 'jmsg-success'
                        });
                        c.innerHTML = ""; // 移除文本框
                        c.innerHTML = inp.value;
                        // 更新最后修改时间
                        cr.cells[updateColumn].innerHTML = data;
                        // 改变图片
                        obj.getElementsByTagName("img")[0].src = webroot
                                + "images/editicon.gif";
                        // 切换事件
                        obj.onclick = function() {
                            modifyName(webroot, this, controllerName, successMsg, updateColumn);
                        };
                        jQuery(obj).parent().parent().find('a:nth-child(3)').remove();
                    } else {// 修改失败
                        jQuery.jGrowl(data, {
                            theme: 'jmsg-alert'
                        });
                    }
                });
    };
}


//根据Resource得到所有的Ingress和Port
function getIngress(webroot, s) {
    var host = document.getElementById("host");
    if (s.value.length >= 1) {
        jQuery.getJSON(webroot + "simulatedcalls/get_ingress_by_resource?r_id=" + s.value, function(data) {
            var datas = eval(data);
            var loop = datas.length;
            host.options.length = 0; //清空
            for (var i = 0; i < loop; i++) {
                var d = datas[i];
                if (i == 0) {
                    document.getElementById("ingress_ip").value = d.ip;
                }

                var option = document.createElement("option");
                option.innerHTML = d.ip + ":" + d.port;
                host.appendChild(option);
            }
        });
    } else {
        host.options.length = 0;
    }
}

function changeIngress(s) {
    if (s.value.length >= 1) {
        var v = s.value;
        v = v.substring(0, v.lastIndexOf(":"));
        document.getElementById("ingress_ip").value = v;
    }
}


/*
 * 弹出层
 */
//获取当前选中的div的标题
function openNew(title, src, widths, heights, type) {
//设置弹出层的大小
    if (!widths || !heights) {
        widths = 650;
        heights = 500;
    }


//创建层
    var infodiv = document.createElement("div");
    with (infodiv.style) {
        width = widths;
        height = heights;
        position = "absolute";
        left = (document.body.clientWidth - widths) / 2;
        top = (document.body.clientHeight - heights) / 2;
        infodiv.style.border = "2px #7BA5C8 solid";
        backgroundColor = "#D7E7FF";
        display = "none";
        zIndex = 88;
        //overflow = "hidden";
    }

    document.body.appendChild(infodiv);
    jQuery(infodiv).show("toggle");
    //标题div
    var titleDiv = document.createElement("div");
    titleDiv.innerHTML = title;
    titleDiv.style.background = "#D7E7FF";
    titleDiv.style.width = widths;
    titleDiv.style.textAlign = "center";
    titleDiv.style.fontSize = "20px";
    titleDiv.style.height = "45px";
    titleDiv.style.position = "absolute";
    titleDiv.style.top = "0";
    titleDiv.style.borderBottom = "2px #7BA5C8 solid";
    titleDiv.style.float = "left";
    //titleDiv.style.cursor = "po";
    titleDiv.style.backgroundRepeat = "no-repeat";
    titleDiv.style.backgroundPositionX = "center";
    //titleDiv.onmousedown = function(){move(this.parentNode,window.event);};
    infodiv.appendChild(titleDiv);
    //关闭按钮
    var img = document.createElement("img");
    img.src = "/exchange/icons/delete.png";
    img.style.position = "absolute";
    img.style.top = "13px";
    img.style.left = (parseInt(widths) - 35) + "px";
    img.title = "关闭";
    img.style.cursor = "pointer";
    titleDiv.appendChild(img);
    img.onclick = function() {
        closeCover('cover_tmp');
        document.body.removeChild(infodiv);
    };
    //显示网页
    var iframe = document.createElement("iframe");
    iframe.src = src;
    iframe.scrolling = 'no';
    iframe.style.width = "100%";
    iframe.style.height = parseInt(heights) - 35 + "px";
    iframe.frameborder = "0";
    iframe.style.marginTop = "45px";
    iframe.style.border = "0";
    iframe.style.overflow = "scroll";
    infodiv.appendChild(iframe);
}





/*
 * 弹出层
 */
//获取当前选中的div的标题
function loadPage(src, widths, heights, margin) {
//设置弹出层的大小
    if (!widths || !heights) {
        widths = 650;
        heights = 500;
    }


//创建层
    var infodiv = document.createElement("div");
    if (margin) {
        infodiv.style.marginTop = margin + "px";
    }
    infodiv.id = "infodivv";
    with (infodiv.style) {
        right = '641px';
        bottom = '21px';
        width = widths;
        height = "auto";
        position = "absolute";
        left = (document.body.clientWidth - widths) / 2;
        top = (document.body.clientHeight - heights) / 2;
        infodiv.style.border = "2px #7BA5C8 solid";
        display = "none";
        zIndex = 88;
    }

    document.body.appendChild(infodiv);
    jQuery(infodiv).show("toggle");
    //显示网页
    var iframe = document.createElement("iframe");
    iframe.id = "infodivif";
    iframe.src = src;
    iframe.scrolling = 'no';
    iframe.style.width = "600px;";
    iframe.style.height = "220px";
    iframe.frameborder = "0";
    iframe.style.border = "0";
    infodiv.appendChild(iframe);
    iframe.onload = function() {
        this.style.height = this.contentWindow.document.body.scrollHeight + "px";
    };
}



function Dsy()
{
    this.Items = {};
}
Dsy.prototype.add = function(id, iArray)
{
    this.Items[id] = iArray;
}
Dsy.prototype.Exists = function(id)
{
    if (typeof (this.Items[id]) == "undefined")
        return false;
    return true;
}

function change(v) {
    var str = "0";
    for (i = 0; i < v; i++) {
        str += ("_" + (document.getElementById(s[i]).selectedIndex - 1));
    }
    ;
    var ss = document.getElementById(s[v]);
    with (ss) {
        length = 0;
        options[0] = new Option(opt0[v], opt0[v]);
        if (v && document.getElementById(s[v - 1]).selectedIndex > 0 || !v)
        {
            if (dsy.Exists(str)) {
                ar = dsy.Items[str];
                for (i = 0; i < ar.length; i++)
                    options[length] = new Option(ar[i], ar[i]);
                if (v)
                    options[1].selected = true;
            }
        }
        if (++v < s.length) {
            change(v);
        }
    }
}

var dsy = new Dsy();
dsy.add("0", ["中华人民共和国", "韩国", "日本", "新加坡", "马来西亚", "菲律宾", "沙特阿拉伯", "朝鲜", "越南", "缅甸", "德国", "英国", "法国", "爱尔兰", "波兰", "西班牙", "意大利", "俄罗斯", "荷兰", "美国", "加拿大", "巴西", "阿根廷", "新西兰", "澳大利亚", "印度", "埃及"]);
dsy.add("0_0", ["安徽", "北京", "福建", "甘肃", "广东", "广西", "贵州", "海南", "河北", "河南", "黑龙江", "湖北", "湖南", "吉林", "江苏", "江西", "辽宁", "内蒙古", "宁夏", "青海", "山东", "山西", "陕西", "上海", "四川", "天津", "西藏", "新疆", "云南", "浙江", "重庆"]);
dsy.add("0_0_0", ["安庆", "蚌埠", "巢湖", "池州", "滁州", "阜阳", "合肥", "淮北", "淮南", "黄山", "六安", "马鞍山", "宿州", "铜陵", "芜湖", "宣城", "亳州"]);
dsy.add("0_0_1", ["北京"]);
dsy.add("0_0_2", ["福州", "龙岩", "南平", "宁德", "莆田", "泉州", "三明", "厦门", "漳州"]);
dsy.add("0_0_3", ["白银", "定西", "甘南藏族自治州", "嘉峪关", "金昌", "酒泉", "兰州", "临夏回族自治州", "陇南", "平凉", "庆阳", "天水", "武威", "张掖"]);
dsy.add("0_0_4", ["潮州", "东莞", "佛山", "广州", "河源", "惠州", "江门", "揭阳", "茂名", "梅州", "清远", "汕头", "汕尾", "韶关", "深圳", "阳江", "云浮", "湛江", "肇庆", "中山", "珠海"]);
dsy.add("0_0_5", ["百色", "北海", "崇左", "防城港", "桂林", "贵港", "河池", "贺州", "来宾", "柳州", "南宁", "钦州", "梧州", "玉林"]);
dsy.add("0_0_6", ["安顺", "毕节", "贵阳", "六盘水", "黔东南苗族侗族自治州", "黔南布依族苗族自治州", "黔西南布依族苗族自治州", "铜仁", "遵义"]);
dsy.add("0_0_7", ["白沙黎族自治县", "保亭黎族苗族自治县", "昌江黎族自治县", "澄迈县", "定安县", "东方", "海口", "乐东黎族自治县", "临高县", "陵水黎族自治县", "琼海", "琼中黎族苗族自治县", "三亚", "屯昌县", "万宁", "文昌", "五指山", "儋州"]);
dsy.add("0_0_8", ["保定", "沧州", "承德", "邯郸", "衡水", "廊坊", "秦皇岛", "石家庄", "唐山", "邢台", "张家口"]);
dsy.add("0_0_9", ["安阳", "鹤壁", "济源", "焦作", "开封", "洛阳", "南阳", "平顶山", "三门峡", "商丘", "新乡", "信阳", "许昌", "郑州", "周口", "驻马店", "漯河", "濮阳"]);
dsy.add("0_0_10", ["大庆", "大兴安岭", "哈尔滨", "鹤岗", "黑河", "鸡西", "佳木斯", "牡丹江", "七台河", "齐齐哈尔", "双鸭山", "绥化", "伊春"]);
dsy.add("0_0_11", ["鄂州", "恩施土家族苗族自治州", "黄冈", "黄石", "荆门", "荆州", "潜江", "神农架林区", "十堰", "随州", "天门", "武汉", "仙桃", "咸宁", "襄樊", "孝感", "宜昌"]);
dsy.add("0_0_12", ["常德", "长沙", "郴州", "衡阳", "怀化", "娄底", "邵阳", "湘潭", "湘西土家族苗族自治州", "益阳", "永州", "岳阳", "张家界", "株洲"]);
dsy.add("0_0_13", ["白城", "白山", "长春", "吉林", "辽源", "四平", "松原", "通化", "延边朝鲜族自治州"]);
dsy.add("0_0_14", ["常州", "淮安", "连云港", "南京", "南通", "苏州", "宿迁", "泰州", "无锡", "徐州", "盐城", "扬州", "镇江"]);
dsy.add("0_0_15", ["抚州", "赣州", "吉安", "景德镇", "九江", "南昌", "萍乡", "上饶", "新余", "宜春", "鹰潭"]);
dsy.add("0_0_16", ["鞍山", "本溪", "朝阳", "大连", "丹东", "抚顺", "阜新", "葫芦岛", "锦州", "辽阳", "盘锦", "沈阳", "铁岭", "营口"]);
dsy.add("0_0_17", ["阿拉善盟", "巴彦淖尔盟", "包头", "赤峰", "鄂尔多斯", "呼和浩特", "呼伦贝尔", "通辽", "乌海", "乌兰察布盟", "锡林郭勒盟", "兴安盟"]);
dsy.add("0_0_18", ["固原", "石嘴山", "吴忠", "银川"]);
dsy.add("0_0_19", ["果洛藏族自治州", "海北藏族自治州", "海东", "海南藏族自治州", "海西蒙古族藏族自治州", "黄南藏族自治州", "西宁", "玉树藏族自治州"]);
dsy.add("0_0_20", ["滨州", "德州", "东营", "菏泽", "济南", "济宁", "莱芜", "聊城", "临沂", "青岛", "日照", "泰安", "威海", "潍坊", "烟台", "枣庄", "淄博"]);
dsy.add("0_0_21", ["长治", "大同", "晋城", "晋中", "临汾", "吕梁", "朔州", "太原", "忻州", "阳泉", "运城"]);
dsy.add("0_0_22", ["安康", "宝鸡", "汉中", "商洛", "铜川", "渭南", "西安", "咸阳", "延安", "榆林"]);
dsy.add("0_0_23", ["上海"]);
dsy.add("0_0_24", ["阿坝藏族羌族自治州", "巴中", "成都", "达州", "德阳", "甘孜藏族自治州", "广安", "广元", "乐山", "凉山彝族自治州", "眉山", "绵阳", "南充", "内江", "攀枝花", "遂宁", "雅安", "宜宾", "资阳", "自贡", "泸州"]);
dsy.add("0_0_25", ["天津"]);
dsy.add("0_0_26", ["阿里", "昌都", "拉萨", "林芝", "那曲", "日喀则", "山南"]);
dsy.add("0_0_27", ["阿克苏", "阿拉尔", "巴音郭楞蒙古自治州", "博尔塔拉蒙古自治州", "昌吉回族自治州", "哈密", "和田", "喀什", "克拉玛依", "克孜勒苏柯尔克孜自治州", "石河子", "图木舒克", "吐鲁番", "乌鲁木齐", "五家渠", "伊犁哈萨克自治州"]);
dsy.add("0_0_28", ["保山", "楚雄彝族自治州", "大理白族自治州", "德宏傣族景颇族自治州", "迪庆藏族自治州", "红河哈尼族彝族自治州", "昆明", "丽江", "临沧", "怒江傈傈族自治州", "曲靖", "思茅", "文山壮族苗族自治州", "西双版纳傣族自治州", "玉溪", "昭通"]);
dsy.add("0_0_29", ["杭州", "湖州", "嘉兴", "金华", "丽水", "宁波", "绍兴", "台州", "温州", "舟山", "衢州"]);
dsy.add("0_0_30", ["重庆"]);
dsy.add("0_1", ["汉城特別市", "釜山广域市", "大邱广域市", "仁川广域市", "光州广域市", "大田广域市", "蔚山广域市", "京畿道", "江原道", "忠清北道", "忠清南道", "全罗北道", "全罗南道", "庆尚北道", "庆尚南道", "济州道"]);
dsy.add("0_1_0", ["汉城"]);
dsy.add("0_1_1", ["釜山", "机张郡"]);
dsy.add("0_1_2", ["大邱", "达城郡"]);
dsy.add("0_1_3", ["仁川", "江华郡", "瓮津郡"]);
dsy.add("0_1_4", ["光州"]);
dsy.add("0_1_5", ["大田"]);
dsy.add("0_1_6", ["蔚山", "蔚州郡"]);
dsy.add("0_1_7", ["水原市", "城南市", "安山市", "高阳市", "安养市", "富川市"]);
dsy.add("0_1_8", ["春川市", "原州市", "江陵市"]);
dsy.add("0_1_9", ["清州市"]);
dsy.add("0_1_10", ["天安市"]);
dsy.add("0_1_11", ["全州市", "群山市", "益山市"]);
dsy.add("0_1_12", ["木浦市", "丽水市", "顺天市"]);
dsy.add("0_1_13", ["浦项市", "龟尾市", "庆州市"]);
dsy.add("0_1_14", ["昌原市", "马山市", "晋州市"]);
dsy.add("0_1_15", ["济州市", "西归浦市", "北济州郡", "南济州郡"]);
dsy.add("0_2", ["东京都", "神奈川县", "大阪府", "爱知县", "北海道", "兵库县", "京都府", "福冈县", "神奈川县", "埼玉县", "广岛县", "宫城县", "福冈县", "千叶县"]);
dsy.add("0_2_0", ["东京"]);
dsy.add("0_2_1", ["横滨市"]);
dsy.add("0_2_2", ["大阪市"]);
dsy.add("0_2_3", ["名古屋市 "]);
dsy.add("0_2_4", ["札幌市"]);
dsy.add("0_2_5", ["神戸市"]);
dsy.add("0_2_6", ["京都市"]);
dsy.add("0_2_7", ["福冈市"]);
dsy.add("0_2_8", ["川崎市"]);
dsy.add("0_2_9", ["埼玉市"]);
dsy.add("0_2_10", ["广岛市"]);
dsy.add("0_2_11", ["仙台市"]);
dsy.add("0_2_12", ["北九州市 "]);
dsy.add("0_2_13", ["千叶市"]);
dsy.add("0_3", ["新加坡"]);
dsy.add("0_3_0", ["新加坡"]);
dsy.add("0_4", ["吉打 Kedah", "槟榔屿 Pulau Pinang", "霹雳 Perak", "吉兰丹 Kelantan", "丁加奴 Terengganu", "彭亨 Pahang", "雪兰莪 Selangor", "吉隆坡联邦直辖区 Kuala Lumpur", "布特拉再也联邦直辖区 Putrajaya", "森美兰 Sembilan", "马六甲 Melaka", "柔佛 Johor", "斗湖省 Tawau", "山打根省 Sandakan", "西海岸省 Pantai Barat"]);
dsy.add("0_4_0", ["亚罗士打 Alor Setar", "浮罗交怡 Langkawi", "古邦巴素 Kubang Pasu", "巴东得腊 Padang Terap", "哥打士打 Kota Setar"]);
dsy.add("0_4_1", ["槟城 George Town", "北区（北海） Utara (Butterworth)", "中区（大山脚） Tengah (Bkt. Mertajam)", "南区（高渊） Selatan (Nibong Tebal)", "东北 Timur Laut"]);
dsy.add("0_4_2", ["怡保 Ipoh", "拉律-马当 Larut & Matang", "近打 Kinta", "江沙 Kuala Kangsar"]);
dsy.add("0_4_3", ["哥打巴鲁 Kota Baharu", "道北 Tumpat", "哥登峇鲁 Kota Bharu", "巴西马 Pasir Mas"]);
dsy.add("0_4_4", ["瓜拉丁加奴 Kuala Terengganu", "勿述 Besut", "瓜拉丁加奴 Kuala Terengganu", "龙运 Dungun", "甘马挽 Kemaman"]);
dsy.add("0_4_5", ["关丹 Kuantan", "金马仑高原 Cameron Highlands", "立卑 Lipis", "关丹 Kuantan", "而连突 Jerantut"]);
dsy.add("0_4_6", ["莎亚南 Shah Alam ", "沙白安南 Sabak Bernam", "乌鲁雪兰莪 Ulu Selangor", "瓜拉雪兰莪 Kuala Selangor"]);
dsy.add("0_4_7", ["吉隆坡 Kuala Lumpur"]);
dsy.add("0_4_8", ["布特拉再也 Putrajaya"]);
dsy.add("0_4_9", ["芙蓉 Seremban", "日叻务 Jelebu", "仁保 Jempol"]);
dsy.add("0_4_10", ["马六甲 Melaka", "亚罗牙也 Alor Gajah"]);
dsy.add("0_4_11", ["新山 Johor Baharu", "昔加末 Segamat", "丰盛港 Mersing", "居銮 Keluang"]);
dsy.add("0_4_12", ["斗湖 Tawau", "拿笃 Lahad Datu"]);
dsy.add("0_4_13", ["山打根 Sandakan", "京那巴登岸 Kinabatangan"]);
dsy.add("0_4_14", ["哥打京那峇鲁（亚庇） Kota Kinabalu", "兰脑 Ranau", "古打毛律 Kota Belud", "斗亚兰 Tuaran"]);
dsy.add("0_5", ["伊罗戈斯 Ilocos", "卡加延河谷 Cagayan", "中央吕宋 Central Luzon", "甲拉巴松 Calabarzon", "比科尔 Bicol", "西米沙鄢 Western Visayas", "中米沙鄢 Central Visayas", "东米沙鄢 Eastern Visayas", "国家首都区 National Capital Region"]);
dsy.add("0_5_0", ["圣费尔南多* San Fernando"]);
dsy.add("0_5_1", ["土格加劳 Tuguegarao"]);
dsy.add("0_5_2", ["圣费尔南多* San Fernando"]);
dsy.add("0_5_3", ["奎松城 Quezon"]);
dsy.add("0_5_4", ["黎牙实比 Legaspi"]);
dsy.add("0_5_5", ["伊洛伊洛 Legaspi"]);
dsy.add("0_5_6", ["宿务 Cebu"]);
dsy.add("0_5_7", ["塔克洛班 Tacloban"]);
dsy.add("0_5_8", ["马尼拉 Manila"]);
dsy.add("0_6", ["利雅得 Ar Riyad", "麦加 Makkah", "麦地那 Al Madinah", "东部 Ash Sharqiyah", "卡西姆 Al Qasim", "哈伊勒 Ha'il", "塔布克 Tabuk", "北部边疆 Al Hudud ash Shamaliyah", "吉赞 Jizan", "纳季兰 Najran", "巴哈 Al Bahah", "朱夫 Al Jawf", "阿西尔 ‘Asir"]);
dsy.add("0_6_0", ["利雅得 Riyad", "海耶 Al-Kharj"]);
dsy.add("0_6_1", ["麦加 Makkah", "吉达 Jiddah", "塔伊夫 At-Ta'if"]);
dsy.add("0_6_2", ["麦地那 Madinah", "延布 Yanbu' al-Bahr"]);
dsy.add("0_6_3", ["达曼 Dammam", "胡富夫 Al-Hufūf", "姆巴拉兹 Al-Mubarraz", "朱拜勒 Al-Jubayl", "哈费尔巴廷 Hafar al-Bātin"]);
dsy.add("0_6_4", ["布赖代 Buraydah"]);
dsy.add("0_6_5", ["哈伊勒 Ha'il"]);
dsy.add("0_6_6", ["塔布克 Tabuk"]);
dsy.add("0_6_7", ["阿尔阿尔 Ar'ar"]);
dsy.add("0_6_8", ["吉赞 Jizan"]);
dsy.add("0_6_9", ["纳季兰 Najran"]);
dsy.add("0_6_10", ["巴哈 Al Bahah"]);
dsy.add("0_6_11", ["塞卡卡 Sakaka"]);
dsy.add("0_6_12", ["艾卜哈 Abhā", "海米斯穆谢特 Khamīs Mushayt"]);
dsy.add("0_7", ["平壤直辖市", "罗先直辖市", "平安南道", "平安北道", "慈江道", "两江道", "咸镜北道", "咸镜南道", "黄海北道", "黄海南道", "江原道"]);
dsy.add("0_7_0", ["平壤"]);
dsy.add("0_7_1", ["罗津"]);
dsy.add("0_7_2", ["南浦特级市", "平城市", "顺川市", "德川市", "安州市", "价川市"]);
dsy.add("0_7_3", ["新义州市", "龟城市", "定州市"]);
dsy.add("0_7_4", ["江界市", "满浦市", "煕川市"]);
dsy.add("0_7_5", ["恵山市"]);
dsy.add("0_7_6", ["清津市", "金策市", "会宁市"]);
dsy.add("0_7_7", ["咸兴市", "兴南市", "新浦市", "端川市"]);
dsy.add("0_7_8", ["沙里院市", "松林市", "开城市"]);
dsy.add("0_7_9", ["海州市"]);
dsy.add("0_7_10", ["元山市", "文川市"]);
dsy.add("0_8", ["河内市", "山罗", "奠边", "谅山", "河西", "清化", "义安", "广南", "嘉莱", "多乐", "平福", "金瓯"]);
dsy.add("0_8_0", ["河内市"]);
dsy.add("0_8_1", ["山罗"]);
dsy.add("0_8_2", ["奠边府市", "孟雷"]);
dsy.add("0_8_3", ["谅山市"]);
dsy.add("0_8_4", ["河东", "山西"]);
dsy.add("0_8_5", ["清化市", "岑山", "拜尚"]);
dsy.add("0_8_6", ["荣市", "扩路"]);
dsy.add("0_8_7", ["三歧", "会安"]);
dsy.add("0_8_8", ["波来古市", "安溪"]);
dsy.add("0_8_9", ["邦美蜀市"]);
dsy.add("0_8_10", ["东帅"]);
dsy.add("0_8_11", ["金瓯市"]);
dsy.add("0_9", ["实皆省 Sagaing", "望濑县 Monywa", "勃固省 Bago", "马圭省 Magway", "曼德勒省 Mandalay", "德林达依省 Tanintharyi", "伊洛瓦底省 Ayeyarwady", "仰光省 Yangon", "克钦邦 Kachin", "克耶邦 Kayah", "克伦邦 Kayin", "钦邦 Chin", "孟邦 Mon", "若开邦 Rakhine", "掸邦 Shan"]);
dsy.add("0_9_0", ["实皆 Sagaing"]);
dsy.add("0_9_1", ["望濑 Monywa"]);
dsy.add("0_9_2", ["勃固 Bago"]);
dsy.add("0_9_3", ["马圭 Magway"]);
dsy.add("0_9_4", ["曼德勒 Mandalay"]);
dsy.add("0_9_5", ["土瓦 Dawei"]);
dsy.add("0_9_6", ["勃生 Pathein"]);
dsy.add("0_9_7", ["仰光 Yangan "]);
dsy.add("0_9_8", ["密支那 Myitkyina"]);
dsy.add("0_9_9", ["垒固 Loi-kaw"]);
dsy.add("0_9_10", ["巴安 Pa-an"]);
dsy.add("0_9_11", ["哈卡 Haka"]);
dsy.add("0_9_12", ["毛淡棉 Mawlamyine"]);
dsy.add("0_9_13", ["实兑 Akyab"]);
dsy.add("0_9_14", ["东枝 Taunggyi"]);
dsy.add("0_10", ["巴登-符腾堡 Baden-Württemberg", "拜恩（巴伐利亚）  Bayern", "柏 林 Berlin", "勃兰登堡 Brandenburg", "不来梅 Bremen", "汉 堡 Hamburg", "黑 森 Hessen", "梅克伦堡-前波莫瑞 Mecklenburg-Vorpommern", "下萨克森  Niedersachsen", "北莱茵-威斯特法伦 Nordrhein-Westfalen", "莱茵兰-普法尔茨 Rheinland-Pfalz", "萨 尔 Saarland", "萨克森 Sachsen", "萨克森-安哈特 Sachsen-Anhalt", "石勒苏益格-荷尔斯泰因 Schleswig-Holstein", "图林根 Thüringen"]);
dsy.add("0_10_0", ["斯图加特  Stuttgart", "卡尔斯鲁厄 Karlsruhe", "弗赖堡 Freiburg", "图宾根 Tübingen"]);
dsy.add("0_10_1", ["慕尼黑 München ", "下拜恩 Niederbayern", "上普法尔茨 Oberpfalz", "上弗兰肯 Oberfranken", "中弗兰肯 Mittelfranken", "外弗兰肯 Unterfranken", "施瓦本 Schwaben"]);
dsy.add("0_10_2", ["柏林 Berlin"]);
dsy.add("0_10_3", ["波茨坦 Potsdam"]);
dsy.add("0_10_4", ["不来梅 Bremen"]);
dsy.add("0_10_5", ["汉堡 Hamburg"]);
dsy.add("0_10_6", ["达姆施塔特 Darmstadt", "吉森 Gieben", "卡塞尔 Kassel"]);
dsy.add("0_10_7", ["什未林 Schwerin"]);
dsy.add("0_10_8", ["不伦瑞克 Braunschweig", "汉诺威  Hannover"]);
dsy.add("0_10_9", ["杜塞尔多夫 Düsseldorf", "科隆 Koln", "明斯特 Münster", "代特莫尔特 Detmold"]);
dsy.add("0_10_10", ["科布伦次 Koblenz ", "特里尔 Trier", "莱茵黑森-普法尔茨"]);
dsy.add("0_10_11", ["萨尔布吕肯 Saarbrücken"]);
dsy.add("0_10_12", ["开姆尼斯 Chemnitz", "德累斯顿 Dresden", "莱比锡 Leipzig"]);
dsy.add("0_10_13", ["德绍 Dessau", "哈雷 Halle", "马格德堡 Magdeburg"]);
dsy.add("0_10_14", ["基尔 Kiel"]);
dsy.add("0_10_15", ["埃尔富特 Erfurt"]);
dsy.add("0_11", ["英格兰 England", "威尔士 Wales", "苏格兰 Scotland", "北爱尔兰 Northern Ireland"]);
dsy.add("0_11_0", ["坎布里亚 Cumbria", "兰开夏 Lancashire ", "布莱克本 Blackburn with Darwen", "大曼彻斯特 Greater Manchester", "柴郡 Cheshire ", "诺森伯兰 Northumberland", "达勒姆 Durham", "北约克郡 North Yorkshire", "约克郡东区 East Riding of Yorkshire", "西约克郡 West Yorkshire", "南约克郡 South Yorkshire", "林肯郡 Lincolnshire", "诺丁汉郡 Nottinghamshire", "斯塔福德郡 Staffordshire", "诺福克 Norfolk", "伦敦 London", "白金汉郡 Buckinghamshire", "牛津郡 Oxfordshire", "格洛斯特郡 Gloucestershire"]);
dsy.add("0_11_1", ["康威 Conwy *", "圭内斯 Gwynedd", "锡尔迪金 Ceredigion", "波伊斯 Powys", "彭布罗克郡 Pembrokeshire", "卡马森郡 Carmarthenshire"]);
dsy.add("0_11_2", ["苏格兰高地 Highland", "马里 Moray", "阿伯丁郡 Aberdeenshire", "安格斯 Angus", "珀斯-金罗斯 Perth and Kinross", "法夫 Fife", "斯特灵 Stirling", "阿盖尔-比特 Argyll and Bute", "苏格兰边界 Scottish Borders", "邓弗里斯-加洛韦 Dumfries and Galloway"]);
dsy.add("0_11_3", ["阿兹 Ards", "卡斯尔雷 Castlereagh", "唐 Down", "贝尔法斯特 Belfast, City of", "利斯本 Lisburn", "巴利米纳 Ballymena", "莫伊尔 Moyle", "阿马 Armagh"]);
dsy.add("0_12", ["法兰西岛 Ile-de-France", "香槟-阿登 Champagne-Ardenne", "皮卡第 Picardie", "上诺曼底 Haute-Normandie", "中央 Centre", "下诺曼底 Basse-Normandie", "勃艮第 Bourgogne", "北部-加莱海峡 Nord-pas-de-Calais", "洛林 Lorraine", "阿尔萨斯 Alsace", "弗朗什孔泰 Franche-Comté", "卢瓦尔河地区 Pays de la Loire", "布列塔尼 Bretagne", "普瓦图-夏朗德 Poitou-Charentes", "阿基坦 Aquitaine", "南部-比利牛斯 Midi-Pyrénées", "利穆赞 Limousin", "罗讷-阿尔卑斯 Rhone-Alpes", "奥弗涅 Auvergne", "朗格多克-鲁西永 Languedoc-Roussillon", "普罗旺斯-阿尔卑斯-蓝色海岸 Provence-Alpes-Cote d'Azur", "科西嘉 Corse"]);
dsy.add("0_12_0", ["巴黎 Paris"]);
dsy.add("0_12_1", ["兰斯 Reims"]);
dsy.add("0_12_2", ["亚眠 Ameiens"]);
dsy.add("0_12_3", ["鲁昂 Rouen"]);
dsy.add("0_12_4", ["奥尔良 Orléans"]);
dsy.add("0_12_5", ["卡昂 Caen"]);
dsy.add("0_12_6", ["第戎 Dijon"]);
dsy.add("0_12_7", ["里尔 Lille"]);
dsy.add("0_12_8", ["南锡 Nancy"]);
dsy.add("0_12_9", ["斯特拉斯堡 Strasbourg"]);
dsy.add("0_12_10", ["贝桑松 Besancon"]);
dsy.add("0_12_11", ["南特 Nantes"]);
dsy.add("0_12_12", ["雷恩 Rennes"]);
dsy.add("0_12_13", ["普瓦捷 Poitiers"]);
dsy.add("0_12_14", ["波尔多 Bordeaux"]);
dsy.add("0_12_15", ["图卢兹 Toulouse"]);
dsy.add("0_12_16", ["利摩日 Limoges"]);
dsy.add("0_12_17", ["里昂 Lyon"]);
dsy.add("0_12_18", ["克莱蒙费朗 Clerment-Ferrand"]);
dsy.add("0_12_19", ["蒙彼里埃 Montpellier"]);
dsy.add("0_12_20", ["马赛 Marseille"]);
dsy.add("0_12_21", ["阿雅克肖 Ajaccio"]);
var s = ["country", "state", "city"];
var opt0 = ["请选择国家", "请选择省份或州", "请选择地级市或县"];
function setup()
{
    document.getElementById(s[0]).onchange = new Function("change(" + (1) + ")");
    /*for(i=0;i <s.length-1;i++) 
     document.getElementById(s[i]).onchange=new Function("change("+(i+1)+")"); */
    change(0);
}



var rowcount = 1;
function add_route_resource(msg1, msg2, v1, v2) {
    if (rowcount == 8) {
        jQuery.jGrowl(language.util.LandingagatewayCanonlyaddeight, {theme: 'jmsg-alert'});
        return;
    }
    var tmpR = document.getElementById("resourceconfig");
    var tab = document.getElementById("resourcetab");
    var cloneR = tmpR.cloneNode(true);
    cloneR.cells[0].innerHTML = msg1 + (rowcount + 1);
    cloneR.getElementsByTagName("span")[0].innerHTML = msg2 + (rowcount + 1);
    cloneR.getElementsByTagName("select")[0].id = "resource_id_" + (rowcount + 1);
    cloneR.getElementsByTagName("select")[0].name = "resource_id_" + (rowcount + 1);
    cloneR.getElementsByTagName("select")[0].selectedIndex = 0;
    cloneR.getElementsByTagName("input")[0].id = "percentage_" + (rowcount + 1);
    cloneR.getElementsByTagName("input")[0].name = "percentage_" + (rowcount + 1);
    cloneR.getElementsByTagName("input")[0].value = "";
    cloneR.getElementsByTagName("input")[0].className = "input in-text";
    if (v1) {
        cloneR.getElementsByTagName("select")[0].value = v1;
    }
    if (v2) {
        cloneR.getElementsByTagName("input")[0].value = v2;
    }
    var x = document.createElement("a");
    x.innerHTML = "<img src='/exchange/icons/delete.png'/>";
    x.href = "javascript:void(0)";
    x.onclick = function() {
        tab.removeChild(cloneR);
        var ss = tab.rows.length;
        for (var i = 0; i < ss; i++) {
            var r = tab.rows[i];
            r.cells[0].innerHTML = msg1 + (i + 1);
            r.getElementsByTagName("span")[0].innerHTML = msg2 + (i + 1);
            r.getElementsByTagName("select")[0].id = "resource_id_" + (i + 1);
            r.getElementsByTagName("select")[0].name = "resource_id_" + (i + 1);
            r.getElementsByTagName("input")[0].id = "percentage_" + (i + 1);
            r.getElementsByTagName("input")[0].name = "percentage_" + (i + 1);
        }
        rowcount--;
    };
    cloneR.cells[1].appendChild(x);
    tab.appendChild(cloneR);
    rowcount++;
}



// [{tag:'input',readonly:'true' options:'',defaultV:'123',className:'class1
// class2',ownevents:{onmouseover:"over()"}}]
function createRow(tab_id, columns) {
    var tr = document.createElement("tr");
    var col_len = columns.length;
    for (var i = 0; i < col_len; i++) {
        var td = document.createElement("td");
        var c = columns[i];
        var ele = null;
        if (c.tag)
            ele = document.createElement(c.tag);
        else
            ele = document.createElement("input");
        if (c.defaultV)
            ele.value = c.defaultV;
        if (c.type) {
            ele.type = c.type;
        }

        if (c.style)
            ele.style = c.style;
        if (c.readonly)
            ele.readOnly = true;
        if (c.className)
            ele.className = c.className;
        if (c.tag && c.tag.toLowerCase() == "select") {
            if (c.addtion)
                ele.appendChild(c.addtion);
            var option_len = c.options.length;
            for (var o = 0; o < option_len; o++) {
                var op = document.createElement("option");
                var opi = c.options[o];
                var ats = null;
                if (typeof opi == "object") {
                    if (opi.length) {
                        ats = getAllAttributes(opi[0]);
                        op.value = opi[0][ats[0]];
                        op.innerHTML = opi[0][ats[1]];
                    } else {
                        ats = getAllAttributes(opi);
                        op.value = opi[ats[0]];
                        op.innerHTML = opi[ats[1]];
                    }
                } else {
                    op.value = opi;
                    op.innerHTML = opi;
                }

                if (c.selected) {
                    if (op.innerHTML.trim() == c.selected)
                        op.selected = true;
                }
                ele.appendChild(op);
            }
        }
        if (c.ownevents) {
            for (var attr in c.ownevents)
                ele[attr] = c.ownevents[attr];
        }

        if (c.hidden)
            ele.style.display = "none";
        td.appendChild(ele);
        if (c.innerHTML)
            td.innerHTML = c.innerHTML;
        tr.appendChild(td);
    }

    var tab = document.getElementById(tab_id);
    if (tab.rows.length % 2 == 0)
        tr.className = "row-1";
    else
        tr.className = "row-2";
    tab.appendChild(tr);
    return tr;
}

function getAllAttributes(obj) {
    var ats = [];
    for (var attr in obj) {
        ats.push(attr);
    }
    return ats;
}

function selected_all_cards(obj, id) {
    var c = document.getElementById(id);
    var chx = c.getElementsByTagName("input");
    var loop = chx.length;
    for (var i = 0; i < loop; i++)
        chx[i].checked = obj.checked;
}


function editRow(currRow, columns) {
    if (!currRow)
        return;
    var loop = columns.length;
    for (var i = 0; i < loop; i++) {
        var c = columns[i];
        if (c.hidden || getAllAttributes(c).length == 0)
            continue;
        if (!c.tag)
            c.tag = "input";
        var ele = document.createElement(c.tag);
        if (c.type) {
            ele.type = c.type;
        }
        if (c.value) {
            ele.value = c.value;
        }


        if (c.readonly)
            ele.readOnly = true;
        if (c.className)
            ele.className = c.className;
        if (c.tag && c.tag.toLowerCase() == "select") {
            if (c.addition)
                ele.appendChild(c.addition);
            var option_len = c.options.length;
            for (var o = 0; o < option_len; o++) {
                var op = document.createElement("option");
                var opi = c.options[o];
                var ats = null;
                if (typeof opi == "object") {
                    if (opi.length) {
                        ats = getAllAttributes(opi[0]);
                        op.value = opi[0][ats[0]];
                        op.innerHTML = opi[0][ats[1]];
                    } else {
                        ats = getAllAttributes(opi);
                        op.value = opi[ats[0]];
                        op.innerHTML = opi[ats[1]];
                    }
                } else {
                    op.value = opi;
                    op.innerHTML = opi;
                }

                if (c.selected) {
                    if (op.innerHTML.trim() == c.selected)
                        op.selected = true;
                }
                ele.appendChild(op);
            }
        }

        if (c.ownevents) {
            for (var attr in c.ownevents)
                ele[attr] = c.ownevents[attr];
        }

        if (c.style) {
            for (var attr in c.style)
                ele.style[attr] = c.style[attr];
        }

        var tmpv = currRow.cells[i].innerHTML;
        if (tmpv.indexOf("<") != -1 || tmpv.indexOf(">") != -1) {
            while (tmpv.indexOf("<") != -1 || tmpv.indexOf(">") != -1) {
                var div = document.createElement("div");
                div.innerHTML = tmpv;
                tmpv = div.firstChild.innerHTML;
            }
        }

        if (c.tag && c.tag.toLowerCase() != "select") {
            ele.value = tmpv.trim();
        }

        currRow.cells[i].innerHTML = "";
        currRow.cells[i].appendChild(ele);
    }
}

function displayTime(divID, value) {


    $('#context_right_nav_div_down1').hide();
    $('#context_right_nav_div_down2').hide();
    $('#context_right_nav_div_down3').hide();
    if (divID.indexOf('1') > 0) {
        $(divID).show();
    }
    else {
        $('#context_right_nav_div_down2').show();
        $('#swSpan').hide();
        $('#ewSpan').hide();
        if (divID.indexOf('2') > 0) {

            $('#swSpan').show();
            $('#ewSpan').show();
        }

    }

    $("input[name='type']").get(value).focus();
}

function selectRadio(value) {


    $("input[name='type']").get(value).checked = true;
    ;
}

function liSelect(component) {

    $('#li1').removeAttr('class');
    $('#li2').removeAttr('class');
    $('#li3').removeAttr('class');
    $(component).parent('li').attr('class', 'selected');
}

function switchTime(component, count) {

    var divID = "#context_right_nav_div_down" + (count + 1);
    liSelect(component);
    selectRadio(count);
    displayTime(divID, count);
}

function setCookie(c_name, value, expiredays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
}
function delCookie(name)//删除cookie
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null)
        document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}

function getCookie(c_name) {
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

function moveOption(e, t) {
    var selected = $("#select2").find("option:selected");
    if (selected.size() > 1) {
        showMessages_new("[{'field':'#select2','code':'101','msg':'Sorry,choose only one !'}]");
        return;
    }
    var prev = $("#select2").find("option:selected").prev();
    var next = $("#select2").find("option:selected").next();
    var sel_index = $("#select2").get(0).selectedIndex;
//    var maxIndex = $("#select2 option:last").attr("index");
    var maxIndex=$("#select2 option").size() - 1;  
    if (t == 'up') {

        //向上移动
        if (sel_index > 0) {
            prev.before(selected);
        } else {
            showMessages_new("[{'field':'#select2','code':'101','msg':'Sorry,could not  move! '}]");
        }
    } else {
        //向下移动
        if (sel_index < maxIndex) {
            next.after(selected); //sel_index);

        } else {

            showMessages_new("[{'field':'#select2','code':'101','msg':'Sorry,could not  move! '}]");
        }

    }
}


function filter_chars(obj) {
    var grep = new RegExp("/[^-\.\d]/g");
    if (grep.test(obj.value)) {
        return;
    } else {
        obj.value = obj.value.replace(/[^-\.\d]/g, "");
        $(obj).attr('class', 'invalid');
        $(obj).attr('title', 'Please fill the field correctly (only  digits allowed).');
    }
    //show_filter_mess(obj,1,'');
}


function filter_numbers(obj) {
    obj.value = obj.value.replace(/[^\d]/g, "");
    $(obj).attr('class', 'invalid');
    $(obj).attr('title', 'Please fill the field correctly (only  digits allowed).');
}





/*
 * 匹配数字+空串
 */
function filter_number_s(obj) {

    obj.value = obj.value.replace(/^\d{0,}/g, "");
    $(obj).attr('class', 'invalid');
    $(obj).attr('title', 'Please fill the field correctly (only  digits allowed).');
}
function  show_filter_mess(obj, style_number, msg) {
    // init defaults
    $.jGrowl.defaults.position = 'top-center';
    $.jGrowl.defaults.closeTemplate = 'x';
    $.jGrowl.defaults.closerTemplate = '<div>[ ' + L['hide-all'] + ' ]</div>';
    // init variables
    var params = {
        sticky: false, //不需要用户手动关闭
        theme: 'default'
    };
    if (obj) {
        $(obj).attr('class', 'invalid');
        $(obj).attr('title', msg);
    }
    params['sticky'] = false;
    switch (style_number) {
        case '1':
            params['sticky'] = false;
            params['theme'] = 'jmsg-alert';
            break;
        case '2':
            params['sticky'] = false;
            params['theme'] = 'jmsg-success';
            break;
        case '3':
        case '4':
            params['sticky'] = false;
            params['theme'] = 'jmsg-error';
            break;
        default:
            params['theme'] = 'jmsg-default';
            break;
    }
    //jquery的弹出信息显示 
    $.jGrowl(msg, params);
}







//去掉数组重复元素
jQuery.extend({
    uniqueArray: function(a) {
        var r = [];
        for (var i = 0, l = a.length; i < l; ++i)
            jQuery.inArray(a[i], r) < 0 && r.push(a[i]);
        return r;
    }
})
