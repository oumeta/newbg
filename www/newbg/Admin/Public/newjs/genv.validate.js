
/*验证*/
var GenvCheck={}
var GenvValidateConfig={
	error_class: 'GenvCheck-valid',
   	prefix:":",
	separator: ';'

}
GenvCheck.parse_class_name = function(class_name) {
    if (class_name.indexOf(GenvValidateConfig.prefix) == 0) {
        var v_params = class_name.substr(GenvValidateConfig.prefix.length).split(GenvValidateConfig.separator)
        for (var key in v_params) {
            if (v_params[key] == "") {
                v_params[key] = undefined
            }
        }
        return v_params;
    } else {
        return [];
    }
}
GenvCheck.success=function(){
return true;
	 
}
$(function(){
	var xOffset = -20; // x distance from mouse
    var yOffset = 20; // y distance from mouse  
	
	$("[reg],[url]:not([reg]),[tip]").hover(
		function(e) {
			if($(this).attr('tip') != undefined){
				var top = (e.pageY + yOffset);
				var left = (e.pageX + xOffset);
				$('body').append( '<p id="vtip"><img id="vtipArrow" src="vtip_arrow.png"/>' + $(this).attr('tip') + '</p>' );
				$('p#vtip').css("top", top+"px").css("left", left+"px");
				$('p#vtip').bgiframe();
			}
		},
		function() {
			if($(this).attr('tip') != undefined){
				$("p#vtip").remove();
			}
		}
	).mousemove(
		function(e) {
			if($(this).attr('tip') != undefined){
				var top = (e.pageY + yOffset);
				var left = (e.pageX + xOffset);
				$("p#vtip").css("top", top+"px").css("left", left+"px");
			}
		}
	).blur(function(){
		if($(this).attr("url") != undefined){
			ajax_validate($(this));
		}else if($(this).attr("reg") != undefined){
			validate($(this));
		}
	});
	
	$("form").submit(function(){
		var isSubmit = true;
		 
		$(this).find("[reg],[url]:not([reg])").each(function(){
			// alert($(this).attr("class") == undefined)
			if($(this).attr("class") == undefined){
				if(!ajax_validate($(this))){
					isSubmit = false;
				}
			}else{

				 
				if(!validate($(this))){
					isSubmit = false;
				}
			}
		});
		 
		if(typeof(isExtendsValidate) != "undefined"){
   			if(isSubmit && isExtendsValidate){
				return extendsValidate();
			}
   		}
		if(isSubmit){				 
			return  GenvCheck.success();			
		}		
		return isSubmit;
	});
	
});
function ggcheck(a,v){
    
   var b
   var c=GenvCheck.validators_types[a[0]]; 
   if(a.length==2){
       
	   	 
		if(c.validationFunction.call(this,a[1],v)){
		  return true;
		}else{			 
			return c.error_message.call(this,a[1],v);		
		}		 
   }else{		  
		if(c.validationFunction.call(this,v)){
		  return true;
		}else{			 
			return c.error_message.call(this,v);		
		}	
   }

}
function validate(obj){
	//alert(obj.attr("class"))
	//var reg = new RegExp(obj.attr("reg"));
	var class_names=obj.attr("reg").split(' ');	
	var objValue = obj.attr("value");
    var bbb=true;
	$.each(class_names,
		function() {
		 
			var parameters = GenvCheck.parse_class_name(this);
			 
			 try{
			    a= ggcheck(parameters, objValue);
			    
			if(a!=true){
				 
				 change_error_style(obj,"add");				 
				 change_tip(obj,null,"remove");
				 bbb=false;
				 return false;
			}else{
 
				 
				if(obj.attr("url") == undefined){
					change_error_style(obj,"remove");
					change_tip(obj,null,"remove");
					bbb=true;
					return true;
				}else{
					return ajax_validate(obj);
				}
			}
			 }catch(e){
			 
				//alert(e)
			 }		 
	});  
	return bbb;
	
	
}

function ajax_validate(obj){
	
	var url_str = obj.attr("url");
	if(url_str.indexOf("?") != -1){
		url_str = url_str+"&"+obj.attr("name")+"="+obj.attr("value");
	}else{
		url_str = url_str+"?"+obj.attr("name")+"="+obj.attr("value");
	}
	var feed_back = $.ajax({url: url_str,cache: false,async: false}).responseText;
	feed_back = feed_back.replace(/(^\s*)|(\s*$)/g, "");
	if(feed_back == 'success'){
		change_error_style(obj,"remove");
		change_tip(obj,null,"remove");
		return true;
	}else{
		change_error_style(obj,"add");
		change_tip(obj,feed_back,"add");
		return false;
	}
}

