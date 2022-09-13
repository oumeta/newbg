<? if(!defined('IN_GENV')) exit('Access Denied');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title></title>

		<style type="text/css" media="screen">		 
		 @import '<?=$_public?>css/reset-fonts-grids.css';	 
		 @import '<?=$_public?>css/local-common.css';
		@import '<?=$_public?>css/local-member.css';
		@import '<?=$_public?>css/yuiskin.css';
		@import '<?=$_public?>css/form.css';
		@import url(<?=$_public?>newcss/jquery.flexbox.css);
		@import '<?=$_public?>newcss/checkform.css';
		.none{display:none;}	
		</style>
	</head>
<script src="<?=$_public?>script/genv.js" type="text/javascript"></script>

<script src="<?=$_public?>script/jquery.js" type="text/javascript"></script>
<script src="<?=$_public?>script/common.js" type="text/javascript"></script> 
<script src="<?=$_public?>newjs/jquery2.flexbox.js" type="text/javascript"></script>
<script src="<?=$_public?>newjs/checkform.js" type="text/javascript"></script>

<script type="text/javascript">
<!--
	<?=$pageinfo?>	
//-->
</script>


 <body class="yui-skin-sam"> 
 <div style="z-index: 2; visibility: visible; left: 10px; top: 10px;" id="toolBoxHolder_c" class="yui-panel-container shadow">
 <div style="visibility: inherit; width: 550px;" class="yui-module yui-overlay yui-panel" id="toolBoxHolder">
    <div id="toolBoxHolder_h" style="cursor: move;" class="hd">商户信息添加</div>
    <div class="bd">
    <form id="form" action="<? echo U('save')?>" method=post>
        <fieldset>
            <legend>基本信息</legend>
           
			
			<div >
			    <label >业务:
				<span id=userid >  </span>			 
				</label>				
			</div>
			<div>
				<label>名称:
					 <input type="text" id="j_company" name="j_company" class="input-w2 input-text :required " value="<?=$rs['j_company']?>"    />
				</label>				
			</div>
			<div >
				<label>地址:
					 <input type="text" id="j_address" name="j_address" class="input-w2 input-text" value="<?=$rs['j_address']?>"   />
				</label>				
			</div>
			<div >
				<label>工厂:
					 <input type="text" id="j_factory" name="j_factory" class="input-w2 input-text" value="<?=$rs['j_factory']?>"   />
				</label>				
			</div>
			<div >
				<label>备注:
					<textarea id="remark" name="remark"  rows=5 cols=50 ><?=$rs['remark']?></textarea>
					 
				</label>				
			</div>
		 
        </fieldset>
        
       <div class=none>
	     <input type="text" id="id" name="id" class="input-w2 input-text" value="<?=$rs['id']?>"   />
		 <input type="text" id="cate" name="cate" class="input-w2 input-text" value="<?=$rs['cate']?>"   />
		 <input type="text" id="mid" name="mid" class="input-w2 input-text" value="<?=$mid?>"   />
		 <input type="text" id="doact" name="doact" class="input-w2 input-text" value="<?=$doact?>"   />
	   
	   </div>

		<div>
        	<span id="setHeader" class="yui-button yui-push-button"><span class="first-child"><button id="btsave" tabindex="0" type="button" class='submit'>保存信息</button></span></span>
		</div>
		 

    </form>
    </div>
</div>
<div class="underlay"></div>

</div>


 

<div style="z-index: 2; visibility: visible; left: 580px; top: 10px;width:400px;" class="yui-module yui-overlay " id="classPath">
 <div style="z-index: 2; visibility: visible; left: 0px; top: 0px;" id="toolBoxHolder_c" class="yui-panel-container shadow">
 <div style="visibility: inherit; width: 400px;" class="yui-module yui-overlay yui-panel" id="toolBoxHolder">
    <div id="toolBoxHolder_h" style="cursor: move;" class="hd">联系人</div>
    <div class="bd">
    <form id="formlinker">
        <fieldset>
            <legend>基本信息</legend>
             <div >
				<label>姓名:
					 <input type="text" id="name" name="name" class="input-w2 input-text" value=""   />
				</label>
				<br>
				<label>电话:
					 <input type="text" id="tel" name="tel" class="input-w2 input-text" value=""   />
				</label>
				 <br>
				<label>邮箱:
					 <input type="text" id="email" name="email" class="input-w2 input-text" value=""   />
				</label><br>
				 <input type="hidden" id="cid" name="cid" class="input-w2 input-text" value="<?=$rs['id']?>"   />
				 <input type="hidden" id="id" name="id" class="input-w2 input-text" value=""   />
				<div>
					<span id="setHeader" class="yui-button yui-push-button"><span class="first-child"><button id="linksave" tabindex="0" type="button">保存信息</button></span></span>
				</div>
			</div>
        </fieldset>
		 <fieldset>
            <legend>联系人列表</legend>
             <div id=linkerlist>
				 <?=$linkerlist?>
			</div>
        </fieldset>
    </form>
    </div>
</div>
<div class="underlay"></div>

</div>
</div>

 
<script type="text/javascript">
<!--
$(function(){
var url="<? echo U('api/getsalesmanlist')?>";
var ffb7=$('#userid').flexbox(url, {  
			 displayValue:'username',
			 hiddenValue:'id',
			 allowInput: false,
			// initialValue:"<?=$rs['username']?>",
			 
			 resultTemplate: '{username}',
			 
			 watermark: '选择业务员',
			 width: 252,			  
			 onSelect: function() {
			       
			       //bill.getcustomer($('#div_salersman_hidden').val());
				//	alert( );
						 
			}					 
		});	
//ffb7.setValue('<?=$rs['username']?>');  
ffb7.setValue('<?=$rs['userid']?>','<?=$rs['username']?>');  



	$("#linksave").click(function(){
	
		var j = $("#formlinker").serialize();//序列化name/value	 
		 
       $.ajax({
           url: "<? echo U('savelinker')?>",
           type:'POST',
           data: j,          
           success: function(e) { //返回的json数据
               $("#linkerlist").html(e);
           } 
     })	
	});

}) 
function $1(s){return document.getElementById(s);}

function mode(id){
$1("formlinker").reset();
$("#background").show();
		$("#progressBar").show();		 
		$.ajax({ 
				url: PI.URL+"/editlinker/?id="+id+"&r="+Math.floor(Math.random() * 2147483648).toString(36), 				
				ifModified:true,
				success: function(e){				 
					str=$.parseJSON(e);	
					try{					 
						$.each(str,function(i,n){	
						 
						    
							$("#formlinker  #"+i).val(n);						
							 
						})	
					}catch(e){
					// alert(e)
					
					}
					$("#background").hide();
					$("#progressBar").hide();	
				}
			});


}
function del(id){
$1("formlinker").reset();
$("#background").show();
		$("#progressBar").show();
		var cid=$("#id").val();
		$.ajax({ 
				url: PI.URL+"/dellinker/?id="+id+"&cid="+cid+"&r="+Math.floor(Math.random() * 2147483648).toString(36), 				
				ifModified:true,
				success: function(e){				 
					//str=$.parseJSON(e);	
					 $("#linkerlist").html(e);
					$("#background").hide();
					$("#progressBar").hide();	
				}
			});


}

VanadiumForm.prototype.success=function(){
 
   var bid=$('#userid_hidden').val()
     if(Genv.isEmpty(bid)){
		 alert('请选择业务员')
       	 $('#userid_input').focus();
		 return false;
	 }
	  
	 $("#form").submit();
	return false;
				
}
//-->
</script>