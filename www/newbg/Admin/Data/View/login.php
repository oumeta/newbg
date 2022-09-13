<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-cn" lang="zh-cn">

    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <style type="text/css" media="screen">
            @import '<?=$_public?>style/login.css';
        </style>
       
    </head>
    <!--[if IE 6]>
        <script type="text/javascript" src="<?=$_public?>script/iepng.js?version=v1">
        </script>
    <![endif]-->
    <style type="text/css">
	.logincbg input.name:focus,
    .logincbg input.password:focus {
        -moz-box-shadow: 0 0 10px #FFF;
        -webkit-box-shadow: 0 0 10px #FFF;
        box-shadow: 0 0 10px #FFF;
    }

    </style>
    </head>
    
    <body>
        <div id="box">
            <div class="wrap">
                <div class="hd">
                    <div class="logo" onclick="javascript:" title="管理平台" alt="管理平台 logo">
                        <span>
                            管理平台
                        </span>
                    </div>
                </div>
                <div class="db">
                    <div class="lbg">
                    </div>
                    <div id="DbankMarquee" class="cbg">
                        <div style="margin-top: 10px;">
                        </div>
                        <div id="banner_bg">
                        </div>
                        <!--标题背景-->
                        <span id="banner_info">
                            最近更新
                        </span>
                        <!--标题-->
                        <ul id="list">
                           
                        </ul>
                        <div id="banner_list">
		<a href=""  target="_blank"><img src="<?=$_public?>/banner/b1.jpg" title="智能化办公,高效、便捷" alt="" /></a>
   		<a href=""   target="_blank"><img src="<?=$_public?>/banner/b2.jpg" title="方便灵活，工作助手" alt="" /></a> 
	</div>

<script language="javascript">

var ScrollImage = function() {
	function id(name) {return document.getElementById(name);}
	//遍历函数
	function each(arr, callback) {
		if (arr.forEach) {arr.forEach(callback);} 
		else { for (var i = 0, len = arr.length; i < len; i++) callback.call(this, arr[i], i, arr);}
	}
	function fadeIn(elem) {
		setOpacity(elem, 0)
		for ( var i = 0; i < 1; i++) {
			(function() {
				var pos = 100;
				setTimeout(function() {
					setOpacity(elem, pos)
				}, i * 100);
			})(i);
		}
	}
	function fadeOut(elem) {
		for ( var i = 0; i <= 1; i++) {
			(function() {
				var pos = 0;
				setTimeout(function() {
					setOpacity(elem, pos)
				}, i * 100);
			})(i);
		}
	}
	// 设置透明度
	function setOpacity(elem, level) {
		if (elem.filters) {
			elem.style.filter = "alpha(opacity=" + level + ")";
		} else {
			elem.style.opacity = level / 100;
		}
	}
	return {
		//count:图片数量，wrapId:包裹图片的DIV,ulId:按钮DIV,	infoId：信息栏
		scroll : function(count,wrapId,ulId,infoId) {
			var self=this;
			var targetIdx=0;      //目标图片序号
			var curIndex=0;       //现在图片序号
			//添加Li按钮
			var frag=document.createDocumentFragment();
			this.num=[];    //存储各个li的应用，为下面的添加事件做准备
			this.info=id(infoId);
			for(var i=0;i<count;i++){
				(this.num[i]=frag.appendChild(document.createElement("li"))).innerHTML=i+1;
			}
			id(ulId).appendChild(frag);
			
			//初始化信息
			this.img = id(wrapId).getElementsByTagName("a");
			this.info.innerHTML=self.img[0].firstChild.title;
			this.num[0].className="on";
			//将除了第一张外的所有图片设置为透明
			each(this.img,function(elem,idx,arr){
				if (idx!=0) setOpacity(elem,0);
				else elem.style.zIndex=2; 
			});
			
			//为所有的li添加点击事件
			each(this.num,function(elem,idx,arr){
				elem.onclick=function(){
					self.fade(idx,curIndex);
					curIndex=idx;
					targetIdx=idx;
				}
			});
			
			//自动轮播效果
			var itv=setInterval(function(){
				if(targetIdx<self.num.length-1){
					targetIdx++;
				}else{
					targetIdx=0;
					}
				self.fade(targetIdx,curIndex);
				curIndex=targetIdx;
				},5000);
			id(wrapId).onmouseover=function(){ clearInterval(itv)};
			id(wrapId).onmouseout=function(){
				itv=setInterval(function(){
					if(targetIdx<self.num.length-1){
						targetIdx++;
					}else{
						targetIdx=0;
						}
					self.fade(targetIdx,curIndex);
					curIndex=targetIdx;
				},5000);
			}
		},
		fade:function(idx,lastIdx){
			if(idx==lastIdx) return;
			var self=this;
			setOpacity(self.img[idx], 0);
			fadeOut(self.img[lastIdx]);
			fadeIn(self.img[idx]);

				each(self.num,function(elem,elemidx,arr){
					if (elemidx!=idx) {
						self.num[elemidx].setAttribute("className","");
						self.num[elemidx].setAttribute("class","");
				
					}else{
						self.num[elemidx].setAttribute("className","on");
						self.num[elemidx].setAttribute("class","on");
						
						}

			   });
				self.img[idx].style.zIndex="10";
				self.img[lastIdx].style.zIndex="0";
			this.info.innerHTML=self.img[idx].firstChild.title;
		}
	}
}();

