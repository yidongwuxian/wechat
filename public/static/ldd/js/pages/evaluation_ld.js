/*
 * 搜索自动查询
 * 
 */
;(function(window,$){
	var defaults = {
		top:NaN,
		height:200,
		myClass:'',//自定义搜索容器样式
		url:'',
        ajaxParms:{},
		data:[],
        needInputChange:true,
        searchBtn:null,
        searchBtnUrl:'',
        needClear:true,
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
			var _id = this.elem.attr('id') || 'main';
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
                    that.getData(keyword,that.opts.url);
                    if (typeof that.opts.ontxtinput == 'function')
                        that.opts.ontxtinput(keyword,that);
                    showCloseIco.call(that, _id);
                    if (typeof that.opts.onnotequal == 'function' && !getEqualItem.call(that)) {//获取搜索框与搜索结果匹配项是否存在
                        that.opts.onnotequal();//没有匹配项事件
                    }
                });
            }/*else{*/
            if(that.opts.searchBtn) {
                that.opts.searchBtn.on('click', function () {
                    if (!that.elem.val()) {
                        that.$layer.html('').hide();
                        hideCloseIco(_id);
                        return false;
                    }
                    that.show();//加载搜索结果容器层
                    var keyword = that.elem.val();
                    that.getData(keyword,that.opts.searchBtnUrl);

                    if (typeof that.opts.onnotequal == 'function' && !getEqualItem.call(that)) {//获取搜索框与搜索结果匹配项是否存在
                        that.opts.onnotequal();//没有匹配项事件
                    }
                });
            }
            /*}*/
            that.$layer.on('click','li',function(){
                that.hide();
                var $this = $(this);
                var txt = $this.text(),
                    id = $this.data('id');
                that.elem.val(txt);
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
        destory: function(){
            /*this.$layer = null;
            this.cpLock = false;*/
        },
		//执行获取查询数据
		getData : function(keyword,url){
			this.ajaxSearch(keyword,url);
		},
		//ajax请求查询结果
		ajaxSearch : function(keyword,url){
			var that = this;
			$.ajax({
				url : url,
				data : $.extend({name : keyword},that.opts.ajaxParms),
				dataType : 'json',
				type : 'post',
        beforeSend: function(){
            that.$layer.html('<div style="height: 40px;line-height: 40px;background-color: #fff;text-align: center;color:#999;">加载中...</div>');
        }
			}).done(function(data){
				var _list = data.extension;
				if(_list && _list instanceof Array){	//数据为数组
					createResultList(that.$layer,_list,that);
          that.opts.onsuccess(that.$layer,that.opts);
				}else{
          that.opts.onnodata(that.$layer,that.opts);
        }
			}).error(function(){
				
			})
		},
        resetAjaxUrl: function(url){
            var that = this;
            that.opts.url = url;
            if (!that.elem.val()) {
                that.$layer.html('').hide();
                var _id = this.elem.attr('id');
                hideCloseIco(_id);
                return false;
            }
            if (that.cpLock) {
                return false;
            }
            that.show();//加载搜索结果容器层
            var keyword = that.elem.val();
            that.getData(keyword,that.opts.url);
            if (typeof that.opts.ontxtinput == 'function')
                that.opts.ontxtinput(keyword,that);
            if (typeof that.opts.onnotequal == 'function' && !getEqualItem.call(that)) {//获取搜索框与搜索结果匹配项是否存在
                that.opts.onnotequal();//没有匹配项事件
            }
        },
        resetAjaxParms: function(parms){
            this.opts.ajaxParms = parms;
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
				that.opts.ontxtinput('',that);
			})
			return false;
		}
		$closeIco.addClass('show');
	}
	//隐藏搜索框关闭
	function hideCloseIco(id){
		$('#'+'auto_clear_'+id).removeClass('show');
	}
	//创建搜索结果的列表
	function createResultList($con,data,that){
		var _html = '<ul>';
		data.forEach(function(item,i){
			_html += '<li data-id="'+item.code+'">'+item.name+'</li>';
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
})(window,jQuery)
;(function(){
	//注册下array.find,因为是es6的
	if (!Array.prototype.find) {
	  Array.prototype.find = function(predicate) {
	    'use strict';
	    if (this == null) {
	      throw new TypeError('Array.prototype.find called on null or undefined');
	    }
	    if (typeof predicate !== 'function') {
	      throw new TypeError('predicate must be a function');
	    }
	    var list = Object(this);
	    var length = list.length >>> 0;
	    var thisArg = arguments[1];
	    var value;
	
	    for (var i = 0; i < length; i++) {
	      value = list[i];
	      if (predicate.call(thisArg, value, i, list)) {
	        return value;
	      }
	    }
	    return undefined;
	  };
	}
	// 默认配置参数项
	var defaults = {
		// 数组，！根据元素ld-index排序！['json/aaa', 'ajax/bbb']
		url: [], 
		urlType: 'get',
		// 根据接口地址返回的数据需要的键名，！根据元素ld-index排序！
		// 比如城市select-option的value，text，对应的city为['code', 'city']
		// 第一项一定为code值（可能名称不一样），第二项为文本值，剩下的是扩展项
		// 例如 [['code', 'city'],['code', 'name', 'area']];
		dataKeys: [],
    //设置默认选中项
    defaultSelected: {},
		//扩展的select选项
		extendSelectItem: {},
		// 联动取不到数据的回调函数
		noDataCallBack: null
	};
	/* 联动的构造函数 */
	function LD(content, opts){
		this.elem = content;								// 当前容器
		this.opts = $.extend(true, {}, defaults, opts);		// 参数项
		this.info = {};										// 从dom中获取到的一些信息集合			
		this.index = 0;										// 当前进行到的索引
		this.indexObj = null;								// 当前进行到的信息（info中）
		this.paramsCache = {};								// 选中或者其他方式得到的每一项的数据缓存
		this.ready();										
	}
	LD.prototype={
		ready: function(){
			// 查询所有包含'ld-key'的表单元素
			var doms = Array.prototype.slice.call(this.elem.find('*[ld-index]'), 0);
			var that = this;
			doms.forEach(function(item){
				var $this = $(item);
				/* 
				 * key为index:当前表单的索引(也是执行联动的顺序)，
				 * dom:jquery元素，
				 * type:当前表单元素的类型（是需要配置到LD.），
				 * params:当前表单需要的参数 
				 * component:当前表单要配置的组件
				 */
				that.info[$this.attr('ld-index')] = {
					dom: $this,
					type: $this.attr('ld-type'),
					params: $this.attr('ld-params'),
					component: $this.attr('ld-component'),
					place: $this.html()
				};
			})
			this.getNext(0);
			// 注册获取当前联动事件
			//e是this.elem,$e是当前触发事件的元素,val是当前获取到的code值，data是传入的列表或者是根据code值得到的object，keys对应opts.dataKeys
			this.elem.on('getValue', function(e, $e, val, data, keys){
				// 将当获取到的元素相关属性缓存
				var $e_index = $e.attr('ld-index');
				that.setParamsCache($e_index, val, data, keys);
				//删除当前触发事件元素后面的元素的数据
				that.clearParamsChche($e_index);
        //此表示代表联动结束，不获取下一步数据
        if($e.attr('ld-end') != undefined){
          return false;
        }
				that.getNext($e_index);
			});
		},
		setParamsCache: function($e_index, val, data, keys){
			var params = {},
				obj = {};
			//如果传入data是数组，则传入的是当前数据集合，将从中筛选出符合当前标示code值的数据
			if(data instanceof Array){
				obj = data.find(function(item){
					return item[keys[0]] == val;
				})
			}else{
				obj = data;
			}
			params['code'] = val;			//code
			params['text'] = obj[keys[1]];  //第二项是文本值text
			// 补充扩展项
			if(keys.length > 2){
				for(var i = 2; i < keys.length; i++){
					params[keys[i]] = obj[keys[i]];
				}
			}
			this.paramsCache[$e_index] = params;
		},
		clearParamsChche: function($e_index){
			for(var k in this.paramsCache){
				var ik = parseInt(k);
				if(ik > $e_index){
					//delete this.paramsCache[k];
          this.paramsCache[k] = {code: -1};
					var _info = this.info[k];
					LD.domType[_info.type].remove.call(this, _info.dom, k);
				}
			}
		},
    setAfterItems: function(index, value){
      for(var k in this.info){
        var ik = parseInt(k);
        if(ik >= index){
          this.paramsCache[k] = {code: value || -1};
        }
      }
    },
		// 加载下一个元素
		getNext: function($e_index){
			this.index = parseInt($e_index) + 1;
			this.indexObj = this.info[this.index];
			var paramsObj = this.formatParams();
			var that = this;
			// 没有url请求地址默认不进行请求（用于一些延缓操作的地方，例如自动搜索）
			if(!that.opts.url[that.index - 1]){
				that.loadDom(null, paramsObj);
				return false;
			}
			$.ajax({
				type: that.opts.urlType,
				url: that.opts.url[that.index - 1],
				data: paramsObj,
				dataType: 'JSON',
				async: true,
				success: function(data){
					if(data.result == 1){
						that.loadDom(data.extension, paramsObj);
						if(typeof that.opts.loadDataCallBack == 'function'){
							that.opts.loadDataCallBack(that.index);
            }
					}
					//有错误的情况，或者没有数据触发回调函数
					else{
						if(typeof that.opts.noDataCallBack == 'function'){
							that.opts.noDataCallBack(that.index);
              that.setAfterItems(that.index, '999999');
            }
					}
				}
			})
		},
		//加载创建执行到此步骤的dom元素数据
		loadDom: function(data, paramsObj){	
			var $dom = this.indexObj.dom,
					component = $dom.attr('ld-component'),
					type = $dom.attr('ld-type');
			LD.domType[type].invoke.call(this, this.elem, $dom, data, this.opts.dataKeys[this.index - 1], paramsObj);
		},
		// 格式化元素加载时所需参数
		formatParams: function(){
			if(!this.indexObj.params)
				return {};
			var that = this,
				arr = this.indexObj.params.split(','),
				paramsObj = {};
			arr.forEach(function(item){
				var sp = item.split(':');
				var sp_val = sp[1].split('.');
				var paramsIndex = that.paramsCache[sp_val[0]],
					value = null;
				//代表查询参数为当前表单元素文本信息text,
				//如果有ext-xxx这种格式的，代表扩展属性
				//否则为value
				if(sp_val[1] && sp_val[1]=='t'){
					value = paramsIndex.text;
				}else if(sp_val[1] && ~sp_val[1].indexOf('ext-')){
					var k = sp_val[1].split('ext-')[1];
					value = paramsIndex[k];
				}else{
					value = paramsIndex.code;
				}
				paramsObj[sp[0]] = value;
			});
			return paramsObj;
		}
	}
	/*静态方法*/
	// 根据元素不同类型操作相应元素渲染移除方法
	LD.domType = {};
	// 加入一个新的，或者重置一个渲染dom的方法
	LD.setLoadDom = function(obj){
		$.extend(true, LD.domType, obj);
	};
	window.LD = LD;
})();

//设置
LD.setLoadDom({
	'select': {
		invoke: function(elem, $e, data, keys){
			var html = '',
					$e_index = $e.attr('ld-index');
			var extItems = this.opts.extendSelectItem[$e_index];
			if(extItems && extItems[0]){
				data.unshift(extItems[0]);
			}
			if(extItems && extItems[1]){
				data.push(extItems[1]);
			}
			data.forEach(function(item){
				html += '<option value="'+ item[keys[0]] +'">'+ item[keys[1]] +'</option>';
			});
			$e.html(html);

      //设置默认选中（如果存在）
      var dval = this.opts.defaultSelected[$e_index];
      if(dval && dval!=-1){
        $e.val(dval)
      }
      //判断默认值是否触发默认事件
			var $e_val = $e.val();
			if($e_val != -1){
				elem.trigger('getValue', [$e, $e_val, data, keys]);
			}
			$e.unbind('change');
			var that = this;
			$e.on('change', function(){
				//判断是请选择或者是其他，都不是才触发事件
				var code = $e.val();
				if(code == -1){
					that.clearParamsChche($e_index);
				}else if(code == 999999){
          if(typeof that.opts.noDataCallBack == 'function')
					  that.opts.noDataCallBack($e_index, '', true);
          that.setAfterItems(that.index, '999999');
					//that.paramsCache[$e_index] = {code: 999999, text: 999999};
				}else{
					elem.trigger('getValue', [$e, code, data, keys]);
				}
			})
		},
		remove: function($e, index){
			if(this.info[index])
				$e.html(this.info[index].place)
			else
				$e.html('')
		}
	},
	'text-area': {
		invoke: function(elem, $e, data, keys){
			if(data && data.fArea && parseFloat(data.fArea) > 0){
				this.paramsCache[$e.attr('ld-index')] = {code: data.fArea, text: data.fArea};
				$e.val(data.fArea);
			}else{
				if(typeof this.opts.noDataCallBack == 'function')
					this.opts.noDataCallBack(this.index, '未查询到面积，请手动输入');
				this.paramsCache[$e.attr('ld-index')] = {code: '', text: ''};
			}
		},
		remove: function($e){
			$e.val('');
		}
	}
});