<? if(!defined('IN_GENV')) exit('Access Denied');?>
<style type="text/css">

/* Pages default */
.page { display:block; overflow:hidden;float:left; width:100%;}
.page .pageHeader { display:block; overflow:auto; margin-bottom:1px; padding:5px; border-style:solid; border-width:0 0 1px 0; position:relative;}
.page .searchBar {}
.page .searchBar .searchContent { display:block; overflow:hidden; _height:25px;}
.page .searchBar .searchContent li { float:left; display:block; overflow:hidden; width:300px; height:25px; padding:2px 0;}
.page .searchBar label { float:left; width:80px; padding:0 5px; line-height:21px; line-height:23px;}
.page .searchBar .textInput {}
.page .searchBar .subBar { height:25px;}
.page .searchBar .subBar ul { float:right;}
.page .searchBar .subBar li { float:left; margin-left:5px;}
.page .searchBar .subBar .button_s {}

.page .pageContent { display:block;overflow:auto;position:relative;}
ul, ol { list-style:none;}	
	/* Pages Form */
.pageForm { display:block; overflow:auto;}
.pageFormContent { display:block; overflow:auto; padding:10px 5px; position:relative;}
.pageFormContent div { clear:both; display:block; overflow:hidden; height:auto;}
.pageFormContent div.unit {display:block; _width:100%; margin:0; padding:5px 0; position:relative;}
.pageFormContent p { float:left; display:block; width:380px; height:21px; margin:0; padding:5px 0; position:relative;}
.pageFormContent p.textareaBar { height:auto;}
.pageFormContent .radioGroup { float:left; display:block; overflow:hidden;}
.pageFormContent label { float:left; width:120px; padding:0 5px; line-height:21px;}
.pageFormContent label.radioButton { float:left; width:auto; padding:0 10px 0 0; line-height:21px;}
.pageFormContent .textInput { float:left;}
.pageFormContent select { float:left;}
.pageFormContent .inputInfo { padding:0 5px; line-height:21px;}
.pageFormContent span.unit, .pageFormContent a.unit { padding:0 5px; line-height:21px;}
.pageFormContent span.info{color:#7F7F7F;display:block;line-height:21px;float:left;}

.pageForm .formBar { clear:both; padding:0 5px; height:30px; padding-top:5px; border-style:solid; border-width:1px 0 0 0;}
.pageForm .formBar ul { float:right;}
.pageForm .formBar li { float:left; margin-left:5px;}
</style>
 <script language="JavaScript" src="<?=$_public?>script/jquery-1.5.min.js"></script>

<div class="page">
	<div class="pageContent">
	
	<form method="post" id=myform action="<? echo U('savepwd')?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
		<div class="pageFormContent" layoutH="58">

			<div class="unit">
				<label>旧密码：</label>
				<input type="password" name="password[]" size="30" minlength="6" maxlength="20" class="required"/>
			</div>
			<div class="unit">
				<label>新密码：</label>
				<input type="password" id="password[]" name="password[]" size="30" minlength="6" maxlength="20" class="required alphanumeric"/>
			</div>
			<div class="unit">
				<label>重复新密码：</label>
				<input type="password" name="password[]" size="30" equalTo="#cp_newPassword" class="required alphanumeric"/>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent">	<button id="btsave" tabindex="0" type="button">修改</button></div></div></li>
			 
			</ul>
		</div>
	</form>
	
	</div>
</div>
 <script type="text/javascript">
 <!--

 $(function(){
 
 $("#btsave").click(function(){
		 
		  var j = $("#myform").serializeArray();//序列化name/value
			 
		   $.ajax({
			   type: "POST",
			   url: "<? echo U('savepwd')?>",
			   dataType: 'json',
			    data: j,
			   //jsonp: 'callback',
			   success: function(e) { //返回的json数据
			   
				if(e.status==0){
				  alert(e.info)
				 }else if(e.status==1){
				 
				parent.window.location.href='<? echo U('logout')?>'
				 
				 }
			   } 
		 })
	})
 })
	
 //-->
 </script>