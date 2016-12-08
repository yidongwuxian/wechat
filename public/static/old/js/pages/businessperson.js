(function(){
	//搜索，加载数据
	var localData = [];
	loadData();
	mui('.dir_nav').on('tap','#searchbtn',function(){
		var keyword = $('#txt_search').val();
		loadData(keyword);
	})
	//加载数据
	function loadData(keyword){
		if(!localData[0]){//如果不存在数据
			//$.ajax({url:'/asserts/ldd/json/persons.json',dataType:'json'})
            $.ajax({url:'/ldd/agent/getlist',dataType:'json'})
				.done(function(data){
					localData = data.list.split('|');
					getSerchList.call(localData);//第一次加载全部
				})
				.error(function(){
					mui.alert('数据加载错误');
				})
			return false;
		}
		getSerchList.call(localData,keyword,searchFilter);//搜索
	}
	
	//定义搜索过滤器
	function searchFilter(index,item,key){
		return new RegExp(key).test(item.substr(item.indexOf(','),item.length-10));
	}
	
	//获取搜索列表
	//callback是搜索过滤器，参数为当前集合索引，要比较的关键字，集合中当前项，返回true代表将此项加入到搜索结果中
	function getSerchList(keyword,callback){
		if(!keyword){
			createHtml(this);
			return false;
		}
		var result = [];
		this.forEach(function(item,i){
			if(callback(i,item,keyword))
				result.push(item);
		})
		createHtml(result);
	}
	//创建列表
	function createHtml(list){
		var _html = '';
		list.forEach(function(item,i){
			var _item = item.split(',');
			_html += createItem(i+1,_item[0],_item[1],_item[2],_item[3],_item[4]);
		})
		$('#personsCon').html(_html);
	}
	//模板，返回一条记录html
	function createItem(index,id,name,no,tel,date){
		var _templete = '<tr>\
						<td>'+no+'</td>\
						<td>'+name+'</td>\
						<td>'+tel+'</td>\
						<td><button class="mui-btn mui-btn-success detail" data-id="'+id+'">编辑</button></td>\
					</tr>';
	     return  _templete;
	}
})();