ScrollImage.scroll(2,"DbankMarquee","list","banner_info");
</script>
</div>

                    <div class="rbg">
                    </div>
                </div>
                <div class="login">
                    <div class="loginlbg">
                    </div>
                    <div class="logincbg">
                        <h1 title="登录">
                            登录您办公平台
                        </h1>
                        <form method="post" action="<? echo U('login')?>"
                        onsubmit="return submitLogin();" name="userForm" id="userform">                         
                           
                            <input id="btn_logined" value="" type="hidden">
                            <span id="tip" class="tip">
                                &nbsp;
                            </span>
                            <span class="input">
                                帐号：
                                <em id="namectn" class="inputbg selectbg">
                                    <input id="username" name="username" type="text">
                                </em>
                                <em style="display: block;" id="nametip" class="usertip">
                                    &nbsp;请输入您的帐号
                                </em>
                            </span>
                            <span class="input" style="margin-top: 5px;">
                                密码：
                                <em id="pwctn" class="inputbg">
                                    <input id="password" name="password" type="password">
                                </em>
                                <em style="display: block;" id="passwordtip" class="pwtip">
                                    &nbsp;密码长度6-32位
                                </em>
                            </span>
                            <span class="btn">
                                &nbsp;
                                <input id="btn_submit" value="" class="loginbtn" title="立即登录" type="submit">
                                <a href="" title="忘记密码">
                                    忘记了密码？
                                </a>
                            </span>
                            <span class="linebg">
                                &nbsp;
                            </span>
                        </form>
                    </div>
                    <div class="loginrbg">
                    </div>
                </div>
                <div class="ft">
                    <div class="ftlbg">
                    </div>
                    <div class="ftcbg">
                        <ul>
                           
                            <li>
                                <a href="" target="_blank">
                                    微博
                                </a>
                                |
                            </li>
                            <li>
                                <a href="" target="_blank">
                                    论坛
                                </a>
                                |
                            </li>
                            <li>
                                <a href="" target="_blank" rel="nofollow">
                                    帮助
                                </a>
                                |
                            </li>
                            <li>
                                <a href="" target="_blank">
                                    关于我们
                                </a>
                                |
                            </li>
                            <li>
                                <a href="" target="_blank" rel="nofollow">
                                    法律声明
                                </a>
                                |
                            </li>                           
                            <li class="last">
                                <a class="icpnum" href="" target="_blank">
                                    ICP备09062682号
                                </a>
                            </li>
                            <li>
                                <a href="" title="dbank网盘">
                                    <strong style="display: none;">
                                        
                                    </strong>
                                    Copyright © 2009 - 2010 DBank All Rights Reserved
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="ftrbg">
                    </div>
                </div>
            </div>
            <!-- Endwrap -->
        </div>


 <script type="text/javascript" src="<?=$_public?>script/login.js?version=v1"></script>
    </body>

</html>