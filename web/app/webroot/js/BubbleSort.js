function MySort(table_id,column_index,order){
	var abc = ['a','b','c','d','e','f','g','h','i','j','k',
	           'l','m','n','o','p','q','r','s','t','u','v',
	           'w','x','y','z'];
	
	var getWordIndex = function(word){
		var l = abc.length;
		for (var i = 0;i<l;i++) {
			if (abc[i] == word.toLowerCase()) {
				return i;
			}
		}
		return -1;
	};
	
	/*
	 * 交换节点
	 */
	var swapNode = function(node1,node2){

		var parent = node1.parentNode;//父节点
	
		var t1 = node1.nextSibling;//两节点的相对位置
	
		var t2 = node2.nextSibling;
	
		if(t1) parent.insertBefore(node2,t1);
	
		else parent.appendChild(node2);
	
		if(t2) parent.insertBefore(node1,t2);
	
		else parent.appendChild(node1);

	};
	
	var tab = document.getElementById(table_id);
	
	var rows_len = tab.rows.length;
	
	for (var i = 0; i<rows_len; i++) {
		var c1 = tab.rows[i].cells[column_index].innerHTML;
		
		var fwi = getWordIndex(c1.substring(0,1));//First word
		
		for (var j = i; j<rows_len; j++) {
			var c2 = tab.rows[j].cells[column_index].innerHTML;
			
			var fwj = getWordIndex(c2.substring(0,1));//First word
			
			
			if (order == 'asc') {
				if (fwi != -1) {
					if (fwi > fwj) {
						swapNode(tab.rows[i],tab.rows[j]);
					}
				} else {
					if (parseInt(c1) > parseInt(c2)) {
						swapNode(tab.rows[i],tab.rows[j]);
					}
				}
			} else {
				if (fwi != -1) {
					if (fwi < fwj) {
						swapNode(tab.rows[j],tab.rows[i]);
					}
				} else {
					if (parseInt(c1) < parseInt(c2)) {
						swapNode(tab.rows[j],tab.rows[i]);
					}
				}
			} 
		}
	}
}