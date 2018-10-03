/**
 * 选中辩解码
 * @return
 */
function  seleted_codes(){
	
}
//************************************************addcodes
var MainSel  = null;
var SlaveSel = null;
var Item_org = new Array();

//添加数据
function DoAdd(){
	var this_sel = null;
	for(var i=0;i<MainSel.options.length;i++){
		this_sel = MainSel.options[i];
		if(this_sel.selected){
			SlaveSel.appendChild(this_sel);
			i--;
		}
	}
	//sort_Main(SlaveSel);
}function DoDel(){
	var this_sel = null;
	for(var i=0;i<SlaveSel.options.length;i++){
		this_sel = SlaveSel.options[i];
		if(this_sel.selected){
			MainSel.appendChild(this_sel);
			i--;
		}
	}
	//sort_Main(MainSel);
}function sort_Main(the_Sel){
	var this_sel = null;
	for(var i=0;i<Item_org.length;i++){
		for(var j=0;j<the_Sel.options.length;j++){
			this_sel = the_Sel.options[j];
			if(this_sel.value==Item_org[i][0] && this_sel.text==Item_org[i][1]){
				the_Sel.appendChild(this_sel);
			}
		}
	}
}
function moveOption(e,t){
var selected=	$("#columns").find("option:selected");
if(selected.size()>1){
	jGrowl_to_notyfy("Sorry! Choose only one.", {theme: 'jmsg-error'});
	return;
}
var prev=$("#columns").find("option:selected").prev();
var  next=$("#columns").find("option:selected").next();

var sel_index=$("#columns").get(0).selectedIndex;
var maxIndex=$("#columns option").size() - 1;  
	if(t=='up'){
		
		//向上移动
		if(sel_index>0){
			prev.before(selected);
		}else{
			jGrowl_to_notyfy("Sorry! Could not move.", {theme: 'jmsg-error'});
		}
	}else{
		//向下移动
		if(sel_index<maxIndex){
			next.after(selected);//sel_index);
			
		}else{

			jGrowl_to_notyfy("Sorry! Could not move.", {theme: 'jmsg-error'});
		}
	
	}
}
$(function() {
    MainSel  = document.getElementById('columns_select');
    SlaveSel =  document.getElementById ('columns');
    MainSel.ondblclick=DoAdd;
    SlaveSel.ondblclick=DoDel;
    var this_sel = null;
    for(var i=0;i<MainSel.options.length;i++){
            this_sel = MainSel.options[i];
            Item_org.push(new Array(this_sel.value,this_sel.text));
    }
});

