(function(){
	mui.init();
	mui('#segmentedControl').on('tap','.mui-control-item',function(){
		location.href = $(this).attr('href');
	})
	
	//选择派单员
	//参数分别代表：主办经理人员集合，协办经理人员集合
	//集合格式为[{text:'张三',value:'1001'},{text:'jack',value:'1002'}]
	function getPersonsData(plist1,plist2){
		var arr = [];
		plist1.forEach(function(item){
			var cloned = [].slice.call(plist2);
			cloned.forEach(function(obj,i){
				if(obj.value == item.value)
					cloned.splice(i,1);
			})
			item['children'] = cloned;
			arr.push(item);
		});
		return arr;
	}
	var personPicker = new mui.PopPicker({
		layer: 2
	});
	//测试数据
	var pl1 = [{text:'张三',value:'1001'},{text:'jack',value:'1002'},{text:'李四',value:'1003'}];
	var pl2 = [{text:'张三',value:'1001'},{text:'jack',value:'1002'},{text:'李四',value:'1003'}];
	
	personPicker.setData(getPersonsData(pl1,pl2));
	document.getElementById('pdbtn').addEventListener('tap',function(){
		if($('.ass_ck:checked').length==0){//如果没有选中任何元素
			mui.alert('没有选择任何业务');
			return false;
		}
		
		personPicker.show(function(items) {
			//处理数据
			//items[0].value
			//items[0].text
			//items[1].value
			//items[1].text
		});
	});
})();
