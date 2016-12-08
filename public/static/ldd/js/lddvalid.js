(function($){

	$.fn.extend({
		valid:function(money) {
			var obj = {
				flag : true,
				msg : ''
			}
			var self = $(this);
			var aInp = self.find('.valid');
			$.each(aInp,function(index){
				if(!obj.flag||obj.flag == 'required' ){
					return;
				}
				var type = $(aInp[index]).attr('data-class');
				var val =  $(aInp[index]).val();
				switch (type)
				{

					case 'province':
						var result = checkisZero(val);
						checkForm(type,obj,result);
						break;
					case 'city':
						var result = checkisZero(val);
						checkForm(type,obj,result);
						break;
					case 'bankcard':
						var result = checkBankcard(val);
						checkForm(type,obj,result);
						break;
                    case 'forward':
						var result = checkisZero(val);
						checkForm(type,obj,result);
						break;
                    case 'buildArea':
                        var result = checkisNull(val);
                        checkForm(type,obj,result);
                        break;
                    case 'floorToal':
                        var result = checkisNull(val);
                        checkForm(type,obj,result);
                        break;
                    case 'years':
                        var result = checkisNull(val);
                        checkForm(type,obj,result);
                        break;
                    case 'infloor':
                        var result = checkisNull(val);
                        checkForm(type,obj,result);
                        break;
                    case 'lift':
                        var result = checkisNull(val);
                        checkForm(type,obj,result);
                        break;
                    case 'mortgage':
                        var result = checkisZero(val);
                        checkForm(type,obj,result);
                        break;
                    case 'one_residual':
                        var result = checkisNull(val);
                        checkForm(type,obj,result);
                        break;
                    case 'is_five':
                        var result = checkisZero(val);
                        checkForm(type,obj,result);
                        break;
                    case 'sel_house_type':
                        var result = checkisZero(val);
                        checkForm(type,obj,result);
                        break;
					case 'address':
						var result = checkAddress(val);
						checkForm(type,obj,result);
						break;
					case 'zipcode':
						var result = checkZipcode(val);
						checkForm(type,obj,result);
						break;
					case 'oldpassword':
						var result = checkPassword(val);
						checkForm(type,obj,result);
						break;
					case 'newpassword':
						var result = checkPassword(val);
						checkForm(type,obj,result);
						break;
					case 'repassword':
						var result = checkSurePassword(val);
						checkForm(type,obj,result);
						break;
					case 'mobile':
						var result = checkReg(val,/^1\d{10}$/);
						checkForm(type,obj,result);
						break;
					case 'validcode':
						var result = checkCode(val);
						checkForm(type,obj,result);
						break;
					case 'password':
						var result = checkPassword(val);
						checkForm(type,obj,result);
						break;
					case 'surepassword':
						var result = checkSurePassword(val);
						checkForm(type,obj,result);
						break;
					case 'name':
						var result = checkNoBlank(val);
						checkForm(type,obj,result);
						break;
					case 'applyName':
						var result = checkNoBlank(val);
						checkForm(type,obj,result);
						break;
					case 'idcard':
						var result = checkReg(val,/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/);
						checkForm(type,obj,result);
						break;
					case 'email':
						var result = checkReg(val,/^(\w)+(\.\w+)*@(\w)+((\.\w{2,3}){1,3})$/);
						checkForm(type,obj,result);
						break;
					case 'appmoney':
						var result = checkMoney(val,money);
						checkFormMoney(type,obj,result,money);
						break;
				}
			});
			return obj;
		}
	})
})(jQuery);

var tips = {
	mobileR: "手机号码不能为空",
	mobile: "请输入有效的11位手机号码",
	passwordR: "请输入密码",
	password: "密码不能小于6位",
	validcodeR:"请输入验证码",
	validcode:"验证码输入错误",
	surepasswordR:"请输入确认密码",
	surepassword:"两次输入的密码不一致",
	oldpasswordR:'请输入原密码',
	oldpassword:'原密码不能小于6位',
	newpasswordR:'请输入新密码',
	newpassword:'新密码不能小于6位',
	repasswordR:'请输入确认密码',
	repassword:'两次输入的密码不一致',
	provinceR:'请选择省份',
	cityR:'请选择市',
	addressR:'请填写详细地址',
	address:'详细地址不能少于10个字',
	zipcodeR:'请输入邮政编码',
	zipcode:'邮政编码格式不正确',
	bankcardR:'请输入卡号',
	bankcard:'输入的卡号格式不正确',
	nameR:'请输入姓名',
	idcardR:'请输入身份证号',
	idcard:'身份证格式不正确',
	emailR:'请输入邮箱',
	email:'邮箱格式不正确',
    forwardR: '请选择朝向',
    forward: '朝向选择有误',
    buildAreaR: '请输入建筑面积',
    buildArea: '建筑面积不正确',
    floorToalR: '请输入总楼层',
    yearsR: '请选择年份',
    mortgageR: '请选择抵押类型',
    one_residualR: '请输入一押剩余本金',
    is_fiveR: '请选择房产交易是否满五年',
    sel_house_typeR: '请选择一种房产类型',
	applyNameR: '请输入申请人姓名'
}

function checkForm(type,obj,result){
	obj.flag = result;
	obj.msg = tips[type];
	if(result){
		obj.msg = '';
		if(result == 'required'){
			obj.flag = false;
			obj.msg = tips[type+'R'];
		}
	};
}

/*需要根据正则判断的*/
function checkReg(str,reg){
	var val = $.trim(str);
	var re = reg;
	if(val == ''){
		return 'required';
	}
    if (re.test(val)) {
        return true;
    } else {
        return false;
    }
}
function checkPassword(str){
	var val = $.trim(str);
	if(val == ''){
		return 'required';
	}

	if(val.length<6){
		return false;
	}
	return true;
}
function checkCode(str){
	var val = $.trim(str);
	if(val == ''){
		return 'required';
	}
	if(val.length!=6){//根据校验码的位数来确定
		return false;
	}
	return true;
}
function checkSurePassword(str){
	var val = $.trim(str);
	var psd = $('input[data-class="password"]');
	var password='';
	if(psd.length>0){
		password = psd.val();
	}else{
		password = $('input[data-class="newpassword"]').val();
	}
	if(val == ''){
		return 'required';
	}
	if(val!=password){
		return false;
	}
	return true;
}
function checkAddress(str){
	var val = $.trim(str);
	if(val == ''){
		return 'required';
	}
	if(val.length<10){
		return false;
	}
	return true;
}
function checkZipcode(str){
	var val = $.trim(str);
	if(val == ''){
		return 'required';
	}
	if(val.length!=6){//根据校验码的位数来确定
		return false;
	}
	return true;
}
function checkNoBlank(str){
	var val = $.trim(str);
	if(val == ''){
		return 'required';
	}
	return true;
}
function checkisZero(str){
	var val = $.trim(str);
	if(val == '0'){
		return 'required';
	}
	return true;
}
//面积
function checkisNull(str){
    var val = $.trim(str);
	if(val == ''){
		return 'required';
	}
	return true;
}

function checkBankcard(str){
	var val = $.trim(str);
	var re = /^(\d{16}|\d{19})$/;
	if(val == ''){
		return 'required';
	}
    if (re.test(val.replace(/\s/g,""))) {
        return true;
    } else {
        return false;
    }
}
