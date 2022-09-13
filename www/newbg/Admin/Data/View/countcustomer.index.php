<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <? include $this->gettpl('formhead');?>
<div  >
   <form method='post' action="<? echo U('index')?>" >
	客户:<span id="d1"  ></span>	 
	日期:<input name="d2" type="text" id="d2" class="date_input" value=" " />
	日期:<input name="d3" type="text" id="d3" class="date_input" value=" " />
	<input type=submit value='查询'>
	</form>
</div>  
 <table border=1 width=100%>
 <tr bgcolor=white><td>序号</td><td>公司名称</td><td>交易数</td></tr>
 <? foreach((array)$dlist as $key => $v) {?>
 <tr><td><?=$key?></td><td><?=$v['kname']?></td><td><?=$v['countid']?></td> </tr>
 <?}?>
 </table>

 <script type="text/javascript">
 <!--
$(function(){
    var url="<? echo U('api/getcustomerlist')?>";
	$('#d1').flexbox(url, {  
		 displayValue:'j_company',
		 hiddenValue:'id',
		 allowInput: false,
		 
		 resultTemplate: '{j_company}',
		 
		 watermark: '选择客户',
		 width: 260,
		  
		 onSelect: function() {
			   
			   //bill.getcustomer($('#b_busid_hidden').val());
			//	alert( );
					 
		}					 
	});	

})
 //-->
 </script>
 <? include $this->gettpl('formfoot');?>