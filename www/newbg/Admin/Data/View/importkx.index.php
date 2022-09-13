<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('reporthead');?>

<style type="text/css">
	input[type="text"] {
		background: url("../images/input-bg.png") repeat-x scroll 0 0 #FFFFFF;
		height: 20px;
		width: 100px;
	}
	#banklist{
	width:850px;
	border:1px solid gray;
 
	 
	height:80px;
	 position:fixed; 	
	 left:10%; 
	 margin-left:-74px; 
	 padding:10px 10px 10px 50px;
	 text-align:left; line-height:27px; font-weight:bold; position:absolute;
	 background-color:white;

	
	}
	 
</style>
<script type="text/javascript">
<!--
	function clear1(){
	 
	 $("#d1").val('');
	 $("#d2").val('');
	 $("#d3").val(0);
	 $("#d4").val(0);
	 $("#comid").val(-1);
	}



 //-->
</script>



 <table    class=reporttable cellspacing="0" width=1000 align=center>
 <caption>  </caption>
 <tr> 
		    <th style='width:60px;'>ID</th>
			 <th style='width:60px;'>接单日期</th>
			<th style='width:60px;'>放行日期</th> 

			<th style='width:60px;'>港口</th> 
			<th style='width:60px;'>品名</th>
			 
		    <th style='width:60px;'>柜号/工作号</th> 
			 <th style='width:60px;'>费用</th> 
		  
</tr>

 <? foreach((array)$list as $key => $v) {?>
 
	     
		<tr>
		<td>&nbsp;<?=$key?></td>
		<td>&nbsp;<?=$v['postdate']?></td>	
		<td>&nbsp;<?=$v['finshdate']?></td>	
		<td>&nbsp;<?=$v['pc_port']?></td>	
		<td>&nbsp;<?=$v['b_product_name']?></td>	
		<td>&nbsp;<?=$v['b_tank_code']?></td>
		<td>&nbsp;<?=$v['count_shu']?></td>
		 	
		</tr> 
 <?}?>
 <tr><td> 合计</td>
		<td>&nbsp;</td>

		<td>&nbsp;</td>	 
		<td>&nbsp;</td>	 
		<td>&nbsp;</td>	 
		<td>&nbsp;</td>	 

	 
		<td><?=$countdata['count_shu']?></td>
	 
		 
		</tr> 
 </table>  
<div  >
   <form  method='post' id=myform name=myform action="<? echo U()?>" >
		<input type=hidden name='import_bill' id='import_bill' value=1>
		<input type=hidden name='d3'   value=<?=$d3?>>
		<input type=hidden name='d4'   value=<?=$d4?>>
		<input type=hidden name='kid'   value=<?=$kid?>>

		<div id=banklist>
        <ul>
		 <? foreach((array)$banklist as $key => $v) {?>
        <li style='width:200px;float:left;'>
		 <input type="checkbox" name="keyid[]" id="keyid[]" value="<?=$v['id']?>" class="checkbox1" ><?=$v['bankname']?>
		 </li>
		<?}?>
		</ul>

		<input type=button onclick=importOK()  value='导出'>
		</div>
</div>  
 <script type="text/javascript">
 <!--
 
	function importOK(){
	    $("#myform").get(0).submit()
		   
	}
 //-->
 </script>
 <? include $this->gettpl('formfoot');?>

 