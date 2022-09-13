  function submitLogin() {
            var logined = trim(document.getElementById("btn_logined").value);
            if (logined == 1) return;

            if (!hitName() || !hitPassword()) {
                return false;
            }

            document.getElementById("btn_logined").value = 1;
            return true;
        }

        function hitName() {
            var nameFoucs = document.getElementById("username");
            var name = trim(nameFoucs.value);
			 
            if (!name) {
                document.getElementById("tip").innerHTML = "帐号不能为空";
                return false;
            }  
            document.getElementById("tip").innerHTML = " ";
            return true;
        }

        function hitPassword() {
            var passwordFoucs = document.getElementById("password");
            var password = passwordFoucs.value;
            if (password.length < 4 || password.length > 32) {
                document.getElementById("tip").innerHTML = "密码长度4-32位";
                return false;
            }

            document.getElementById("tip").innerHTML = " ";
            return true;
        }

        function isemail(s) {
            if (s.length > 100 || s.length == 0) return false;
            if (s.indexOf("'") != -1 || s.indexOf("/") != -1 || s.indexOf("\\") != -1 || s.indexOf("<") != -1 || s.indexOf(">") != -1) return false;
            if (s.indexOf(" ") > -1 || s.indexOf("　") > -1) {
                return false;
            }
            //var regu="^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[_.0-9a-zA-Z]+))@{1}(([a-zA-Z0-9-]+[.]{1})([a-zA-Z0-9-]+))+$";
            var regu = "^([a-z0-9_\\.-]+)@([\\da-z\\.-]+)\\.([a-z\\.]{2,6})$";
            var re = new RegExp(regu, "i");
            s = s.replace("；", ";");
            s = s.replace("<", "");
            s = s.replace(">", "");
            s = s.replace('(', '');
            s = s.replace(')', '');
            s = s.replace('（', '');
            s = s.replace('）', '');
            var mail_array = s.split(";");
            var part_num = 0;
            var isname = true;
            while (part_num < mail_array.length) {
                if (mail_array[part_num].search(re) == -1) {
                    isname = false;
                }
                part_num += 1;
            }

            if (!isname) {
                var reg = new RegExp("^((\\+{0,1}86){0,1})1[0-9]{10}$");
                return reg.test(s);
            } else {
                return isname;
            }

        }
        function trim(s) {
            return s.replace(/^\s+|\s+$/i, '');
        }
		
 
(function(){
	function __bind( obj, type, fn ) {
		if ( obj.attachEvent ) {
			obj['e'+type+fn] = fn;
			obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
			obj.attachEvent( 'on'+type, obj[type+fn] );
		} else
			obj.addEventListener( type, fn, false );
	}
	var nameInput = document.getElementById("username");
	var passwordInput = document.getElementById("password");
	var nameTip = document.getElementById("nametip");
	var passwordTip = document.getElementById("passwordtip");
	var nameContainer = document.getElementById("namectn");
	var passwordContainer = document.getElementById("pwctn");
	var tip = document.getElementById("tip");
	//添加进入网盘首页后自动聚焦到用户名输入框 add by yxp 2010—10—22
	
	setTimeout(function(){
		nameInput.focus();
	},100);
	
	//一开始，焦点放到用户名输入框，当有输入或者聚集时，判断是否已经有输入，有的话，隐藏提示，没有的话，显示提示
	__bind(nameInput, 'keyup', function(){

		if(nameInput.value.length === 0){
			nameTip.style.display = 'block';
		} else {
			nameTip.style.display = 'none';
		}
		setTimeout(function(){
			if(passwordInput.value.length === 0){
				passwordTip.style.display = 'block';
			} else {
				passwordTip.style.display = 'none';
			}
		}, 100);
	});
	__bind(nameInput, 'focus', function(){
		if(nameInput.value.length === 0){
			nameTip.style.display = 'block';
		} else {
			nameTip.style.display = 'none';
		}
		
		if(trim(passwordInput.value).length>0){
			hitPassword();
		};
		tip.innerHTML='';
		nameContainer.className = 'inputbg selectbg';
	});
	__bind(nameInput, 'blur', function(){
		nameContainer.className = "inputbg";
		tip.innerHTML='&nbsp;';
	});

	//点击用户名输入提示，焦点放到用户名输入框
	__bind(nameTip, 'click', function(){
		nameInput.focus();
	});


	//点击密码输入提示，焦点放到用户名输入框，提示消失
	__bind(passwordTip, 'click', function(){
		passwordInput.focus();
	});


	//如果焦点到密码输入框，密码提示消失
	__bind(passwordInput, 'focus', function(){
		setTimeout(function(){
			if(passwordInput.value.length === 0){
				passwordTip.style.display = 'block';
			} else {
				passwordTip.style.display = 'none';
			}
		}, 100);
		
		 hitName();
		
		passwordContainer.className = 'inputbg selectbg';
	});
	__bind(passwordInput, 'blur', function(){
		if(passwordInput.value.length === 0){
			passwordTip.style.display = 'block';
		}
		passwordContainer.className = 'inputbg';
		
	});
	__bind(passwordInput, 'keyup', function(e){
		var e=e?e:window.event;
		if(e.keyCode==32) {
			var v=this.value;
			this.value=trim(v);
		}
		
		if(passwordInput.value.length === 0){
			passwordTip.style.display = 'block';
		} else {
			passwordTip.style.display = 'none';
		}
	
	});
	
	nameInput.focus();
	if(nameInput.value.length == 0){
		nameTip.style.display = 'block';
	} else {
		nameTip.style.display = 'none';
	}
	
	 setInterval(function(){
	 	if(nameInput.value.length === 0){
			nameTip.style.display = 'block';
		} else {
			nameTip.style.display = 'none';
		}
	
	},200);

	// setPasswordHandle();
	 	 setInterval(function(){
			if(passwordInput.value.length == 0){
				passwordTip.style.display = 'block';
			} else {
				passwordTip.style.display = 'none';
			}
		},200);	


})();

 
