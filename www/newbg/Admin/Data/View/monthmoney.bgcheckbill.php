<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('moneyhead');?>

<style type="text/css">
	input[type="text"] {
		background: url("../images/input-bg.png") repeat-x scroll 0 0 #FFFFFF;
		height: 20px;
		width: 100px;
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
 <div  >
   <form method='post' action="<? echo U()?>" >


    报关公司：<select name=comid id=comid>
	<option value="-1">请选择</option>
	<? foreach((array)$comlist as $k => $v) {?>
		<option value="<?=$v['id']?>" <? if($comid==$v['id']) { ?>selected<? } ?>><?=$v['name']?></option>
     <?}?>
	 </select>(必选项)
	开始日期:<input name="d1" type="text" id="d1" class="date_input" value="<?=$d1?>" />
	结束日期:<input name="d2" type="text" id="d2" class="date_input" value="<?=$d2?>" />

    年份：<select name=d3 id=d3>
	<option value="0">请选择年</option>
	<? for($i=2008;$i<2050;$i++){?>
		<option value="<?=$i?>" <? if($d3==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>
	月份:<select name=d4 id=d4>
	<option value="0">请选择月</option>
	<? for($i=1;$i<=12;$i++){?>
		<option value="<?=$i?>" <? if($d4==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>


 	<input type=submit value='查询'>&nbsp;&nbsp;<input type=button onclick=clear1() value='取消查询' >
	<input type="button"  onclick='importdata(0);' value='导出' />
	</form>
</div>


 <table    class=reporttable cellspacing="0" width=1000 align=center>
 <caption><?=$company?>(供应商) </caption>
 <tr> <th style='width:60px;'>序号</th>
		    <th style='width:60px;'>ID</th>
			<th style='width:60px;'>单号</th>
			<th style='width:60px;'>柜号</th>
			<th style='width:60px;'>S/O</th> <th style='width:60px;'>品名</th>
			<th style='width:60px;'>报关费</th>
			<th style='width:60px;'>港建费</th>
			<th style='width:60px;'>互认费/卫检费
</th>
			<th style='width:60px;'>其它费</th>
		    <th style='width:60px;'>日期</th>

</tr>
 <? foreach((array)$list as $key => $v) {?>


		<tr>
		<td>&nbsp;<?=$key?></td>
		<td>&nbsp;<?=$v['id']?></td>
		<td>&nbsp;<?=$v['b_code']?></td>

		<td>&nbsp;<?=$v['b_tank_code']?></td>
		<td>&nbsp;<?=$v['b_so']?></td>
		<td>&nbsp;<?=$v['b_product_name']?></td>

		<td>&nbsp;<?=$v['pc_bgmoney']?></td>
		<td>&nbsp;<?=$v['pc_portmoney']?></td>
		<td>&nbsp;<?=$v['pc_tankmoney']?></td>
		<td>&nbsp;<?=$v['pc_other']?></td>
		<td>&nbsp;<?=$v['postdate']?></td>
		</tr>
 <?}?>
 <tr><td> 合计</td>
		<td>&nbsp;</td><td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>
		<td><?=$countdata['pc_bgmoney']?></td>
		<td><?=$countdata['pc_portmoney']?></td>
		<td><?=$countdata['pc_tankmoney']?></td>
		<td><?=$countdata['pc_other']?></td>
		<td>&nbsp;</td>
		</tr>
 </table>
 <? include $this->gettpl('monthmoney');?>
 <? include $this->gettpl('formfoot');?>

 <script type="text/javascript">
 <!--
	function importdata(){

window.location.href='<? echo U("import/bgcheckbill?guid=".$guid)?>';

}
 //-->
 </script>
