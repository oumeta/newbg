<? if(!defined('IN_GENV')) exit('Access Denied');?>
<div >
 
	<form  id="moneyform" action="<? echo U('batchfu')?>" method='post' onsubmit='return false;'>
	 
	<input type=hidden name='comcate' value=<?=$comcate?>>
	<input type=hidden name='comid' value=<?=$comid?>>
	日期:<input type="text" size="20" class="input-w2 input-text date_input" name='postdate' id='postdate' readonly  />			
	选择银行:
	<select name=bankid id=bankid>
		 <? foreach((array)$bank as $key => $v) {?>
		 <option value=<?=$key?>><?=$v?></option>
		 <?}?>
	</select>
	金额:<input type="text" size="20" class="input-w2 input-text :required :number" name='money' id='money'   />	
	备注:<input type="text" size="20" class="input-w2 input-text" name='remark' id='remark'   />
		<input type=button onclick='batchfu()'  value='保存' />
	</form>
</div>
<div id='alreadyfu'><?=$alreadyfu?></div>
<script type="text/javascript">
<!--
	function batchfu(){ 
     
	    var j = $("#moneyform").serialize();//序列化name/value
	     
       $.ajax({
           url: "<? echo U('batchfu')?>",
           type:'POST',
           data: j,           
           success: function(e) { //返回的json数据 
		         str=$.parseJSON(e);
				 if(str.status==0){
					alert(str.info)			 
				 }else{
					 alert('已经付款成功');
					 $('form').each(function(index){
						$("form" )[index].reset();
					})
					
					$("#alreadyfu").html(str.info)
				   
				 }
				 
           } 
     })	
}
//-->
</script>