function change_tip(obj,msg,action_type){
	
	if(obj.attr("tip") == undefined){//初始化判断TIP是否为空
		obj.attr("is_tip_null","yes");
	}
	if(action_type == "add"){
		if(obj.attr("is_tip_null") == "yes"){
			obj.attr("tip",msg);
		}else{
			if(msg != null){
				if(obj.attr("tip_bak") == undefined){
					obj.attr("tip_bak",obj.attr("tip"));
				}
				obj.attr("tip",msg);
			}
		}
	}else{
		if(obj.attr("is_tip_null") == "yes"){
			obj.removeAttr("tip");
			obj.removeAttr("tip_bak");
		}else{
			obj.attr("tip",obj.attr("tip_bak"));
			obj.removeAttr("tip_bak");
		}
	}
}

function change_error_style(obj,action_type){
	if(action_type == "add"){		 
		obj.addClass("input_validation-failed");
	}else{
		obj.removeClass("input_validation-failed");
	}
}
GenvCheck.validators_types = {};
GenvCheck.Type = function(className, validationFunction, error_message, message, init) {
  this.initialize(className, validationFunction, error_message, message, init);
};
GenvCheck.Type.prototype = {
  initialize: function(className, validationFunction, error_message, message, init) {
    this.className = className;
    this.message = message;
	 //alert(validationFunction);
    this.error_message = error_message;
    this.validationFunction = validationFunction;
    this.init = init;
  },
  ok:function(){
  
  },
  test: function(value) {
    return this.validationFunction.call(this, value);
  },
  validMessage: function() {
    return this.message;
  },
  invalidMessage: function() {
    return this.error_message;
  },
  toString: function() {
    return "className:" + this.className + " message:" + this.message + " error_message:" + this.error_message
  },
  init: function(parameter) {
    if (this.init) {
      this.init(parameter);
    }
  }
};

GenvCheck.setupValidatorTypes = function() {

  GenvCheck.addValidatorType('empty', function(v) {
    return  ((v == null) || (v.length == 0));
 });

}


GenvCheck.addValidatorType = function(className, validationFunction, error_message, message, init) {
 
    GenvCheck.validators_types[className] = new GenvCheck.Type(className, validationFunction, error_message, message, init);


};

