<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('pageform');?> 
<style type="text/css" media="screen">
		@import '<?=$_public?>css/mycss.css';
		body { overflow:hidden; }

</style>
<SCRIPT LANGUAGE="JavaScript"><!--
<?=$pageinfo?>
//-->	
</SCRIPT>
<style>
html, body, img, a img, form {
	margin: 0;
	padding: 0;
	border: 0 none;	
	/*filter:alpha(opacity=80);*/
	 
} 
.left1{
float:left;
padding-left:20px;
}
.left1 table td{
text-align:left;
}
</style>
<body>
 
<form action="<? echo U('cachesave')?>" method="post" id=powerform name=powerform>
<fieldset id="fs" class=left1>
<legend>更新缓存</legend>
 
<div id="divFrom">
  <form action="{:U('cachesave')}" method="post" id=myform name=myform >
	 <table  border=0 cellspacing=0 cellpadding=0  width=100% >
		<tr>
		  <td><input type="checkbox" name="selAll" id="selAll1"  class="checkbox" >选择全部
	  </td>
		</tr>
		<? foreach((array)$cache as $key => $dir) {?>
				<tr>
				  <td style="background-color:#DDEDFB"><?=$key?></td>
				  </tr>
			   
				<tr style="background-color:#F4FAFB">
				  <td style='padding-left:50px'>
				  <? foreach((array)$dir as $key1 => $sub) {?>
				 
					<input type="checkbox" name="keyid[]" id="keyid[]" value="<?=$sub?>" class="checkbox"> <?=$key1?><br>
				  <?}?></td>
				</tr>

 		<?}?>
		            
	 </table>
	<div class="sub">
		<input name="id" type="hidden" id="id" value="{<?=$list?>.id}">   
 		<input type="submit"   value=" 提 交[s] " accesskey="S" class="button">
  </div>
 </form>
</fieldset>


<div id=divhelp>
		 	<ul>
				<li>系统设置项里的操作，完成之后重新系统缓存</li>
			 
			</ul>  
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
//thePage.flashshow();

$(document).ready(function(){
	 
	 $("#background").hide();
	 $("#progressBar").hide();

	
})
 $("#selAll1").click(
		 function(){
		 
		    var This=this;
			$("input[id='keyid[]']").each(function(){
			 
					 $(this).attr("checked",$(This).attr("checked")) 					 
					})
		})

//-->
</SCRIPT>