<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <? include $this->gettpl('pageform');?>
 <style type="text/css">
	.left1{
	text-align:left;
	padding-left:10px;
	
	}
 </style>
 
 
<body>
 
<form action="<? echo U('billsave')?>" method="post" id=powerform name=powerform>
<table border=1 width=400 align=center>
<tr><td>动作</td><td><input type="checkbox" name="selAll" id="selAll"  class="checkbox" >订单状态</td><td><input type="checkbox" name="selAll2" id="selAll2"  class="checkbox" >业务员范围</td><td><input type="checkbox" name="selAll3" id="selAll3"  class="checkbox" >操作员范围</td></tr>
<? foreach((array)$actlist as $key => $v) {?>
	 
	 
	<tr><td ><?=$v?></td>
		<td align=left>
		 
		<? foreach((array)$billstatus as $m => $n) {?>
			&nbsp;&nbsp;<input type=checkbox  name="status[]" id="status[]" value="<?=$key?>_<?=$m?>" <? if($billpower['status'][$key.'_'.$m]) { ?>checked<? } ?>><?=$n?> <br>
              
		<?}?>
		</td>
		<td align=left>
		 
		<? foreach((array)$deplist as $x => $y) {?>
		&nbsp;&nbsp;<input type=checkbox  name="limitd[]" id="limitd[]"  value="<?=$key?>_<?=$x?>" <? if($billpower['limitd'][$key.'_'.$x]) { ?>checked<? } ?>><?=$y?> <br>
			  
		<?}?>
		</td>
		<td align=left>
		 
		<? foreach((array)$deplist as $x => $y) {?>
		&nbsp;&nbsp;<input type=checkbox  name="limitd1[]" id="limitd1[]"  value="<?=$key?>_<?=$x?>" <? if($billpower['limitd1'][$key.'_'.$x]) { ?>checked<? } ?>><?=$y?> <br>
			  
		<?}?>
		</td>
	</tr>
	 
<?}?>


</table>
 <input name="roleid" type="hidden" id="roleid"  value="<?=$roleid?>">
 <input type="submit" value="保存权限 "  class="button">
</form> 
<SCRIPT LANGUAGE="JavaScript">
<!--
//thePage.flashshow();

$(document).ready(function(){
	 
	// thePage.flashhide();
	
})
 $("#selAll").click(			
		 function(){
		    var This=this;
			$("input[id='status[]']").each(function(){
					 $(this).attr("checked",$(This).attr("checked")) 					 
					})
})
 $("#selAll2").click(			
		 function(){
		    var This=this;
			$("input[id='limitd[]']").each(function(){
					 $(this).attr("checked",$(This).attr("checked")) 					 
					})
})
 $("#selAll3").click(			
		 function(){
		    var This=this;
			$("input[id='limitd1[]']").each(function(){
					 $(this).attr("checked",$(This).attr("checked")) 					 
					})
})
//-->
</SCRIPT>