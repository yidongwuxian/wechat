/*
 * 搜索自动查询
 * 最后更新2016.03.24
 */
;(function(window,$){
	var defaults = {
		top:NaN,
		height:200,
		myClass:'',//自定义搜索容器样式
		searchType:'ajax',//默认ajax获取数据，本地获取是local,前者需要传入url，后者需要穿入data
		url:'',
        ajaxParms:{},
		data:[],
        needInputChange:true,
        searchBtn:null,
        needClear:true,
		filterCallBack:null,//本地搜索条件过滤，参数len,item,keyword,代表符合当前过滤条件的数组长度，每项的内容，要进行对比的搜索内容，返回true，false
		onselect:null,//当选中加入到搜索框
		oncloselayer:null,//当关闭搜索容器
		ontxtinput:null,//当输入的时候，并没有选择时的事件
		onnotequal:null,//当输入的值和搜索结果中的值没有匹配的时候事件(此处用于当焦点离开搜索框的时候)
        onnodata:null//当没有数据的时候
	}
	
	function AutoComplete(e,options){
		this.elem = $(e);
		this.opts = $.extend(true,{},defaults,options);
		//坐标，宽度
		this.top = !isNaN(this.opts.top)?this.opts.top: this.elem.outerHeight();//不准确，应该手动输入
		this.height = this.opts.height;
		//搜索结果容器
		this.$layer = this.createLayer();
        //标记输中文拼音没有结束不执行搜索
        this.cpLock = false;
		//初始化
		this.init();
	}
	AutoComplete.prototype = {
		setData : function(data){
			this.opts.data = data;
			this.hide();
		},
		createLayer : function(){
			var _id = this.elem.attr('id');
			return $('<div id="auto_layer_'+ _id +'" class="auto_layer '+this.opts.myClass+'" style="display:none;top:'+this.top+'px;height:'+this.height+'px"></div>')
				   .appendTo(this.elem.parent().addClass('auto_wrap'));//+'px;width:'+this.width+left:'+this.left+'px;
		},
		init : function(){
			var that = this;
			var _id = this.elem.attr('id');
            that.elem.on('compositionstart',function(){
                that.cpLock = true;
            });
            that.elem.on('compositionend',function(){
                that.cpLock = false;
            });
            if(that.opts.needInputChange) {
                that.elem.on('input propertychange', function () {
                    if (!$(this).val()) {
                        that.$layer.html('').hide();
                        hideCloseIco(_id);
                        return false;
                    }
                    if (that.cpLock) {
                        return false;
                    }
                    that.show();//加载搜索结果容器层
                    var keyword = $(this).val();
                    that.getData(keyword);
                    if (typeof that.opts.ontxtinput == 'function')
                        that.opts.ontxtinput(keyword);
                    showCloseIco.call(that, _id);
                    if (typeof that.opts.onnotequal == 'function' && !getEqualItem.call(that)) {//获取搜索框与搜索结果匹配项是否存在
                        that.opts.onnotequal();//没有匹配项事件
                    }
                });
                that.elem.on('blur',function(e){
                    setTimeout(function(){
                        hideCloseIco(_id);
                        that.hide();
                    })
                });

            }else{
                if(!that.opts.searchBtn) return false;
                that.opts.searchBtn.on('click',function(){
                    if (!that.elem.val()) {
                        that.$layer.html('').hide();
                        hideCloseIco(_id);
                        return false;
                    }console.log(11)
                    that.show();//加载搜索结果容器层
                    var keyword = that.elem.val();
                    that.getData(keyword);

                    if (typeof that.opts.onnotequal == 'function' && !getEqualItem.call(that)) {//获取搜索框与搜索结果匹配项是否存在
                        that.opts.onnotequal();//没有匹配项事件
                    }
                });
            }
            that.$layer.on('click','li',function(){
                that.hide();
                var $this = $(this);
                var txt = $this.text(),
                    id = $this.data('id');
                //that.elem.val(txt);
                if(typeof that.opts.onselect=='function')
                    that.opts.onselect(id,txt);
            })

			/*that.$layer.on('click','.auto_btm_btn',function(){
                that.opts.onBtmBtnClick();
            })*/
		},
		show : function(){
			this.$layer.show();
		},
		hide : function(){
			this.$layer.hide();
		},
		//执行获取查询数据
		getData : function(keyword){
			if(this.opts.searchType=='ajax'){
				this.ajaxSearch(keyword);
			}else{
				this.localSearch(this.opts.data,keyword);
			}
		},
		localFilter : function(len,item,keyword){
			if(typeof this.opts.filterCallBack=='function')
				return this.opts.filterCallBack(len,item,keyword);
			//默认搜索过滤条件
			return new RegExp(keyword).test(item)&&len<5;
		},
		//本地过滤获得查询结果
		localSearch : function(list,keyword){
			var that = this;
			var result = [];
			if(!keyword){
				result = getRangeList(list,0);
				that.hide();
			}else{
				list.forEach(function(item,i){
					if(that.localFilter(result.length,item.name,keyword))
						result.push(item);
				})
				if(result.length==0){
					that.hide();
				}
			}
			createResultList(that.$layer,result,that);
		},
		//ajax请求查询结果
		ajaxSearch : function(keyword){
			var that = this;
			$.ajax({
				url : that.opts.url,
				data : $.extend({village : keyword},that.opts.ajaxParms),
				dataType : 'json',
				type : 'post'
			}).done(function(data){
				var _list = data.data;
				if(_list && _list instanceof Array){	//数据为数组
					createResultList(that.$layer,_list,that);
				}else{
                    that.$layer.html('');
                    that.opts.onnodata();
                }
			}).error(function(){
				
			})
		}
	}
	//获取搜索框的值和搜索结果匹配项，没有返回空对象
	function getEqualItem(){
		var _val = this.elem.val();
		var result = false;
		this.$layer.find('li').each(function(){
			if(_val == $(this).text()){
				result = true;
				return false;
			}
		})
		return result;
	}
	//创建搜索框关闭
	function showCloseIco(id){
        if(!this.opts.needClear)return false;
		var that = this;
			clearId = 'auto_clear_'+id,
			$closeIco = $('#'+clearId);
		if($closeIco.length==0){	//如果不存在清除按钮,创建
			$closeIco = $('<span id="'+clearId+'" class="mui-icon mui-icon-clear auto_input_clear show"></span>');
			that.elem.after($closeIco);
			$closeIco.on('click',function(){
				that.elem.val('');
				that.hide();
				hideCloseIco(id);
				that.opts.ontxtinput('');
			})
			return false;
		}
		$closeIco.addClass('show');
	}
	//隐藏搜索框关闭
	function hideCloseIco(id){
		$('#'+'auto_clear_'+id).removeClass('show');
	}
	//获取一个数组前几项
	function getRangeList(list,limit){
		var result = [];
		for(var i=0,j=list.length;i<j;i++){
			if(limit<i+1)
				break;
			result.push(list[i]);
		}
		return result;
	}
	//创建搜索结果的列表
	function createResultList($con,data,that){
		var _html = '<ul>';
		data.forEach(function(item,i){
			_html += '<li data-id="'+item.id+'">'+item.name+'</li>';
		})
		_html += '</ul>';//<span class="mui-icon mui-icon-search"></span><div class="auto_btm_btn"><a href="javascript:;">'+that.opts.btmBtnText+'</a></div>
		$con.html(_html);
	}
	//创建插件
	$.fn['autoComplete'] = function (options) {
        return this.each(function () {
            $.data(this, 'plugin_autoComplete', new AutoComplete(this, options));
        });
    };
})(window,jQuery);