GenvCheck.addValidatorTypes = function(validators_args) {
    var self = this;
    $.each(validators_args,
            function() {
		 
                GenvCheck.addValidatorType.apply(self, this);
            }
      )
};
var GenvValidateTypes=[
	//匹配大小写的等值
    ['equal', function(v, p) {
      return v == p;
    }, function (_v, p) {
       return '输入的值必须与<span class="' + GenvValidateConfig.error_class + '">' + p + '相符\(区分大小写\)</span>.'
    }],
    //不匹配大小写的等值
    ['equal_ignore_case', function(v, p) {
      return v.toLowerCase() == p.toLowerCase();
    }, function (_v, p) {
      return '输入的值必须与<span class="' + GenvValidateConfig.error_class + '">' + p + '相符\(不区分大小写\)</span>.'
    }],
    //是否为空
    ['required', function(v) {
      return !GenvCheck.validators_types['empty'].test(v);
    }, '此项不可为空!'],
    //强制选中 
    ['accept', function(v, _p, e) {
      return e.element.checked;
    }, '必须接受!'],
    //
    ['integer', function(v) {
      if (GenvCheck.validators_types['empty'].test(v)) return true;
      var f = parseFloat(v);
      return (!isNaN(f) && f.toString() == v && Math.round(f) == f);
    }, '请输入一个正确的整数值.'],
    //数字
    ['number', function(v) {
      return GenvCheck.validators_types['empty'].test(v) || (!isNaN(v) && !/^\s+$/.test(v));
    }, '请输入一个正确的数字.'],
    //
    ['digits', function(v) {
      return GenvCheck.validators_types['empty'].test(v) || !/[^\d]/.test(v);
    }, '请输入一个非负整数,含0.'],
    //只能输入英文字母
    ['alpha', function (v) {
      return GenvCheck.validators_types['empty'].test(v) || /^[a-zA-Z\u00C0-\u00FF\u0100-\u017E\u0391-\u03D6]+$/.test(v)   //% C0 - FF (? - ?); 100 - 17E (? - ?); 391 - 3D6 (? - ?)
    }, '请输入英文字母.'],
    //仅限ASCII编码模式下输入英文字母
    ['asciialpha', function (v) {
      return GenvCheck.validators_types['empty'].test(v) || /^[a-zA-Z]+$/.test(v)   //% C0 - FF (? - ?); 100 - 17E (? - ?); 391 - 3D6 (? - ?)
    }, '请在ASCII下输入英文字母.'],
	//英文字母或正数
    ['alphanum', function(v) {
      return GenvCheck.validators_types['empty'].test(v) || !/\W/.test(v)
    }, '请输入英文字母或正数.'],
	//邮箱验证
    ['email', function (v) {
      return (GenvCheck.validators_types['empty'].test(v) || /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(v))
    }, '邮箱格式不正确,请检查.正确格式例如mrthink@gmail.com'],
    //网址
    ['url', function (v) {
      return GenvCheck.validators_types['empty'].test(v) || /^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i.test(v)
    }, '请输入正确的网址,比如:http://www.mrthink.net'],
    //日期格式
    ['date_au', function(v) {
      if (GenvCheck.validators_types['empty'].test(v)) return true;
      var regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
      if (!regex.test(v)) return false;
      var d = new Date(v.replace(regex, '$2/$1/$3'));
      return ( parseInt(RegExp.$2, 10) == (1 + d.getMonth()) ) && (parseInt(RegExp.$1, 10) == d.getDate()) && (parseInt(RegExp.$3, 10) == d.getFullYear() );
    }, '请输入正确的日期格式,比如:28/05/2010.'],
    //输入固定长度字符串
    ['length',
      function (v, p) {
        if (p === undefined) {
          return true
        } else {
          return v.length == parseInt(p)
        }
        ;
      },
      function (_v, p) {
        return '输入字符长度等于<span class="' + GenvValidateConfig.error_class + '">' + p + '</span>个.'
      }
    ],
    //
    ['min_length',
      function (v, p) {
        if (p === undefined) {
          return true
        } else {
          return v.length >= parseInt(p)
        }
        ;
      },
      function (_v, p) {
        return '输入字符长度不低于<span class="' + GenvValidateConfig.error_class + '">' + p + '</span>个.'
      }
    ],
    ['max_length',
      function (v, p) {
        if (p === undefined) {
          return true
        } else {
          return v.length <= parseInt(p)
        }
        ;
      },
      function (_v, p) {
        return '输入字符长度不大于<span class="' + GenvValidateConfig.error_class + '">' + p + '</span>个.'
      }
    ],
	//判断密码是相同
    ['same_as',
      function (v, p) {
        if (p === undefined) {
          return true
        } else {
          var exemplar = document.getElementById(p);
          if (exemplar)
            return v == exemplar.value;
          else
            return false;
        }
        ;
      },
      function (_v, p) {
        var exemplar = document.getElementById(p);
        if (exemplar)
          return '两次密码输入不相同.';
        else
          return '没有可参考值!'
      },
      "",
      function(validation_instance) {
        var exemplar = document.getElementById(validation_instance.param);
        if (exemplar){
          jQuery(exemplar).bind('validate', function(){
            jQuery(validation_instance.element).trigger('validate');
          });
        }
      }
    ],
	//ajax判断是否存在值
    ['ajax',
      function (v, p, validation_instance, decoration_context, decoration_callback) {
        if (GenvCheck.validators_types['empty'].test(v)) return true;
        if (decoration_context && decoration_callback) {
          jQuery.getJSON(p, {value: v, id: validation_instance.element.id}, function(data) {
            decoration_callback.apply(decoration_context, [[data], true]);
          });
        }
        return true;
      }]
    ,
	//正则匹配,此用法不甚理解
    ['format',
      function(v, p) {
        var params = p.match(/^\/(((\\\/)|[^\/])*)\/(((\\\/)|[^\/])*)$/);        
        if (params.length == 7) {
          var pattern = params[1];
          var attributes = params[4];
          try
          {
            var exp = new RegExp(pattern, attributes);
            return exp.test(v);
          }
          catch(err)
          {
            return false
          }
        } else {
          return false
        }
      },
      function (_v, p) {
        var params = p.split('/');
        if (params.length == 3 && params[0] == "") {
          return '输入的值必须与 <span class="' + GenvValidateConfig.error_class + '">' + p.toString() + '</span> 相匹配.';
        } else {
          return '提供的值与<span class="' + GenvValidateConfig.error_class + '">' + p.toString() + '</span>不匹配.';
        }
      }
    ]
]
 

GenvCheck.addValidatorTypes(GenvValidateTypes);


$.fn.validate_callback = function(msg,action_type,options){
	this.each(function(){
		if(action_type == "failed"){
			change_error_style($(this),"add");
			change_tip($(this),msg,"add");
		}else{
			change_error_style($(this),"remove");
			change_tip($(this),null,"remove");
		}
	});
};
